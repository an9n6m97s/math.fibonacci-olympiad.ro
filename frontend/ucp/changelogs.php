<h2 class="mb-2 lh-sm">Changelog</h2>


<?php
$changelogs = getChangelogs();

$pinned = [];
$unpinned = [];

foreach ($changelogs as $changelog) {
    if ($changelog["visible_to"] == 'all') {
        if (!empty($changelog['is_pinned'])) {
            $pinned[] = $changelog;
        } else {
            $unpinned[] = $changelog;
        }
    }
}

$orderedChangelogs = array_merge($pinned, $unpinned);

foreach ($orderedChangelogs as $changelog):
    $adminName = '';
    if (!empty($changelog['posted_by_admin_id'])) {
        $admin = getAdminDataById($changelog['posted_by_admin_id']);
        $adminName = setAdminRole($admin['role']) . ' ' . ($admin['full_name'] ?? '');
    }
?>

    <div class="card shadow-none border my-3">
        <div class="card-header border-bottom bg-body">
            <h5 class="mb-2">
                <code class="fw-bold fs-7">
                    <?= htmlspecialchars($changelog['version'] ?? '—') ?>
                </code>
                - <?= htmlspecialchars($changelog['title'] ?? 'Changelog') ?>
                <?php if (!empty($changelog['is_pinned'])): ?>
                    <span class="fibo-chip fibo-chip--sm fibo-pin"><i></i>Pinned</span>
                <?php endif; ?>
            </h5>
            <p class="mb-0">
                <?= isset($changelog['published_at']) ? date('d F, Y', strtotime($changelog['published_at'])) : '' ?>
                <?php if ($adminName): ?>
                    · Posted by <?= $adminName ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="card-body">
            <?= $changelog['description'] ?? '' ?>
        </div>
    </div>

<?php endforeach; ?>