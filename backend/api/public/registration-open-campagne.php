<?php

/**
 * Fibonacci – Bulk sender (Registrations Open) cu debug vizibil
 * - Test mode (trimite DOAR la 2 adrese)
 * - PHPMailer SMTP + transcript live pe pagină
 * - generateEmailUID(), trackCampaign(), logEmails() (există în header)
 * - Umple {{view_in_browser_url}} și {{register_url}}
 * - Marchează invites în live; se oprește pe rate/limit și alertează cu sendNotify()
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* ===============================
   Config
   =============================== */

$campaignSlug  = 'fibo-registrations-2026';
$subject       = 'Fibonacci Romania — Registrations are Open';
$templatePath  = $_SERVER['DOCUMENT_ROOT'] . '/assets/email/registration-open.html';

$viewBaseUrl   = 'https://fibonacci-olympiad.ro/email?view='; // + $emailUID
$trackEndpoint = 'https://fibonacci-olympiad.ro/email/track.php';
$registerFinal = 'https://fibonacci-olympiad.ro/register';

// Pune true ca să trimiți DOAR la adresele de test
$testMode   = false; // <<< setează false pentru live
$testEmails = [
    'lupsa.mihailucian@gmail.com',
    'lupsa.mihailucian1@gmail.com',
];

$liveBatchLimit = 500;

/* ===============================
   Utils locale
   =============================== */
function looksLikeRateLimit(string $msg): bool
{
    $msg = strtolower($msg);
    foreach (['rate', 'quota', 'limit', 'throttl', 'too many', 'maximum sending rate', 'daily user sending quota', '421', '450', '451', '452', '4.7.0', '5.7.0'] as $n) {
        if (strpos($msg, $n) !== false) return true;
    }
    return false;
}

function esc(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Redactează linii sensibile din transcript (AUTH/ucp/login/XOAUTH2 etc.)
 * Nu riscăm să-ți aruncăm credențialele SMTP în clar pe pagină.
 */
function sanitizeSmtpTranscript(array $lines): array
{
    $out = [];
    foreach ($lines as $line) {
        $l = (string)$line;
        if (stripos($l, 'AUTH') !== false || stripos($l, 'LOGIN') !== false || stripos($l, 'XOAUTH2') !== false) {
            $out[] = '[REDACTED AUTH LINE]';
        } else {
            $out[] = $l;
        }
    }
    return $out;
}

/* ===============================
   Template
   =============================== */
if (!is_file($templatePath)) {
    echo '<p style="color:#c00">Template missing: ' . esc($templatePath) . '</p>';
    exit;
}
$templateRaw = file_get_contents($templatePath);

/* ===============================
   Collect recipients
   =============================== */
$recipients = [];
if ($testMode) {
    foreach ($testEmails as $e) {
        $recipients[] = ['id' => 0, 'email' => $e];
    }
} else {
    $q = "SELECT id, email
        FROM invites
        WHERE is_sent = 0 AND email IS NOT NULL AND email <> ''
        ORDER BY id ASC
        LIMIT ?";
    $stmt = $conn->prepare($q);
    $stmt->bind_param('i', $liveBatchLimit);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) $recipients[] = $row;
    $stmt->close();
}

/* ===============================
   HTML head + streaming
   =============================== */
@ini_set('output_buffering', 'off');
@ini_set('zlib.output_compression', 0);
while (ob_get_level()) {
    ob_end_flush();
}
ob_implicit_flush(true);

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Fibonacci Bulk Sender Debug</title>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: #0b1020;
            color: #e9eefb;
            padding: 16px;
        }

        h1 {
            font-size: 18px;
            margin: 0 0 12px;
            color: #9dc1ff;
        }

        .meta {
            margin-bottom: 12px;
            color: #b8c6e3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border-bottom: 1px solid #1f2942;
            padding: 10px;
            vertical-align: top;
            font-size: 14px;
        }

        th {
            text-align: left;
            color: #9db4ff;
            position: sticky;
            top: 0;
            background: #0b1020;
        }

        .ok {
            color: #46d17f;
            font-weight: 600;
        }

        .fail {
            color: #ff6b6b;
            font-weight: 600;
        }

        details {
            margin-top: 6px;
        }

        summary {
            cursor: pointer;
            color: #9dc1ff;
        }

        pre {
            white-space: pre-wrap;
            margin: 6px 0 0;
            color: #cbd5f6;
        }

        .pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 12px;
            background: #17223c;
            color: #9db4ff;
        }
    </style>
</head>

<body>
    <h1>Fibonacci Bulk Sender Debug</h1>
    <div class="meta">
        Mode: <span class="pill"><?= $testMode ? 'TEST' : 'LIVE' ?></span>
        &nbsp;•&nbsp; Recipients: <span class="pill"><?= count($recipients) ?></span>
        &nbsp;•&nbsp; Campaign: <span class="pill"><?= esc($campaignSlug) ?></span>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:28%">Recipient</th>
                <th style="width:12%">Status</th>
                <th style="width:24%">Last SMTP Reply</th>
                <th style="width:18%">Message-ID</th>
                <th>Transcript</th>
            </tr>
        </thead>
        <tbody>
            <?php

            /* ===============================
   Mailer init
   =============================== */
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            // SMTP debug level 2: client + server messages
            $mail->SMTPDebug = 2;

            /* ===============================
   Send loop
   =============================== */
            $sent = 0;
            $failed = 0;
            $stoppedOnLimit = false;

            foreach ($recipients as $row) {
                $inviteId = (int)$row['id'];
                $to       = trim($row['email']);
                if ($to === '') continue;

                // colectăm transcriptul per-email
                $smtpTranscript = [];
                $mail->Debugoutput = function ($str, $level) use (&$smtpTranscript) {
                    $smtpTranscript[] = trim((string)$str);
                };

                // UID hex 32 (din helperul tău)
                $emailUID = generateEmailUID();

                // URL-uri pentru placeholders
                $viewInBrowserUrl = $viewBaseUrl . $emailUID;
                $registerUrl = $trackEndpoint
                    . '?a=click'
                    . '&c=' . urlencode($campaignSlug)
                    . '&uid=' . urlencode($emailUID)
                    . '&email=' . urlencode($to)
                    . '&t=register_url'
                    . '&r=' . urlencode($registerFinal);

                $htmlContent = str_replace(
                    ['{{view_in_browser_url}}', '{{register_url}}'],
                    [$viewInBrowserUrl, $registerUrl],
                    $templateRaw
                );

                $statusText = '';
                $statusClass = '';
                $lastReply = '';
                $messageId = '';
                $errMsg = '';

                try {
                    // SMTP config
                    $mail->clearAllRecipients();
                    $mail->isSMTP();
                    $mail->Host       = getenv('MAIL_HOST');
                    $mail->SMTPAuth   = true;
                    $mail->Username   = getenv('MAIL_USERNAME');
                    $mail->Password   = getenv('MAIL_PASSWORD');
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;

                    $mail->setFrom(getenv('MAIL_USERNAME'), 'Fibonacci Romania');
                    $mail->addReplyTo('office@fibonacci-olympiad.ro', 'Information');
                    $mail->addAddress($to);
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body    = $htmlContent;

                    if ($mail->send()) {
                        $messageId = (string)$mail->getLastMessageID();
                        $smtpInst  = $mail->getSMTPInstance();
                        if ($smtpInst && method_exists($smtpInst, 'getLastReply')) {
                            $lastReply = (string)$smtpInst->getLastReply();
                        }

                        // Tracking SEND
                        // trackCampaign($campaignSlug, $emailUID, $to, 'send', null);

                        // Log email (ai deja funcția)
                        logEmails($to, $subject, $htmlContent, 0, $emailUID);

                        // Update invites doar în live
                        if (!$testMode && $inviteId > 0) {
                            $upd = $conn->prepare("UPDATE invites SET is_sent = 1, sent_at = NOW(), last_error = NULL WHERE id = ?");
                            $upd->bind_param('i', $inviteId);
                            $upd->execute();
                            $upd->close();
                        }

                        $sent++;
                        $statusText  = 'SUCCESS';
                        $statusClass = 'ok';
                    } else {
                        $errMsg = $mail->ErrorInfo ?: 'Unknown error';
                        $smtpInst  = $mail->getSMTPInstance();
                        if ($smtpInst && method_exists($smtpInst, 'getLastReply')) {
                            $lastReply = (string)$smtpInst->getLastReply();
                        }

                        if (!$testMode && $inviteId > 0) {
                            $upd = $conn->prepare("UPDATE invites SET last_error = ? WHERE id = ?");
                            $upd->bind_param('si', $errMsg, $inviteId);
                            $upd->execute();
                            $upd->close();
                        }

                        if (looksLikeRateLimit($errMsg)) {
                            $stoppedOnLimit = true;
                            sendNotify('Mailing stopped (rate/limit)', "Stopped at invite #$inviteId <$to>\nError: {$errMsg}");
                        }

                        $failed++;
                        $statusText  = 'FAIL: ' . $errMsg;
                        $statusClass = 'fail';
                    }
                } catch (Exception $e) {
                    $errMsg = $e->getMessage();
                    $smtpInst  = $mail->getSMTPInstance();
                    if ($smtpInst && method_exists($smtpInst, 'getLastReply')) {
                        $lastReply = (string)$smtpInst->getLastReply();
                    }

                    if (!$testMode && $inviteId > 0) {
                        $upd = $conn->prepare("UPDATE invites SET last_error = ? WHERE id = ?");
                        $upd->bind_param('si', $errMsg, $inviteId);
                        $upd->execute();
                        $upd->close();
                    }

                    if (looksLikeRateLimit($errMsg)) {
                        $stoppedOnLimit = true;
                        sendNotify('Mailing stopped (exception)', "Stopped at invite #$inviteId <$to>\nError: {$errMsg}");
                    }

                    $failed++;
                    $statusText  = 'FAIL: ' . $errMsg;
                    $statusClass = 'fail';
                }

                // Redactăm transcriptul
                $safeTranscript = sanitizeSmtpTranscript($smtpTranscript);
                $safeLastReply  = $lastReply !== '' ? $lastReply : '[no last reply]';
                $safeMessageId  = $messageId !== '' ? $messageId : '[none]';

                // Afișare rând în tabel
                echo "<tr>\n";
                echo "  <td>" . esc($to) . "</td>\n";
                echo "  <td class='{$statusClass}'>" . esc($statusText) . "</td>\n";
                echo "  <td>" . esc($safeLastReply) . "</td>\n";
                echo "  <td>" . esc($safeMessageId) . "</td>\n";
                echo "  <td><details><summary>SMTP transcript</summary><pre>" . esc(implode("\n", $safeTranscript)) . "</pre></details></td>\n";
                echo "</tr>\n";

                @ob_flush();
                flush();

                if ($stoppedOnLimit) {
                    break;
                }
            }
            ?>
        </tbody>
    </table>

    <div class="meta" style="margin-top:12px">
        Sent: <span class="pill"><?= $sent ?></span>
        &nbsp;•&nbsp; Failed: <span class="pill"><?= $failed ?></span>
        &nbsp;•&nbsp; StoppedOnLimit: <span class="pill"><?= $stoppedOnLimit ? 'YES' : 'NO' ?></span>
    </div>

</body>

</html>