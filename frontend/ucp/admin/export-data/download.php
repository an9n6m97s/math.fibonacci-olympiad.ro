<?php
// Nu scoate output înainte de header-uri.
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/FPDF/fpdf.php';

$option = isset($_GET['type']) ? trim(strtolower($_GET['type'])) : null;
$format = isset($_GET['format']) ? trim(strtolower($_GET['format'])) : null;

$admin      = new Admin($conn);
$coaches    = $admin->getUsers();
$teams      = $admin->getTeams();
$members    = $admin->getMembers();
$robots     = $admin->getRobots();
$allmembers = array_merge($coaches, $members);

$validFormats = ['list', 'csv', 'pdf'];

/* ----------------- utils ----------------- */
function sanitizeText($s)
{
    $s = preg_replace('/\s+/', ' ', (string)$s);
    return trim($s);
}

function csvOutput(array $headers, array $rows, string $filenameBase)
{
    $filename    = $filenameBase . '_' . date('Y-m-d_H-i-s') . '.csv';
    $exportedAt  = date('Y-m-d H:i:s');

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: inline; filename="' . $filename . '"');

    $out = fopen('php://output', 'w');
    // BOM pentru Excel
    fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Meta
    fputcsv($out, ['# Exported at', $exportedAt]);
    fputcsv($out, []); // blank line

    // Header + rows
    fputcsv($out, $headers);
    foreach ($rows as $r) {
        $line = [];
        foreach ($headers as $h) {
            $line[] = $r[$h] ?? '';
        }
        fputcsv($out, $line);
    }
    fclose($out);
    exit;
}

function listOutput(array $headers, array $rows, string $title, string $filenameBase)
{
    $filename = $filenameBase . '_' . date('Y-m-d_H-i-s') . '.txt';
    header('Content-Type: text/plain; charset=utf-8');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    echo $title . PHP_EOL . str_repeat('=', mb_strlen($title)) . PHP_EOL . PHP_EOL;
    $i = 1;
    foreach ($rows as $r) {
        echo ($i++) . ". ";
        $parts = [];
        foreach ($headers as $h) {
            $parts[] = $h . ': ' . sanitizeText($r[$h] ?? '');
        }
        echo implode(' | ', $parts) . PHP_EOL;
    }
    exit;
}

/* ---------- FPDF subclass cu wrap corect ---------- */
class WrapPDF extends FPDF
{
    public $widths = [];
    public $aligns = [];

    public function __construct($orientation = 'L', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size);
        $this->SetAutoPageBreak(true, 15);
    }
    public function SetWidths($w)
    {
        $this->widths = $w;
    }
    public function SetAligns($a)
    {
        $this->aligns = $a;
    }

    public function Row($data)
    {
        // calculează înălțimea unui rând după cât de mult se rupe fiecare celulă
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i] ?? 30;
            $nb = max($nb, $this->NbLines($w, (string)$data[$i]));
        }
        $lineH = 6;
        $h = $lineH * $nb;

        // page break
        if ($this->GetY() + $h > $this->PageBreakTrigger) $this->AddPage($this->CurOrientation);

        // desenează celulele
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i] ?? 30;
            $a = $this->aligns[$i] ?? 'L';
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $w, $h);
            $txt = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', (string)$data[$i]);
            $this->MultiCell($w, $lineH, $txt, 0, $a);
            $this->SetXY($x + $w, $y);
        }
        $this->Ln($h);
    }

    // câte linii ocupă un text într-o MultiCell de lățime $w
    public function NbLines($w, $txt)
    {
        $cw = $this->CurrentFont['cw'];
        if ($w == 0) $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', (string)$txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') $sep = $i;
            $l += $cw[$c] ?? 0;
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) $i++;
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }
}

/* ---------- PDF renderers ---------- */

function pdfOutputSingleTableWrapped(array $headers, array $rows, string $title, string $filenameBase)
{
    if (!class_exists('FPDF')) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=utf-8');
        echo "PDF export needs FPDF (manual/composer) și autoload activ.";
        exit;
    }
    $filename   = $filenameBase . '_' . date('Y-m-d_H-i-s') . '.pdf';
    $exportedAt = date('Y-m-d H:i:s');

    $pdf = new WrapPDF('L', 'mm', 'A4');
    $pdf->AddPage();

    // Logo în colțul dreapta-sus
    $localLogo = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/logo/logo.png';
    $logoPath  = file_exists($localLogo) ? $localLogo : 'https://fibonacci-olympiad.ro/assets/images/logo/logo.png';
    $logoW = 28;     // lățimea logo-ului
    $margin = 10;    // marginea față de dreapta
    $pdf->Image($logoPath, $pdf->GetPageWidth() - $logoW - $margin, 10, $logoW);

    // Mută cursorul SUB logo (SetY resetează X la marginea stângă)
    $pdf->SetY(10 + $logoW + 6);

    // Titlu + data
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 12, $title, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, 'Exported at: ' . $exportedAt, 0, 1, 'L');
    $pdf->Ln(2);

    // lățimi coloane
    $widths = [];
    $total  = 270;
    foreach ($headers as $h) {
        if ($h === 'category_slug') $widths[] = 140;
        elseif ($h === 'name')          $widths[] = 55;
        elseif ($h === 'operator')      $widths[] = 25;
        elseif ($h === 'team_name')     $widths[] = 50;
        else                            $widths[] = max(30, floor($total / max(1, count($headers))));
    }
    $pdf->SetWidths($widths);
    $pdf->SetAligns(array_fill(0, count($headers), 'L'));

    // Header tabel
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Row(array_map(function ($h) {
        return strtoupper($h);
    }, $headers));

    // Rânduri
    $pdf->SetFont('Arial', '', 9);
    foreach ($rows as $r) {
        $row = [];
        foreach ($headers as $h) {
            $val = isset($r[$h]) ? (string)$r[$h] : '';
            if ($h === 'category_slug') $val = str_replace(',', ', ', $val);
            $row[] = $val;
        }
        $pdf->Row($row);
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    $pdf->Output('I', $filename);
    exit;
}

function pdfOutputMultiSectionsWrapped(array $sections, string $filenameBase)
{
    $filename   = $filenameBase . '_' . date('Y-m-d_H-i-s') . '.pdf';
    $exportedAt = date('Y-m-d H:i:s');

    $pdf = new WrapPDF('L', 'mm', 'A4');
    $pdf->AddPage();

    // Logo în colțul dreapta-sus
    $localLogo = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/logo/logo.png';
    $logoPath  = file_exists($localLogo) ? $localLogo : 'https://fibonacci-olympiad.ro/assets/images/logo/logo.png';
    $logoW = 28;
    $margin = 10;
    $pdf->Image($logoPath, $pdf->GetPageWidth() - $logoW - $margin, 10, $logoW);

    // Textul începe sub logo (SetY resetează X)
    $pdf->SetY(10 + $logoW + 6);

    // Titlu + dată
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 12, 'All Data Export', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, 'Exported at: ' . $exportedAt, 0, 1, 'L');
    $pdf->Ln(2);

    foreach ($sections as $sec) {
        $headers = $sec['headers'];
        $rows    = $sec['rows'];

        // lățimi per secțiune
        $widths = [];
        $total  = 270;
        foreach ($headers as $h) {
            if ($h === 'category_slug') $widths[] = 140;
            elseif ($h === 'name')          $widths[] = 55;
            elseif ($h === 'operator')      $widths[] = 25;
            elseif ($h === 'team_name')     $widths[] = 50;
            else                            $widths[] = max(30, floor($total / max(1, count($headers))));
        }
        $pdf->SetWidths($widths);
        $pdf->SetAligns(array_fill(0, count($headers), 'L'));

        // Secțiune
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 9, $sec['title'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Row(array_map(function ($h) {
            return strtoupper($h);
        }, $headers));

        $pdf->SetFont('Arial', '', 9);
        foreach ($rows as $r) {
            $row = [];
            foreach ($headers as $h) {
                $val = isset($r[$h]) ? (string)$r[$h] : '';
                if ($h === 'category_slug') $val = str_replace(',', ', ', $val);
                $row[] = $val;
            }
            $pdf->Row($row);
        }
        $pdf->Ln(2);
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    $pdf->Output('I', $filename);
    exit;
}




/* ---------- dataset builder (folosește getTeamById) ---------- */
function buildDataset(string $option, Admin $admin, array $coaches, array $members, array $teams, array $robots, array $allmembers): array
{
    switch ($option) {
        case 'coaches':
            $headers = ['full_name', 'email', 'phone', 'org_type', 'org_name'];
            $rows = [];
            foreach ($coaches as $c) {
                $rows[] = [
                    'full_name' => $c['full_name'] ?? '',
                    'email'     => $c['email'] ?? '',
                    'phone'     => $c['phone'] ?? '',
                    'org_type'  => $c['org_type'] ?? '',
                    'org_name'  => $c['org_name'] ?? '',
                ];
            }
            return ['title' => 'Coaches / Managers', 'headers' => $headers, 'rows' => $rows, 'file' => 'coaches'];

        case 'members':
            $headers = ['full_name', 'email', 'phone', 'team_name'];
            $rows = [];
            foreach ($members as $m) {
                $teamName = '';
                if (isset($m['team_id'])) {
                    $t = getTeamById($m['team_id']);
                    $teamName = $t['name'] ?? '';
                }
                $rows[] = [
                    'full_name' => $m['full_name'] ?? '',
                    'email'     => $m['email'] ?? '',
                    'phone'     => $m['phone'] ?? '',
                    'team_name' => $teamName,
                ];
            }
            return ['title' => 'Members', 'headers' => $headers, 'rows' => $rows, 'file' => 'members'];

        case 'teams':
            $headers = ['name', 'code', 'city', 'country'];
            $rows = [];
            foreach ($teams as $t) {
                $rows[] = [
                    'name'    => $t['name'] ?? '',
                    'code'    => $t['code'] ?? '',
                    'city'    => $t['city'] ?? '',
                    'country' => $t['country'] ?? '',
                ];
            }
            return ['title' => 'Teams', 'headers' => $headers, 'rows' => $rows, 'file' => 'teams'];

        case 'robots':
            $headers = ['name', 'category_slug', 'operator', 'team_name'];
            $rows = [];
            foreach ($robots as $r) {
                $teamName = '';
                if (isset($r['team_id'])) {
                    $t = getTeamById($r['team_id']);
                    $teamName = $t['name'] ?? '';
                }
                $rows[] = [
                    'name'          => $r['name'] ?? '',
                    'category_slug' => $r['category_slug'] ?? '',
                    'operator'      => $r['operator'] ?? '',
                    'team_name'     => $teamName,
                ];
            }
            return ['title' => 'Robots', 'headers' => $headers, 'rows' => $rows, 'file' => 'robots'];

        case 'allmembers':
            $headers = ['full_name', 'email', 'phone'];
            $rows = [];
            foreach ($allmembers as $p) {
                $rows[] = [
                    'full_name' => $p['full_name'] ?? '',
                    'email'     => $p['email'] ?? '',
                    'phone'     => $p['phone'] ?? '',
                ];
            }
            return ['title' => 'Coaches & Members', 'headers' => $headers, 'rows' => $rows, 'file' => 'allmembers'];

        case 'all':
            $sections = [];
            foreach (['coaches', 'teams', 'members', 'robots'] as $part) {
                $d = buildDataset($part, $admin, $coaches, $members, $teams, $robots, $allmembers);
                $sections[] = ['title' => $d['title'], 'headers' => $d['headers'], 'rows' => $d['rows']];
            }
            return ['title' => 'All Data', 'sections' => $sections, 'file' => 'all'];

        default:
            return [];
    }
}

/* ---------- checks ---------- */
if (!$option) {
    http_response_code(400);
    echo 'Invalid or missing option.';
    return;
}
$data = buildDataset($option, $admin, $coaches, $members, $teams, $robots, $allmembers);
if (!$data) {
    http_response_code(400);
    echo 'Invalid or missing option.';
    return;
}

/* ---------- export branch ---------- */
if ($format && in_array($format, $validFormats, true)) {
    if ($option === 'all') {
        if ($format === 'pdf')  pdfOutputMultiSectionsWrapped($data['sections'], 'all');
        elseif ($format === 'list') {
            $filename = 'all_' . date('Y-m-d_H-i-s') . '.txt';
            header('Content-Type: text/plain; charset=utf-8');
            header('Content-Disposition: inline; filename="' . $filename . '"');
            echo "All Data Export\n=================\n\n";
            foreach ($data['sections'] as $sec) {
                echo $sec['title'] . "\n" . str_repeat('-', mb_strlen($sec['title'])) . "\n";
                $i = 1;
                foreach ($sec['rows'] as $r) {
                    $parts = [];
                    foreach ($sec['headers'] as $h) {
                        $parts[] = $h . ': ' . sanitizeText($r[$h] ?? '');
                    }
                    echo ($i++) . '. ' . implode(' | ', $parts) . "\n";
                }
                echo "\n";
            }
            exit;
        } elseif ($format === 'csv') {
            $filename = 'all_' . date('Y-m-d_H-i-s') . '.csv';
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: inline; filename="' . $filename . '"');
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            foreach ($data['sections'] as $sec) {
                fputcsv($out, ['# ' . $sec['title']]);
                fputcsv($out, $sec['headers']);
                foreach ($sec['rows'] as $r) {
                    $line = [];
                    foreach ($sec['headers'] as $h) {
                        $line[] = $r[$h] ?? '';
                    }
                    fputcsv($out, $line);
                }
                fputcsv($out, []);
            }
            fclose($out);
            exit;
        }
    } else {
        // single table
        $headers = $data['headers'];
        $rows = $data['rows'];
        if ($format === 'csv')  csvOutput($headers, $rows, $data['file']);
        elseif ($format === 'list') listOutput($headers, $rows, $data['title'], $data['file']);
        elseif ($format === 'pdf')  pdfOutputSingleTableWrapped($headers, $rows, $data['title'], $data['file']);
    }
    exit;
}

/* ---------- UI (content only) ---------- */
?>
<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label">This export includes fields</label>
        <div class="form-control" style="min-height:auto">
            <?php if ($option === 'all'): ?>
                <div><strong>Coaches:</strong> full_name, email, phone, org_type, org_name</div>
                <div><strong>Teams:</strong> name, code, city, country</div>
                <div><strong>Members:</strong> full_name, email, phone, team_name</div>
                <div><strong>Robots:</strong> name, category_slug, operator, team_name</div>
            <?php else: ?>
                <div><?= implode(', ', array_map('htmlspecialchars', $data['headers'] ?? [])) ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-3">
        <label class="form-label">Export</label>
        <div class="d-flex gap-2">
            <?php
            $baseParams = $_GET;
            unset($baseParams['format']);
            $qs = function ($fmt) use ($baseParams) {
                return htmlspecialchars('?' . http_build_query($baseParams + ['format' => $fmt]));
            };
            ?>
            <a class="btn btn-primary" href="<?= $qs('list') ?>">List</a>
            <a class="btn btn-outline-primary" href="<?= $qs('csv')  ?>">CSV</a>
            <a class="btn btn-outline-primary" href="<?= $qs('pdf')  ?>" target="_blank">PDF</a>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Preview (first 5 rows)</label>
        <div class="form-control" style="overflow:auto;max-height:220px">
            <?php
            if ($option === 'all') {
                foreach ($data['sections'] as $sec) {
                    echo '<div style="margin:6px 0;"><strong>' . htmlspecialchars($sec['title']) . '</strong></div>';
                    echo '<table class="table table-sm" style="width:100%;font-size:12px">';
                    echo '<thead><tr>';
                    foreach ($sec['headers'] as $h) echo '<th>' . htmlspecialchars($h) . '</th>';
                    echo '</tr></thead><tbody>';
                    $i = 0;
                    foreach ($sec['rows'] as $r) {
                        if ($i++ >= 5) break;
                        echo '<tr>';
                        foreach ($sec['headers'] as $h) echo '<td>' . htmlspecialchars((string)($r[$h] ?? '')) . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                }
            } else {
                $headers = $data['headers'] ?? [];
                $rows    = array_slice($data['rows'] ?? [], 0, 5);
                if ($headers) {
                    echo '<table class="table table-sm" style="width:100%;font-size:12px">';
                    echo '<thead><tr>';
                    foreach ($headers as $h) echo '<th>' . htmlspecialchars($h) . '</th>';
                    echo '</tr></thead><tbody>';
                    foreach ($rows as $r) {
                        echo '<tr>';
                        foreach ($headers as $h) echo '<td>' . htmlspecialchars((string)($r[$h] ?? '')) . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<div class="text-muted">No data.</div>';
                }
            }
            ?>
        </div>
    </div>
</div>