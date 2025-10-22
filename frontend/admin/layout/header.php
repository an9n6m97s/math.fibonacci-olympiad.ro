<?php
$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$isLoginPage = preg_match('#^/admin/login#', $uriPath);

if (!$isLoginPage) {
    requireAdminLogin();
}

$adminProfile = $isLoginPage ? null : getAdminProfile();
?>
<div class="admin-shell">
    <?php if (!$isLoginPage): ?>
        <header class="admin-bar">
            <div class="admin-brand">
                <a href="/admin/dashboard" class="admin-logo">Fibonacci Admin</a>
            </div>
            <nav class="admin-nav">
                <a href="/admin/dashboard" class="admin-nav__item<?= strpos($uriPath, '/admin/dashboard') === 0 ? ' is-active' : '' ?>">Tablou de bord</a>
                <a href="/admin/participants" class="admin-nav__item<?= strpos($uriPath, '/admin/participants') === 0 ? ' is-active' : '' ?>">Participanți</a>
                <a href="/admin/settings" class="admin-nav__item<?= strpos($uriPath, '/admin/settings') === 0 ? ' is-active' : '' ?>">Setări</a>
            </nav>
            <div class="admin-user">
                <?php if ($adminProfile): ?>
                    <span class="admin-user__name"><?= htmlspecialchars($adminProfile['full_name']) ?></span>
                <?php endif; ?>
                <button type="button" class="admin-logout" id="admin-logout-btn">Ieșire</button>
            </div>
        </header>
        <main class="admin-main">
    <?php else: ?>
        <main class="admin-login">
    <?php endif; ?>
<?php if (!$isLoginPage): ?>
    <div class="admin-main__content">
<?php endif; ?>
<?php if (!$isLoginPage): ?>
    <script>
      (function() {
        const logoutBtn = document.getElementById('admin-logout-btn');
        if (!logoutBtn) return;
        logoutBtn.addEventListener('click', () => {
          fetch('/backend/api/private/admin/logout.php', { method: 'POST' })
            .then(() => {
              window.location.href = '/admin/login';
            });
        });
      })();
    </script>
<?php endif; ?>
