<?php

declare(strict_types=1);

/*
  sendAutoMail($templateKey, $toEmail, array $vars, array $opts = [])
  - Uses $conn from /backend/headerBackend.php (mysqli)
  - Renders subject + HTML with {{var}} (escaped) and {{{var}}} (raw)
  - Ensures view_url is present (auto-appends block if missing)
  - Inserts into email_messages (status queued or sent)
  - Sends immediately unless $opts['queue'] === true
*/

require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

function sam_respond_array(bool $ok, string $msg, array $extra = []): array
{
    return array_merge(['ok' => $ok, 'message' => $msg], $extra);
}

function sam_make_id(int $bytes = 13): string
{
    return bin2hex(random_bytes($bytes));
}

function sam_base_url(): string
{
    $env = getenv('APP_BASE_URL');
    if ($env) return rtrim($env, '/');
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
    return $scheme . '://' . $host;
}

function sam_sanitize_header(string $s): string
{
    return trim(str_replace(["\r", "\n"], '', $s));
}

function sam_render_string(string $str, array $vars): string
{
    $out = $str;
    $out = preg_replace_callback('/\{\{\{([\w\.]+)\}\}\}/u', function ($m) use ($vars) {
        $key = $m[1];
        $val = $vars[$key] ?? '';
        return is_scalar($val) ? (string)$val : '';
    }, $out);
    $out = preg_replace_callback('/\{\{([\w\.]+)\}\}/u', function ($m) use ($vars) {
        $key = $m[1];
        $val = $vars[$key] ?? '';
        $val = is_scalar($val) ? (string)$val : '';
        return htmlspecialchars($val, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }, $out);
    return $out;
}

function sam_html_to_text(string $html): string
{
    $text = preg_replace('/\s+/', ' ', strip_tags($html));
    return html_entity_decode(trim((string)$text), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function sam_transport_send(string $toEmail, ?string $toName, string $subject, string $html, string $text): bool
{
    $to = $toName ? (sam_sanitize_header($toName) . " <{$toEmail}>") : $toEmail;
    $boundary = 'bnd_' . sam_make_id(12);
    $headers = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
    $headers[] = 'From: Relativity Robotics <no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'fibonacci-olympiad.ro') . '>';
    $headers[] = 'Reply-To: no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'fibonacci-olympiad.ro');
    $body  = "--{$boundary}\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
    $body .= $text . "\r\n";
    $body .= "--{$boundary}\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
    $body .= $html . "\r\n";
    $body .= "--{$boundary}--\r\n";
    return @mail($to, sam_sanitize_header($subject), $body, implode("\r\n", $headers));
}

/*
  sendAutoMail
  $opts:
    - 'to_name'   => string|null
    - 'queue'     => bool (default false = send now)
*/
function sendAutoMail(string $templateKey, string $toEmail, array $vars, array $opts = []): array
{
    global $conn;

    if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
        return sam_respond_array(false, 'Invalid recipient email.');
    }

    $toName = isset($opts['to_name']) ? trim((string)$opts['to_name']) : null;
    $queue  = isset($opts['queue']) ? (bool)$opts['queue'] : false;

    $stmt = $conn->prepare('SELECT id, subject, html_body, required_fields FROM email_templates WHERE tkey = ? AND is_active = 1 LIMIT 1');
    if (!$stmt) return sam_respond_array(false, 'Template lookup failed.');
    $stmt->bind_param('s', $templateKey);
    $stmt->execute();
    $res = $stmt->get_result();
    $tpl = $res->fetch_assoc();
    $stmt->close();

    if (!$tpl) return sam_respond_array(false, 'Template not found or inactive.');

    $required = json_decode($tpl['required_fields'] ?: '[]', true);
    if (!is_array($required)) $required = [];
    $missing = [];
    foreach ($required as $k) {
        if (!array_key_exists($k, $vars) || $vars[$k] === null || $vars[$k] === '') $missing[] = $k;
    }
    if (!empty($missing)) {
        return sam_respond_array(false, 'Missing required fields: ' . implode(', ', $missing));
    }

    $publicId = sam_make_id(13);
    $viewUrl  = sam_base_url() . '/email/view.php?id=' . $publicId;

    if (!isset($vars['view_url'])) $vars['view_url'] = $viewUrl;

    $subject = sam_render_string($tpl['subject'], $vars);
    $html    = sam_render_string($tpl['html_body'], $vars);

    if (strpos($html, '{{view_url}}') === false && strpos($html, '{{{view_url}}}') === false) {
        $html .= '<div style="margin-top:24px;font-size:12px;color:#666;">'
            .  'If you cannot see this email correctly, <a href="' . htmlspecialchars($viewUrl, ENT_QUOTES, 'UTF-8') . '">open it in your browser</a>.'
            .  '</div>';
    }

    $text = sam_html_to_text($html);

    $conn->begin_transaction();

    try {
        $templateId = (int)$tpl['id'];
        $varsJson   = json_encode($vars, JSON_UNESCAPED_UNICODE);

        $ins = $conn->prepare('
      INSERT INTO email_messages
        (public_id, template_id, template_key, to_email, to_name, subject, html_body, text_body, variables, status)
      VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
        if (!$ins) throw new Exception('Prepare failed.');

        $status = $queue ? 'queued' : 'queued';  // always queued first; we'll update to sent if we actually send
        $ins->bind_param(
            'sissssssss',
            $publicId,
            $templateId,
            $templateKey,
            $toEmail,
            $toName,
            $subject,
            $html,
            $text,
            $varsJson,
            $status
        );
        if (!$ins->execute()) throw new Exception('Insert failed: ' . $ins->error);
        $messageId = (int)$conn->insert_id;
        $ins->close();

        if ($queue) {
            $conn->commit();
            return sam_respond_array(true, 'Queued', ['public_id' => $publicId, 'message_id' => $messageId, 'view_url' => $viewUrl]);
        }

        $sent = sam_transport_send($toEmail, $toName, $subject, $html, $text);
        if ($sent) {
            $upd = $conn->prepare('UPDATE email_messages SET status = "sent", sent_at = NOW() WHERE id = ? LIMIT 1');
            $upd->bind_param('i', $messageId);
            $upd->execute();
            $upd->close();
            $conn->commit();
            return sam_respond_array(true, 'Sent', ['public_id' => $publicId, 'message_id' => $messageId, 'view_url' => $viewUrl]);
        } else {
            $err = 'Transport failed';
            $upd = $conn->prepare('UPDATE email_messages SET status = "failed", error = ? WHERE id = ? LIMIT 1');
            $upd->bind_param('si', $err, $messageId);
            $upd->execute();
            $upd->close();
            $conn->commit(); // commit log even on fail
            return sam_respond_array(false, 'Failed to send email.', ['public_id' => $publicId, 'message_id' => $messageId, 'error' => $err]);
        }
    } catch (Throwable $e) {
        $conn->rollback();
        return sam_respond_array(false, 'Server error while sending.');
    }
}
