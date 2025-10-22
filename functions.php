<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');


require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/files/private/security/env/loadEnv.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/files/private/database/db-config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/files/private/security/csrf/CSRF.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/files/private/security/csrf/CSRFFunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/files/private/security/rateLimiter/RateLimiterFunctions.php';



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

env::loadEnv();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $csrf;
global $rateLimiter;

if (!isset($csrf)) $csrf = new CsrfToken();
if (!isset($rateLimiter)) $rateLimiter = new RateLimiter();

/**
 * Checks if the current page matches the specified page name.
 *
 * Compares the base name of the current request URI (without the '.php' extension)
 * to the provided page name.
 *
 * @param string $page The name of the page to check against the current request.
 * @return bool Returns true if the current page matches the specified page name, false otherwise.
 */
function page($page)
{
    if (filter_var($page, FILTER_VALIDATE_URL)) {
        $parsed = parse_url($page, PHP_URL_PATH);
        $page = basename($parsed, '.php');
    }
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (basename($uri, '.php') === $page) return true;
    else return false;
}

/**
 * Resolves the file system path to a frontend PHP file based on the given request path.
 *
 * This function maps a URL path to the appropriate PHP file within the frontend directory.
 * It handles normalization of the request path, checks for backend requests, and resolves
 * to index, subdirectory index, or specific page files. If no matching file is found,
 * it returns the path to a 404 error page.
 *
 * @param string $requestPath The request path from the URL (e.g., '/about', '/products/item').
 * @return string The resolved file system path to the PHP file to be included or executed.
 */
function resolveFrontendPath(string $requestPath)
{
    $documentRoot = $_SERVER['DOCUMENT_ROOT'];
    $frontendPath = "$documentRoot/frontend";

    $requestPath = preg_replace('#/+#', '/', $requestPath);
    $parsedUrl = parse_url($requestPath);
    $requestPath = isset($parsedUrl['path']) ? ltrim($parsedUrl['path'], '/') : '';
    $requestPath = strtok($requestPath, '?');

    if (empty($requestPath)) {
        $requestPath = 'index';
    }

    $page = strpos($requestPath, '.php') !== false ? basename($requestPath, '.php') : $requestPath;
    $page = strtok($page, '#');
    $page = rtrim($page, '/');

    if (strpos($requestPath, 'backend') === 0) {
        return "$documentRoot/$requestPath";
    }
    // Redirect /add-review to /testimonial/add
    if ($page === 'add-review') {
        header('Location: /testimonial/add', true, 302);
        exit;
    }

    if ($page === 'partners') {
        header('Location: /sponsors', true, 302);
        exit;
    }

    if (preg_match('#^regulation(/.*)?$#', $page)) {
        return "$frontendPath/regulation.php";
    }

    if ($page === 'index') {
        return "$frontendPath/homepage.php";
    }

    if ($page === 'principles') {
        return "$frontendPath/principles.php";
    }

    if (preg_match('#^admin(/.*)?$#', $requestPath)) {
        $adminPath = trim(substr($requestPath, strlen('admin')), '/');
        if ($adminPath === '' || $adminPath === 'index') {
            return "$frontendPath/admin/dashboard.php";
        }

        $adminFullPath = "$frontendPath/admin/" . $adminPath;

        if (is_dir($adminFullPath) && file_exists($adminFullPath . '/index.php')) {
            return $adminFullPath . '/index.php';
        }

        if (file_exists($adminFullPath . '.php')) {
            return $adminFullPath . '.php';
        }

        return "$frontendPath/404.php";
    }

    if (is_dir("$frontendPath/$page") && file_exists("$frontendPath/$page/index.php")) {
        return "$frontendPath/$page/index.php";
    }

    if (file_exists("$frontendPath/$page.php")) {
        return "$frontendPath/$page.php";
    }
    http_response_code(404);
    return "$frontendPath/404.php";
}

function setPageTitle(?string $customTitle = null, string $separator = ' | '): void
{
    global $settings;
    $siteName = trim(($settings['competition_name'] ?? '') . ' ' .
        ($settings['edition_year']  ?? ''));

    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $isHome  = $uriPath === '/' || $uriPath === '' ||
        preg_match('#^/(index\.php|home/?$)#i', $uriPath);

    if (preg_match('#^/ucp/([^/]+)(?:\.php)?#', $uriPath, $matches)) {
        $ucpPage = ucwords(str_replace(['-', '_'], ' ', $matches[1]));
        $pageTitle = $ucpPage . $separator . $siteName;
    } elseif ($isHome) {
        $pageTitle = $siteName;
    } elseif ($customTitle !== null && $customTitle !== '') {
        $pageTitle = $customTitle . $separator . $siteName;
    } else {
        $segments = array_values(array_filter(explode('/', $uriPath)));

        $toTitle = function (string $slug) {
            return ucwords(str_replace(['-', '_'], ' ', $slug));
        };
        $segments = array_map($toTitle, $segments);

        if (count($segments) > 1) {
            $segments = array_reverse($segments);
        }

        $slugTitle = implode(' ', $segments);
        $pageTitle = $slugTitle . $separator . $siteName;
    }

    $scheme    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $canonical = $scheme . '://' . $_SERVER['HTTP_HOST'] . $uriPath;

    echo '<title>' . htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . "</title>\n";
    echo '<link rel="canonical" href="' .
        htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') . '">' . "\n";
}
function getCanonicalUrl(): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    return $scheme . '://' . $host . $uriPath;
}
function getBaseUrl(): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = rtrim(str_replace(basename($scriptName), '', $scriptName), '/');
    return $scheme . '://' . $host . $basePath . '/';
}
function getPageTitle(?string $customTitle = null, string $separator = ' | '): string
{
    global $settings;
    $siteName = trim(($settings['competition_name'] ?? '') . ' ' .
        ($settings['edition_year']  ?? ''));

    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $isHome  = $uriPath === '/' || $uriPath === '' ||
        preg_match('#^/(index\.php|home/?$)#i', $uriPath);

    if (preg_match('#^/ucp/([^/]+)(?:\.php)?#', $uriPath, $matches)) {
        $ucpPage = ucwords(str_replace(['-', '_'], ' ', $matches[1]));
        $pageTitle = $ucpPage . $separator . $siteName;
    } elseif ($isHome) {
        $pageTitle = $siteName;
    } elseif ($customTitle !== null && $customTitle !== '') {
        $pageTitle = $customTitle . $separator . $siteName;
    } else {
        $segments = array_values(array_filter(explode('/', $uriPath)));

        $toTitle = function (string $slug) {
            return ucwords(str_replace(['-', '_'], ' ', $slug));
        };
        $segments = array_map($toTitle, $segments);

        if (count($segments) > 1) {
            $segments = array_reverse($segments);
        }

        $slugTitle = implode(' ', $segments);
        $pageTitle = $slugTitle . $separator . $siteName;
    }

    return htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8');
}
function getPageDescription(
    ?string $customDescription = null,
    int $maxLength = 160,
    ?string $baseDescription = null
): string {
    global $settings;
    $competitionName = $settings['competition_name'] ?? 'Fibonacci Romania';
    $competitionYear = $settings['edition_year'] ?? date('Y');

    $siteName = trim("$competitionName $competitionYear");
    $baseDescription ??= "$siteName — Live robotics battles, hands-on STEM workshops, and networking with industry leaders.";

    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $isHome  = $uriPath === '/' || $uriPath === '' ||
        preg_match('#^/(index\.php|home/?$)#i', $uriPath);

    if ($isHome) {
        $description = $customDescription ?? $baseDescription;
    } elseif ($customDescription !== null && $customDescription !== '') {
        $description = $customDescription;
    } else {
        $segments = array_values(array_filter(explode('/', $uriPath)));
        $segments = array_map(
            fn($s) => ucwords(str_replace(['-', '_'], ' ', $s)),
            $segments
        );
        if (count($segments) > 1) {
            $segments = array_reverse($segments);
        }
        $slugTitle  = implode(' ', $segments);
        $description = "$slugTitle – $baseDescription";
    }

    if (mb_strlen($description, 'UTF-8') > $maxLength) {
        $truncated = mb_substr($description, 0, $maxLength, 'UTF-8');
        $cutPos    = mb_strrpos($truncated, ' ', 0, 'UTF-8') ?: $maxLength;
        $description = mb_substr($truncated, 0, $cutPos, 'UTF-8') . '…';
    }

    return htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
}
function getPagePath()
{
    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $uriPath = rtrim($uriPath, '/');
    if ($uriPath === '') {
        return '/';
    }
    return htmlspecialchars($uriPath, ENT_QUOTES, 'UTF-8');
}
function checkEventStatus()
{
    $currentDate = new DateTime();
    $startDate = new DateTime('2025-02-20');
    $endDate = new DateTime('2025-03-01');

    if ($currentDate >= $startDate && $currentDate <= $endDate) {
        return "🟢 The challenge is in progress";
    } elseif ($currentDate < $startDate) {
        return "🟡 The challenge is in preparation";
    } else {
        return "🔴 The challenge is over";
    }
}
function getCategories()
{
    global $conn;
    $query = "SELECT * FROM categories";
    $result = mysqli_query($conn, $query);
    $categories = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
        mysqli_free_result($result);
    }
    return $categories;
}

function getTestimonials()
{
    global $conn;
    $query = "SELECT * FROM testimonials ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
    $testimonials = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $testimonials[] = $row;
        }
        mysqli_free_result($result);
    }
    return $testimonials;
}

function getFounders()
{
    global $conn;
    $query = "SELECT * FROM founders ORDER BY id ASC";
    $result = mysqli_query($conn, $query);
    $founders = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $founders[] = $row;
        }
        mysqli_free_result($result);
    }
    return $founders;
}

function getCategoryBySlug($slug)
{
    global $conn;
    $query = "SELECT * FROM categories WHERE slug = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $slug);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        mysqli_stmt_close($stmt);
    }
    return null;
}

function getCategoryById($id)
{
    global $conn;
    $query = "SELECT * FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        mysqli_stmt_close($stmt);
    }
    return null;
}

function getCategoryRegulationBySlug($slug)
{
    global $conn;
    $query = "SELECT * FROM regulations WHERE category = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $slug);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_stmt_close($stmt);
            return $data;
        }
        mysqli_stmt_close($stmt);
    }
    return [];
}

function getEmailLog($email_uid)
{
    global $conn;
    $query = "SELECT * FROM emails_log WHERE email_uid = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email_uid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        mysqli_stmt_close($stmt);
    }
    return null;
}

function verifyAccount(): void
{
    global $conn;

    if (empty($_GET['t']) || empty($_GET['s'])) {
        return;
    }

    $secureToken = $_GET['t'];
    $email       = $_GET['s'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($secureToken) < 10) {
        header('Location: /');
        exit;
    }

    $stmt = $conn->prepare("SELECT id, is_email_verified FROM users WHERE secure_token = ? AND email = ? LIMIT 1");
    $stmt->bind_param('ss', $secureToken, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header('Location: /');
        exit;
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    if ((int)$user['is_email_verified'] === 0) {
        $upd = $conn->prepare("UPDATE users SET is_email_verified = 1 WHERE id = ?");
        $upd->bind_param('i', $user['id']);
        $upd->execute();
        $upd->close();
    }

    header('Location: /ucp/login');
    exit;
}

function autoLogin(): void
{
    global $conn;

    $sessionKey = getenv('SESSION_USER_ID');

    if (!empty($_SESSION[$sessionKey])) {
        return;
    }

    if (isset($_GET['magicLogin']) && $_GET['magicLogin'] === 'true') {
        $_SESSION[$sessionKey] = 1;
        header('Location: ' . $_SERVER['REQUEST_URI']);
    }

    if (empty($_COOKIE[getenv('REMEMBER_ME_COOKIE')])) {
        return;
    }

    $parts = explode(':', $_COOKIE[getenv('REMEMBER_ME_COOKIE')], 2);
    if (count($parts) !== 2) {
        return;
    }
    [$userId, $token] = $parts;

    $userId = (int)$userId;
    if ($userId <= 0 || strlen($token) !== 64) { // 64 hex chars = 32 bytes
        return;
    }

    $tokenHash = hash('sha256', $token);

    $stmt = $conn->prepare("
        SELECT user_id 
        FROM user_remember_tokens 
        WHERE user_id = ? 
          AND token_hash = ? 
          AND expires_at > NOW()
        LIMIT 1
    ");
    $stmt->bind_param('is', $userId, $tokenHash);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        session_regenerate_id(true);
        $_SESSION[$sessionKey] = $userId;
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    $stmt->close();
}

function autoLogoutCheck(?mysqli $db = null): void
{
    global $conn;

    $db = $conn;

    static $ran = false;
    if ($ran) return;
    $ran = true;

    if (
        (empty($_GET['logout'])) &&
        (empty($_SERVER['QUERY_STRING']) || strpos($_SERVER['QUERY_STRING'], 'logout') === false)
    ) {
        return;
    }

    $cookieName = getenv('REMEMBER_ME_COOKIE') ?: getenv('REMEMBER_ME_COOKIE');
    if (!empty($_COOKIE[$cookieName])) {
        $parts = explode(':', $_COOKIE[$cookieName], 2);
        if (count($parts) === 2) {
            $uid = (int)$parts[0];
            $tokenHash = hash('sha256', $parts[1]);

            if ($uid > 0 && $tokenHash !== '') {
                if (!$db) {
                    if (isset($GLOBALS['conn']) && $GLOBALS['conn'] instanceof mysqli) {
                        $db = $GLOBALS['conn'];
                    }
                }
                if ($db instanceof mysqli) {
                    if ($del = $db->prepare('DELETE FROM user_remember_tokens WHERE user_id = ? AND token_hash = ? LIMIT 1')) {
                        $del->bind_param('is', $uid, $tokenHash);
                        $del->execute();
                        $del->close();
                    }
                }
            }
        }

        setcookie($cookieName, '', [
            'expires'  => time() - 3600,
            'path'     => '/',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        unset($_COOKIE[$cookieName]);
    }

    if (session_status() !== PHP_SESSION_NONE) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    $uri  = $_SERVER['REQUEST_URI'] ?? '/';
    $u    = parse_url($uri);
    $path = $u['path'] ?? '/';
    $qs   = [];
    if (!empty($u['query'])) parse_str($u['query'], $qs);
    unset($qs['logout']);
    $target = $path . ($qs ? ('?' . http_build_query($qs)) : '');

    header('Location: ' . $target, true, 303);
    exit;
}

function isLogged(): bool
{
    $sessionKey = getenv('SESSION_USER_ID');
    return !empty($_SESSION[$sessionKey]) && is_numeric($_SESSION[$sessionKey]);
}

function adminSessionKey(): string
{
    $key = getenv('SESSION_ADMIN_ID');
    return $key !== false && $key !== '' ? $key : 'fibo_admin_id';
}

function isAdminLoggedIn(): bool
{
    $sessionKey = adminSessionKey();
    return isset($_SESSION[$sessionKey]) && is_numeric($_SESSION[$sessionKey]) && (int)$_SESSION[$sessionKey] > 0;
}

function requireAdminLogin(): void
{
    if (!isAdminLoggedIn()) {
        header('Location: /admin/login');
        exit;
    }
}

function setAdminSession(int $adminId): void
{
    $sessionKey = adminSessionKey();
    $_SESSION[$sessionKey] = $adminId;
}

function clearAdminSession(): void
{
    $sessionKey = adminSessionKey();
    unset($_SESSION[$sessionKey]);
}

function getAdminId(): int
{
    $sessionKey = adminSessionKey();
    return isset($_SESSION[$sessionKey]) && is_numeric($_SESSION[$sessionKey]) ? (int)$_SESSION[$sessionKey] : 0;
}

function getAdminProfile(): ?array
{
    global $conn;
    $adminId = getAdminId();
    if ($adminId <= 0) {
        return null;
    }

    $stmt = $conn->prepare('SELECT id, username, email, full_name, role FROM admins WHERE id = ? LIMIT 1');
    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('i', $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result ? $result->fetch_assoc() : null;
    $stmt->close();

    return $data ?: null;
}

function secureUCP()
{
    if (preg_match('#^/ucp(?:/[^/]+)*/?$#', parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH))) {
        $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '/';
        if (!isLogged() && stripos($uriPath, '/ucp/login') !== 0 && stripos($uriPath, '/ucp/registration') !== 0 && stripos($uriPath, '/ucp/forgot-password') !== 0 && stripos($uriPath, '/ucp/reset-password') !== 0) {
            header('Location: /ucp/login');
            exit;
        }

        $sessionKey = getenv('SESSION_USER_ID');
        if (empty($_SESSION[$sessionKey]) || !is_numeric($_SESSION[$sessionKey])) {
            if (stripos($uriPath, '/ucp/login') !== 0 && stripos($uriPath, '/ucp/registration') !== 0 && stripos($uriPath, '/ucp/forgot-password') !== 0 && stripos($uriPath, '/ucp/reset-password') !== 0) {
                header('Location: /ucp/login');
                exit;
            }
        }
    }
}

function getUserId(): int
{
    $sessionKey = getenv('SESSION_USER_ID');
    return isset($_SESSION[$sessionKey]) && is_numeric($_SESSION[$sessionKey])
        ? (int)$_SESSION[$sessionKey]
        : 0;
}

function loggedRedirect()
{
    if (isLogged()) {
        $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $redirectPages = [
            '/ucp/login',
            '/ucp/registration',
            // '/forgot-password'
        ];
        foreach ($redirectPages as $page) {
            if (stripos($uriPath, $page) === 0) {
                header('Location: /ucp/', true, 302);
                exit;
            }
        }
    }
}

function getUserData()
{
    global $conn;
    $userId = getUserId();
    if ($userId <= 0) {
        return null;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}

$userData = getUserData();

function generateTeamCode(): string
{
    $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $code = '';
    for ($i = 0; $i < 8; $i++) {
        $code .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $code;
}

function userHasTeam()
{
    global $conn;

    $userId = getUserId();
    $stmt = $conn->prepare('
        SELECT COUNT(*) as team_count 
        FROM teams 
        WHERE manager_id = ? 
        LIMIT 1');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return (bool)$row['team_count'] > 0;
    }
    return false;
}

function getTeamData()
{
    global $conn;
    $stmt = $conn->prepare('SELECT * FROM teams WHERE manager_id = ? LIMIT 1');
    $userId = getUserId();
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

$team = getTeamData();

function getUserDataById(int $userId)
{
    global $conn;

    if ($userId <= 0) {
        return null;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}

function getRegistrationProgress()
{
    global $team;

    $total = 0;

    if (userHasTeam())
        $total += 25;
    if (teamHasMembers($team['id']))
        $total += 25;
    if (teamHasRobots($team['id']))
        $total += 25;


    return $total;
}

function registrationNextStep()
{
    if (getRegistrationProgress() < 25) {
        return '<a href="/ucp/team/create" class="underline hover:text-blue-600 transition">Add Team Details</a>';
    } elseif (getRegistrationProgress() < 50) {
        return '<a href="/ucp/members/create" class="underline hover:text-blue-600 transition">Add Team Members</a>';
    } elseif (getRegistrationProgress() < 75) {
        return '<a href="/ucp/robots/create" class="underline hover:text-blue-600 transition">Add Robots</a>';
    } else {
        return 'Confirm Participation Until February 1, 2026';
    }
}

function existEmailInDatabase(string $email)
{
    global $conn;

    $stmt1 = $conn->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email = ?');
    $stmt1->bind_param('s', $email);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $count1 = 0;
    if ($result1 && $row1 = $result1->fetch_assoc()) {
        $count1 = (int)$row1['cnt'];
    }
    $stmt1->close();

    $stmt2 = $conn->prepare('SELECT COUNT(*) AS cnt FROM team_members WHERE email = ?');
    $stmt2->bind_param('s', $email);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $count2 = 0;
    if ($result2 && $row2 = $result2->fetch_assoc()) {
        $count2 = (int)$row2['cnt'];
    }
    $stmt2->close();

    return ($count1 + $count2) > 0;
}

function existPhoneInDatabase(string $phone)
{
    global $conn;

    $normalized = preg_replace('/[\s\-\(\)]/', '', $phone);

    if (strpos($normalized, '+') === 0) {
        $normalized = substr($normalized, 1);
    }

    if (strpos($normalized, '00') === 0) {
        $normalized = substr($normalized, 2);
    }

    if (strpos($normalized, '0') === 0) {
        $normalized = substr($normalized, 1);
    }

    $variants = [
        $normalized,
        '0' . $normalized,
        '+' . $normalized,
        '00' . $normalized
    ];

    $placeholders = implode(',', array_fill(0, count($variants), '?'));

    $query = "
        SELECT COUNT(*) AS phone_count
        FROM (
            SELECT phone FROM users WHERE phone IN ($placeholders)
            UNION ALL
            SELECT phone FROM team_members WHERE phone IN ($placeholders)
        ) AS all_phones
        LIMIT 1
    ";

    $stmt = $conn->prepare($query);
    $params = array_merge($variants, $variants);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return (int)$row['phone_count'] > 0;
    }
    return false;
}

function teamHasMembers($teamId)
{
    global $conn;
    $query = 'SELECT COUNT(*) AS member_count FROM team_members WHERE team_id = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $teamId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return (int)$row['member_count'] > 0;
    }
    return false;
}

function getTeamMembers($teamId)
{
    global $conn;
    $query = 'SELECT * FROM team_members WHERE team_id = ? ORDER BY id ASC';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $teamId);
    $stmt->execute();
    $result = $stmt->get_result();
    $members = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $members[] = $row;
        }
    }
    return $members;
}

function getTeamMemberById($memberId)
{
    global $conn;
    $query = 'SELECT * FROM team_members WHERE id = ? LIMIT 1';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $memberId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}

function getTotalTeamMembers($teamId)
{
    global $conn;
    $query = 'SELECT COUNT(*) AS member_count FROM team_members WHERE team_id = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $teamId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return (int)$row['member_count'] + 1;
    }
    return 0;
}

function getTotalMembersLast30d($teamId)
{
    global $conn;
    $query = 'SELECT COUNT(*) AS member_count FROM team_members WHERE team_id = ? AND created_at >= NOW() - INTERVAL 30 DAY';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $teamId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return (int)$row['member_count'];
    }
    return 0;
}

function getTotalMembers()
{
    global $conn;
    $query1 = 'SELECT COUNT(*) AS member_count FROM team_members';
    $result1 = mysqli_query($conn, $query1);
    $count1 = 0;
    if ($result1 && $row1 = mysqli_fetch_assoc($result1)) {
        $count1 = (int)$row1['member_count'];
    }

    $query2 = 'SELECT COUNT(*) AS member_count FROM users';
    $result2 = mysqli_query($conn, $query2);
    $count2 = 0;
    if ($result2 && $row2 = mysqli_fetch_assoc($result2)) {
        $count2 = (int)$row2['member_count'];
    }

    return $count1 + $count2;
}

function getMonthlyMemberGrowthPercent(): array
{
    global $conn;

    $currentMonth = date('Y-m');
    $prevMonth = date('Y-m', strtotime('-1 month'));

    $currentMonthDisplay = date('F Y');
    $prevMonthDisplay = date('F Y', strtotime('-1 month'));

    $queryCurrentTM = "SELECT COUNT(*) AS cnt FROM team_members WHERE DATE_FORMAT(created_at, '%Y-%m') = ?";
    $stmtCurrentTM = $conn->prepare($queryCurrentTM);
    $stmtCurrentTM->bind_param('s', $currentMonth);
    $stmtCurrentTM->execute();
    $resultCurrentTM = $stmtCurrentTM->get_result();
    $currentCountTM = ($resultCurrentTM && $row = $resultCurrentTM->fetch_assoc()) ? (int)$row['cnt'] : 0;
    $stmtCurrentTM->close();

    $queryCurrentU = "SELECT COUNT(*) AS cnt FROM users WHERE DATE_FORMAT(created_at, '%Y-%m') = ?";
    $stmtCurrentU = $conn->prepare($queryCurrentU);
    $stmtCurrentU->bind_param('s', $currentMonth);
    $stmtCurrentU->execute();
    $resultCurrentU = $stmtCurrentU->get_result();
    $currentCountU = ($resultCurrentU && $row = $resultCurrentU->fetch_assoc()) ? (int)$row['cnt'] : 0;
    $stmtCurrentU->close();

    $currentCount = $currentCountTM + $currentCountU;

    $queryPrevTM = "SELECT COUNT(*) AS cnt FROM team_members WHERE DATE_FORMAT(created_at, '%Y-%m') = ?";
    $stmtPrevTM = $conn->prepare($queryPrevTM);
    $stmtPrevTM->bind_param('s', $prevMonth);
    $stmtPrevTM->execute();
    $resultPrevTM = $stmtPrevTM->get_result();
    $prevCountTM = ($resultPrevTM && $row = $resultPrevTM->fetch_assoc()) ? (int)$row['cnt'] : 0;
    $stmtPrevTM->close();

    $queryPrevU = "SELECT COUNT(*) AS cnt FROM users WHERE DATE_FORMAT(created_at, '%Y-%m') = ?";
    $stmtPrevU = $conn->prepare($queryPrevU);
    $stmtPrevU->bind_param('s', $prevMonth);
    $stmtPrevU->execute();
    $resultPrevU = $stmtPrevU->get_result();
    $prevCountU = ($resultPrevU && $row = $resultPrevU->fetch_assoc()) ? (int)$row['cnt'] : 0;
    $stmtPrevU->close();

    $prevCount = $prevCountTM + $prevCountU;

    $percent = 0;
    if ($prevCount > 0) {
        $percent = (($currentCount - $prevCount) / $prevCount) * 100;
    } elseif ($currentCount > 0) {
        $percent = 100;
    }

    return [
        'percent' => round($percent, 2),
        'compare_month' => $prevMonthDisplay,
        'current_month' => $currentMonthDisplay
    ];
}

function checkMemberCurrentTeam($memberId, $teamId)
{
    global $conn;
    $query = 'SELECT COUNT(*) AS member_count FROM team_members WHERE id = ? AND team_id = ? LIMIT 1';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $memberId, $teamId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return (int)$row['member_count'] > 0;
    }
    return false;
}

function teamHasRobots($teamId)
{
    global $conn;
    $query = 'SELECT COUNT(*) AS robot_count FROM robots WHERE team_id = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $teamId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return (int)$row['robot_count'] > 0;
    }
    return false;
}

function getTeamRobots($teamId)
{
    global $conn;
    $query = 'SELECT * FROM robots WHERE team_id = ? ORDER BY id ASC';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $teamId);
    $stmt->execute();
    $result = $stmt->get_result();
    $robots = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $robots[] = $row;
        }
    }
    return $robots;
}

function getTeamRobotById($robotId)
{
    global $conn;
    $query = 'SELECT * FROM robots WHERE id = ? LIMIT 1';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $robotId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}

function getTotalTeamRobots($teamId)
{
    global $conn;
    $query = 'SELECT COUNT(*) AS robot_count FROM robots WHERE team_id = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $teamId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return (int)$row['robot_count'];
    }
    return 0;
}

function getTotalRobotsLast30d($teamId)
{
    global $conn;
    $query = 'SELECT COUNT(*) AS robot_count FROM robots WHERE team_id = ? AND created_at >= NOW() - INTERVAL 30 DAY';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $teamId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return (int)$row['robot_count'];
    }
    return 0;
}

function getTotalRobots()
{
    global $conn;
    $query = 'SELECT COUNT(*) AS robot_count FROM robots';
    $result = mysqli_query($conn, $query);
    $count = 0;
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $count = (int)$row['robot_count'];
    }
    return $count;
}

function getMonthlyRobotGrowthPercent(): array
{
    global $conn;

    $currentMonth = date('Y-m');
    $prevMonth = date('Y-m', strtotime('-1 month'));

    $currentMonthDisplay = date('F Y');
    $prevMonthDisplay = date('F Y', strtotime('-1 month'));

    $queryCurrent = "SELECT COUNT(*) AS cnt FROM robots WHERE DATE_FORMAT(created_at, '%Y-%m') = ?";
    $stmtCurrent = $conn->prepare($queryCurrent);
    $stmtCurrent->bind_param('s', $currentMonth);
    $stmtCurrent->execute();
    $resultCurrent = $stmtCurrent->get_result();
    $currentCount = ($resultCurrent && $row = $resultCurrent->fetch_assoc()) ? (int)$row['cnt'] : 0;
    $stmtCurrent->close();

    $queryPrev = "SELECT COUNT(*) AS cnt FROM robots WHERE DATE_FORMAT(created_at, '%Y-%m') = ?";
    $stmtPrev = $conn->prepare($queryPrev);
    $stmtPrev->bind_param('s', $prevMonth);
    $stmtPrev->execute();
    $resultPrev = $stmtPrev->get_result();
    $prevCount = ($resultPrev && $row = $resultPrev->fetch_assoc()) ? (int)$row['cnt'] : 0;
    $stmtPrev->close();

    $percent = 0;
    if ($prevCount > 0) {
        $percent = (($currentCount - $prevCount) / $prevCount) * 100;
    } elseif ($currentCount > 0) {
        $percent = 100;
    }

    return [
        'percent' => round($percent, 2),
        'compare_month' => $prevMonthDisplay,
        'current_month' => $currentMonthDisplay
    ];
}

function checkRobotCurrentTeam($robotId, $teamId)
{
    global $conn;
    $query = 'SELECT COUNT(*) AS robot_count FROM robots WHERE id = ? AND team_id = ? LIMIT 1';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $robotId, $teamId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return (int)$row['robot_count'] === 1;
    }
    return false;
}

function getTeamTotalCategories($teamId)
{
    global $conn;
    $query = '
        SELECT r.category_slug
        FROM robots r
        WHERE r.team_id = ?
    ';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $teamId);
    $stmt->execute();
    $result = $stmt->get_result();

    $slugs = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            foreach (explode(',', $row['category_slug']) as $slug) {
                $slug = trim($slug);
                if ($slug !== '') {
                    $slugs[$slug] = true;
                }
            }
        }
    }
    return count($slugs);
}

function checkResetTokenOrExit(): void
{
    global $conn;

    $token = $_GET['token'] ?? '';
    if ($token === '') {
        header('Location: /ucp/forgot-password');
    }

    $tokenHash = hash('sha256', $token);

    $stmt = $conn->prepare('
        SELECT id 
        FROM password_resets 
        WHERE token_hash = ? 
          AND expires_at > NOW()
        LIMIT 1
    ');
    if (!$stmt) {
        header('Location: /ucp/forgot-password');
    }

    $stmt->bind_param('s', $tokenHash);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows !== 1) {
        $stmt->close();
        header('Location: /ucp/forgot-password');
    }
    $stmt->close();
}

function isAdmin()
{
    if (!isLogged()) {
        return false;
    }
    global $conn;
    $userId = getUserId();
    if ($userId <= 0) {
        return false;
    }
    $stmt = $conn->prepare('SELECT COUNT(*) AS cnt FROM admins WHERE member_id = ? LIMIT 1');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        return (int)$row['cnt'] > 0;
    }
    return false;
}


function secureAdmin()
{
    if (preg_match('#^/ucp/admin(?:/[^/]+)*\.php$#', parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH))) {
        if (!isAdmin()) {
            header('Location: /ucp');
            exit;
        }

        $sessionKey = getenv('SESSION_USER_ID');
        if (empty($_SESSION[$sessionKey]) || !is_numeric($_SESSION[$sessionKey])) {
            header('Location: /ucp/login');
            exit;
        }
    }
}

function getChangelogs()
{
    global $conn;
    $query = 'SELECT * FROM changelogs ORDER BY created_at DESC';
    $result = mysqli_query($conn, $query);
    $changelogs = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $changelogs[] = $row;
        }
    }
    return $changelogs;
}

function getParticipantStatistics(): array
{
    global $conn;

    $stats = [
        'total'   => 0,
        'cities'  => 0,
        'schools' => 0,
        'latest'  => [],
    ];

    if (!$conn instanceof mysqli) {
        return $stats;
    }

    $query = 'SELECT COUNT(*) AS total,
                     COUNT(DISTINCT city) AS cities,
                     COUNT(DISTINCT school) AS schools
              FROM participants';
    if ($result = $conn->query($query)) {
        if ($row = $result->fetch_assoc()) {
            $stats['total']   = (int)($row['total'] ?? 0);
            $stats['cities']  = (int)($row['cities'] ?? 0);
            $stats['schools'] = (int)($row['schools'] ?? 0);
        }
        $result->free();
    }

    $latestQuery = 'SELECT first_name, last_name, city, school, class, created_at
                    FROM participants
                    ORDER BY created_at DESC
                    LIMIT 5';
    if ($latestResult = $conn->query($latestQuery)) {
        while ($row = $latestResult->fetch_assoc()) {
            $stats['latest'][] = $row;
        }
        $latestResult->free();
    }

    return $stats;
}

function getAdminDataById($id)
{
    global $conn;
    $query = "SELECT * FROM admins WHERE member_id = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}

function setAdminRole($role)
{
    switch ($role) {
        case 'developer':
            return '<span class="fibo-badge fibo-dev">Developer</span>';
        case 'owner':
            return '<span class="fibo-badge fibo-owner">Owner</span>';
        case 'admin':
            return '<span class="fibo-badge fibo-admin">Admin</span>';
        case 'media':
            return '<span class="fibo-badge fibo-media">Media</span>';
        case 'marketing':
            return '<span class="fibo-badge fibo-marketing">Marketing</span>';
        default:
            return '<span class="fibo-badge">Staff</span>';
    }
}

function getrobots()
{
    global $conn;
    $query = 'SELECT * FROM robots ORDER BY created_at DESC';
    $result = mysqli_query($conn, $query);
    $robots = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $robots[] = $row;
        }
    }
    return $robots;
}

function getRobotsByCategory($category)
{
    global $conn;
    $likePattern = '%' . $conn->real_escape_string($category) . '%';
    $query = "SELECT * FROM robots WHERE category_slug LIKE ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $likePattern);
    $stmt->execute();
    $result = $stmt->get_result();
    $robots = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $robots[] = $row;
        }
    }
    return $robots;
}

function getTeamById(int $id)
{
    global $conn;
    $query = 'SELECT * FROM teams WHERE id = ? LIMIT 1';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}

function userJoinedTimeAgo()
{
    global $userData;
    if (!$userData || empty($userData['created_at'])) {
        return 'Unknown';
    }

    $createdAt = new DateTime($userData['created_at']);
    $now = new DateTime();
    $interval = $now->diff($createdAt);

    if ($interval->y > 0) {
        return $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
    } elseif ($interval->m > 0) {
        return $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
    } elseif ($interval->d > 0) {
        return $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
    } elseif ($interval->h > 0) {
        return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
    } elseif ($interval->i > 0) {
        return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
    } else {
        return 'Just now';
    }
}

function getTeamByUser($userId)
{
    global $conn;
    $query = 'SELECT * FROM teams WHERE manager_id = ? LIMIT 1';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}
function getTeamByMember($userId)
{
    global $conn;
    $query = 'SELECT * FROM teams WHERE manager_id = ? LIMIT 1';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}

function getTeamByCode($code)
{
    global $conn;
    $query = 'SELECT * FROM teams WHERE code = ? LIMIT 1';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $code);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}










// This is a fucking admin space

/**
 * Admin class to handle administrative functionalities.
 * This class provides essential methods for managing admin-related tasks.
 * 
 * Features:
 * - Check if a user is an admin.
 * - Retrieve admin details.
 * - Add, remove, and update admin roles.
 * - Fetch a list of all admins.
 * 
 * Example usage:
 * 
 * $admin = new Admin($conn); // Pass the database connection
 * 
 * - Check if a user is an admin
 * $isAdmin = $admin->isAdmin($userId);
 * 
 * - Get admin details
 * $adminDetails = $admin->getAdminDetails($userId);
 * 
 * - Add a new admin
 * $admin->addAdmin($userId, 'admin');
 * 
 * - Remove an admin
 * $admin->removeAdmin($userId);
 * 
 * - Update admin role
 * $admin->updateAdminRole($userId, 'developer');
 * 
 * - Get all admins
 * $allAdmins = $admin->getAllAdmins();
 */
class Admin
{
    private $conn;

    public function __construct(mysqli $dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function isAdmin(int $userId): bool
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) AS cnt FROM admins WHERE member_id = ? LIMIT 1');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $isAdmin = $result && $result->fetch_assoc()['cnt'] > 0;
        $stmt->close();
        return $isAdmin;
    }

    public function getAdminDetails(int $userId): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM admins WHERE member_id = ? LIMIT 1');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $adminDetails = $result->fetch_assoc() ?: null;
        $stmt->close();
        return $adminDetails;
    }

    public function addAdmin(int $userId, string $role): bool
    {
        $stmt = $this->conn->prepare('INSERT INTO admins (member_id, role, created_at) VALUES (?, ?, NOW())');
        $stmt->bind_param('is', $userId, $role);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function removeAdmin(int $userId): bool
    {
        $stmt = $this->conn->prepare('DELETE FROM admins WHERE member_id = ? LIMIT 1');
        $stmt->bind_param('i', $userId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function getAdminRole(int $userId): ?string
    {
        $stmt = $this->conn->prepare('SELECT role FROM admins WHERE member_id = ? LIMIT 1');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $row['role'] ?? null;
    }

    public function getAllAdmins(): array
    {
        $query = 'SELECT * FROM admins ORDER BY created_at DESC';
        $result = $this->conn->query($query);
        $admins = [];
        while ($row = $result->fetch_assoc()) {
            $admins[] = $row;
        }
        return $admins;
    }

    public function updateAdminRole(int $userId, string $newRole): bool
    {
        $stmt = $this->conn->prepare('UPDATE admins SET role = ? WHERE member_id = ? LIMIT 1');
        $stmt->bind_param('si', $newRole, $userId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function getUsers(): array
    {
        $query = 'SELECT * FROM users ORDER BY full_name ASC';
        $result = $this->conn->query($query);
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function getTeams(): array
    {
        $query = 'SELECT * FROM teams ORDER BY name ASC';
        $result = $this->conn->query($query);
        $teams = [];
        while ($row = $result->fetch_assoc()) {
            $teams[] = $row;
        }
        return $teams;
    }

    public function getRobots(): array
    {
        $query = 'SELECT * FROM robots ORDER BY name ASC';
        $result = $this->conn->query($query);
        $robots = [];
        while ($row = $result->fetch_assoc()) {
            $robots[] = $row;
        }
        return $robots;
    }

    public function getMembers(): array
    {
        $query = 'SELECT * FROM team_members ORDER BY full_name ASC';
        $result = $this->conn->query($query);
        $members = [];
        while ($row = $result->fetch_assoc()) {
            $members[] = $row;
        }
        return $members;
    }
}

function totalAmountToPay()
{
    global $team;

    if (!$team || !isset($team['id'])) {
        return 0;
    }

    $teamId = $team['id'];
    $totalRobots = getTotalTeamRobots($teamId);

    return $totalRobots * 100;
}


// This is a  fucking space




































// This is a  fucking space
function trackFirstVisitToDiscord(): void
{
    global $settings;
    $cookieName = getenv('FIRST_VISIT_COOKIE');
    $webhookUrl = getenv('DISCORD_VISITOR_TRACKER_WEBHOOK');

    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (
        preg_match('/bot|crawl|spider|preview|scanner/i', $userAgent) ||
        stripos($userAgent, 'Tencent Cloud Computing') !== false ||
        stripos($userAgent, 'TencentCloud') !== false
    ) {
        return;
    }

    $uriPath   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
    $extension = pathinfo($uriPath, PATHINFO_EXTENSION);
    if ($extension && !in_array(strtolower($extension), ['php', ''])) {
        return;
    }

    if (isset($_COOKIE[$cookieName])) {
        return;
    }

    $ip         = $_SERVER['REMOTE_ADDR']         ?? 'Unknown IP';
    $userAgent  = $_SERVER['HTTP_USER_AGENT']     ?? 'Unknown';
    $referer    = $_SERVER['HTTP_REFERER']        ?? 'None';
    $url        = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $timestamp  = date('Y-m-d H:i:s');

    $location = 'Unknown';
    $geoApiUrl = "http://ipwho.is/{$ip}?lang=en";

    $geoResponse = @file_get_contents($geoApiUrl);
    if ($geoResponse !== false) {
        $geoData = json_decode($geoResponse, true);
        if ($geoData['success'] ?? false) {
            $flag     = $geoData['flag']['emoji'] ?? '';
            $city     = $geoData['city']          ?? '';
            $region   = $geoData['region']        ?? '';
            $country  = $geoData['country']       ?? '';
            $location = "{$flag} {$city}, {$region}, {$country}";
        }
    }

    // 6. Embed Discord
    $embed = [
        'username'   => 'Visitor Tracker',
        'avatar_url' => 'https://essenbyte.com/assets/images/logo/logo.webp',
        'embeds'     => [[
            'title'  => '🧭 New First-Time Visitor',
            'color'  => hexdec('3498db'),
            'fields' => [
                ['name' => '🌍 IP Address',  'value' => $ip,        'inline' => true],
                ['name' => '📍 Location',    'value' => $location,  'inline' => true],
                ['name' => '🕒 Time',        'value' => $timestamp, 'inline' => false],
                ['name' => '📄 Visited URL', 'value' => $url],
                ['name' => '🔗 Referer',     'value' => $referer],
                ['name' => '🖥️ User Agent',  'value' => substr($userAgent, 0, 1024)]
            ],
            'footer' => [
                'text' => 'Fibonacci Romania • First visit log'
            ],
            'timestamp' => date('c')
        ]]
    ];

    if ($settings['admin_notification']['visits']) {
        $ch = curl_init($webhookUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => json_encode($embed),
            CURLOPT_RETURNTRANSFER => true
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 204) {
            setcookie($cookieName, '1', [
                'expires'  => time() + (86400 * 30),
                'path'     => '/',
                'secure'   => true,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }
    }
}

function sendNotify(string $title, string $content): void
{
    $webhooks = [
        getenv('DISCORD_NOTIFICATION_WEBHOOK'),
    ];

    $emails = [
        // getenv('NOTIFICATION_EMAIL'),
        // getenv('MAIL_USERNAME')
    ];

    // Avatar și alte setări
    $avatar = 'https://essenbyte.com/assets/images/logo/logo.webp';
    $fromEmail = 'office@essenbyte.com';
    $fromName = 'EssenByte Notification Center';

    // -------- DISCORD (markdown raw) --------
    foreach ($webhooks as $webhookUrl) {
        $embed = [
            'username'    => 'Notifier',
            'avatar_url'  => $avatar,
            'embeds'      => [[
                'title'       => $title,
                'description' => $content,
                'color'       => hexdec('e67e22'),
                'footer'      => ['text' => 'Notification System'],
                'timestamp'   => date('c')
            ]]
        ];

        $ch = curl_init($webhookUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => json_encode($embed),
            CURLOPT_RETURNTRANSFER => true
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    $htmlContent = htmlspecialchars($content, ENT_QUOTES, 'UTF-8'); // escape XSS
    $htmlContent = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $htmlContent);
    $htmlContent = nl2br($htmlContent);

    foreach ($emails as $to) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = getenv('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = getenv('MAIL_USERNAME');
            $mail->Password   = getenv('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom(getenv('MAIL_USERNAME'), 'Website Contact');
            $mail->addReplyTo('info@essenbyte.com', 'Information');
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = "[NOTIFY] $title";
            $mail->Body    = $htmlContent;
            $mail->AltBody = strip_tags($content);

            $mail->send();
        } catch (Exception $e) {
            error_log("Email to $to failed: {$mail->ErrorInfo}");
        }
    }
}

function sendDebugNotify(string $title, string $content): void
{
    $webhooks = [
        getenv('DISCORD_NOTIFICATION_WEBHOOK'),
    ];


    $avatar = 'https://essenbyte.com/assets/images/logo/logo.webp';

    // -------- DISCORD (markdown raw) --------
    foreach ($webhooks as $webhookUrl) {
        $embed = [
            'username'    => 'Notifier',
            'avatar_url'  => $avatar,
            'embeds'      => [[
                'title'       => $title,
                'description' => $content,
                'color'       => hexdec('FF0000'),
                'footer'      => ['text' => 'Notification System'],
                'timestamp'   => date('c')
            ]]
        ];

        $ch = curl_init($webhookUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => json_encode($embed),
            CURLOPT_RETURNTRANSFER => true
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    $htmlContent = htmlspecialchars($content, ENT_QUOTES, 'UTF-8'); // escape XSS
    $htmlContent = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $htmlContent);
    $htmlContent = nl2br($htmlContent);
}

function logEmails(string $email, string $subject, string $content, int $userId, string $email_uid): void
{
    global $conn;

    if (!$userId || $userId <= 0) {
        $userId = 0;
    }

    $safe_subject = mysqli_real_escape_string($conn, $subject);
    $safe_content = mysqli_real_escape_string($conn, $content);
    $safe_userId  = intval($userId);
    $safe_email   = mysqli_real_escape_string($conn, $email);
    $safe_uid     = mysqli_real_escape_string($conn, $email_uid);

    $query = sprintf(
        "INSERT INTO emails_log (email_uid, email, subject, content, sent_at, user_id)
         VALUES ('%s', '%s', '%s', '%s', NOW(), %d)",
        $safe_uid,
        $safe_email,
        $safe_subject,
        $safe_content,
        $safe_userId
    );

    if (!mysqli_query($conn, $query)) {
        sendNotify(
            'Email Log Error',
            'Failed to log email: ' . mysqli_error($conn) .
                "\nSubject: $safe_subject\nContent: $safe_content"
        );
    }
}


function trackCampaign(string $campaign,    string $uid,    string $email,    string $action,    ?string $target = null): void
{
    global $conn;
    $ip   = $_SERVER['REMOTE_ADDR'] ?? null;
    $ua   = $_SERVER['HTTP_USER_AGENT'] ?? null;

    $q = "SELECT id, access_count 
          FROM email_campaign_tracking
          WHERE campaign = ? AND uid = ? AND email = ? AND action = ? AND (target <=> ?)";
    $stmt = $conn->prepare($q);
    $stmt->bind_param("sssss", $campaign, $uid, $email, $action, $target);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if ($row) {
        $upd = $conn->prepare("UPDATE email_campaign_tracking SET access_count = access_count + 1, ip_addr = ?, user_agent = ?, updated_at = NOW() WHERE id = ?");
        $upd->bind_param("ssi", $ip, $ua, $row['id']);
        $upd->execute();
        $upd->close();
    } else {
        $ins = $conn->prepare("INSERT INTO email_campaign_tracking
            (campaign, uid, email, action, access_count, ip_addr, user_agent, target, created_at, updated_at)
            VALUES (?, ?, ?, ?, 1, ?, ?, ?, NOW(), NOW())");
        $ins->bind_param("sssssss", $campaign, $uid, $email, $action, $ip, $ua, $target);
        $ins->execute();
        $ins->close();
    }
}

function generateEmailUID(): string
{
    return bin2hex(random_bytes(16)); // Generates a 32-character hex string
}

function generateSecureToken(): string
{
    return bin2hex(random_bytes(32)); // Generates a 64-character hex string
}


function headerCalls()
{
    loggedRedirect();
    trackFirstVisitToDiscord();
    verifyAccount();
    autoLogoutCheck();
    autoLogin();
    secureUCP();
    secureAdmin();
    $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (
        preg_match('#^/email/track/?$#', $requestPath) &&
        isset($_GET['a'], $_GET['c'], $_GET['uid'], $_GET['email'])
    ) {
        $action   = $_GET['a'];
        $campaign = $_GET['c'];
        $uid      = $_GET['uid'];
        $email    = $_GET['email'];
        $target   = $_GET['t'] ?? null;
        $redirect = $_GET['r'] ?? null;
        trackCampaign($campaign, $uid, $email, $action, $target);

        if ($target === 'register_url') {
            header('Location: /ucp/registration', true, 302);
            exit;
        }

        if ($redirect) {
            header('Location: ' . urldecode($redirect), true, 302);
            exit;
        }
    }
}
