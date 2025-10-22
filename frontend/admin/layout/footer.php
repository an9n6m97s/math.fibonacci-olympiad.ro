<?php
$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$isLoginPage = preg_match('#^/admin/login#', $uriPath);
?>
<?php if (!$isLoginPage): ?>
    </div>
<?php endif; ?>
    </main>
</div>
