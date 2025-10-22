<?php
$admin = new Admin($conn);
$changelogs = getChangelogs();
?>

<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Manage Changelogs</h2>
    </div>
</div>

<div class="row gx-3 px-4 px-lg-6 pt-6 pb-9">
    <div class="col-12 mb-3">
        <div class="d-flex justify-content-end"><a class="btn btn-primary" href="/ucp/admin/changelogs/create"><span class="fas fa-plus me-2"></span>Add changelog</a></div>
    </div>
    <table class="table table-responsive">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Title</th>
                <th scope="col">Area</th>
                <th scope="col">Version</th>
                <th scope="col">Visible To</th>
                <th scope="col">Posted By</th>
                <th scope="col">Status</th>
                <th scope="col">Pinned</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($changelogs)): ?>
                <?php foreach ($changelogs as $log): ?>
                    <?php $poster = $admin->getAdminDetails($log['posted_by_admin_id'])['full_name'] ?? 'Unknown'; ?>
                    <tr>
                        <th scope="row"><?= htmlspecialchars($log['id']) ?></th>
                        <td><?= htmlspecialchars($log['title']) ?></td>
                        <td><?= htmlspecialchars($log['area']) ?></td>
                        <td><?= htmlspecialchars($log['version']) ?></td>
                        <td><?= htmlspecialchars($log['visible_to']) ?></td>
                        <td><?= htmlspecialchars($poster) ?></td>
                        <td><?= htmlspecialchars($log['status']) ?></td>
                        <td><?= $log['is_pinned'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <a href="/ucp/admin/changelogs/edit?id=<?= $log['id'] ?>">
                                <span data-feather="edit-3"></span> Edit
                            </a>
                            |
                            <a href="#!" onclick="adminDeleteChangelog(<?= $log['id'] ?>)">
                                <span data-feather="trash-2"></span> Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">No changelogs found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>