<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/settings.php';
env::loadDatabase();
function db_conn(): mysqli
{
    $host     = getenv('DB_HOST');
    $username = getenv('DB_USER');
    $password = getenv('DB_PASSWORD');
    $database = getenv('DB_NAME');
    $port     = getenv('DB_PORT') ?: 3306;

    if (empty($host) || empty($username) || empty($password) || empty($database)) {
        displayErrorPage("Database connection settings are not properly configured.");
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $conn = new mysqli($host, $username, $password, $database, $port);
        $conn->set_charset('utf8mb4');
        return $conn;
    } catch (mysqli_sql_exception $e) {
        displayErrorPage("Database connection failed: " . $e->getMessage());
    }
}

class DatabaseConnectionException extends Exception {}

function displayErrorPage($errorMessage)
{
    die("
<!doctype html>
<title>Database Error</title>
<link href=\"https://fonts.googleapis.com/css?family=Open+Sans:300,400,700\" rel=\"stylesheet\">
<style>
  html, body { padding: 0; margin: 0; width: 100%; height: 100%; }
  * {box-sizing: border-box;}
  body { text-align: center; padding: 0; background: #d6433b; color: #fff; font-family: Open Sans; }
  h1 { font-size: 50px; font-weight: 100; text-align: center;}
  body { font-family: Open Sans; font-weight: 100; font-size: 20px; color: #fff; text-align: center; display: flex; justify-content: center; align-items: center; height: 100%; }
  article { display: block; width: 700px; padding: 50px; margin: 0 auto; }
  a { color: #fff; font-weight: bold;}
  a:hover { text-decoration: none; }
  svg { width: 75px; margin-top: 1em; }
</style>

<article>
    <svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 202.24 202.24\"><defs><style>.cls-1{fill:#fff;}</style></defs><title>Asset 3</title><g id=\"Layer_2\" data-name=\"Layer 2\"><g id=\"Capa_1\" data-name=\"Capa 1\"><path class=\"cls-1\" d=\"M101.12,0A101.12,101.12,0,1,0,202.24,101.12,101.12,101.12,0,0,0,101.12,0ZM159,148.76H43.28a11.57,11.57,0,0,1-10-17.34L91.09,31.16a11.57,11.57,0,0,1,20.06,0L169,131.43a11.57,11.57,0,0,1-10,17.34Z\"/><path class=\"cls-1\" d=\"M101.12,36.93h0L43.27,137.21H159L101.13,36.94Zm0,88.7a7.71,7.71,0,1,1,7.71-7.71A7.71,7.71,0,0,1,101.12,125.63Zm7.71-50.13a7.56,7.56,0,0,1-.11,1.3l-3.8,22.49a3.86,3.86,0,0,1-7.61,0l-3.8-22.49a8,8,0,0,1-.11-1.3,7.71,7.71,0,1,1,15.43,0Z\"/></g></g></svg>
    <h1>Database Error</h1>
    <div>
         <p>The connection to the database cannot be made. Please wait until the problem is solved.</p>
         <p>Error: $errorMessage</p>
         <p>&mdash; The <b>EssenByte Solutions</b> Team</p> 
    </div>
</article>
");
}

function load_settings(mysqli $conn): array
{
    $sql = "SELECT `key`, `value`, `type` FROM `settings`";
    $res = $conn->query($sql);

    if (!$res) {
        throw new RuntimeException("SQL error: " . $conn->error);
    }

    $settings = [];

    while ($row = $res->fetch_assoc()) {
        $value = $row['value'];

        switch ($row['type']) {
            case 'int':
                $value = (int) $value;
                break;

            case 'bool':
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                break;

            case 'json':
                $decoded = json_decode($value, true);
                $value = $decoded ?? $value;
                break;

            case 'datetime':
                $value = (new DateTime($value))->format(DateTime::ATOM);
                break;

            case 'date':
                $value = (new DateTime($value))->format('d.m.Y');
                break;
        }

        $settings[$row['key']] = $value;
    }

    $res->free();
    return $settings;
}

$conn     = db_conn();
$settings = load_settings($conn);

if ($settings['comingSoon_mode'] && !page('coming-soon') && !isLogged()) {
    header("Location: /coming-soon");
    exit;
}

// Temporarily disabled to fix redirect loop
if ($settings['maintenance_mode'] && !page('maintenance') && !isLogged()) {
    header("Location: /maintenance");
    exit;
}
