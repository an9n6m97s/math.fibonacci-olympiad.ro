<?php
// Get email logs from existing emails_log table
$page = $_GET['page'] ?? 1;
$limit = 100;
$offset = ($page - 1) * $limit;

$searchQuery = '';
$searchTerm = $_GET['search'] ?? '';

if (!empty($searchTerm)) {
    $searchQuery = " WHERE subject LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%'";
}

// Get total count
$countQuery = "SELECT COUNT(*) as total FROM emails_log" . $searchQuery;
$countResult = mysqli_query($conn, $countQuery);
$totalLogs = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalLogs / $limit);

// Get logs with pagination - group by email_uid to show batches
$logsQuery = "SELECT el.email_uid, el.subject, el.sent_at, el.user_id,
              COUNT(*) as recipient_count,
              GROUP_CONCAT(el.email ORDER BY el.email SEPARATOR ', ') as recipients_list
              FROM emails_log el 
              $searchQuery
              GROUP BY el.email_uid, el.subject, el.sent_at
              ORDER BY el.sent_at DESC 
              LIMIT $limit OFFSET $offset";
$logsResult = mysqli_query($conn, $logsQuery);
$logs = [];
if ($logsResult) {
    while ($row = mysqli_fetch_assoc($logsResult)) {
        $logs[] = $row;
    }
}
?>

<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Email Logs</h2>
        <p class="text-muted mb-0">View all sent emails and their details</p>
    </div>
    <div class="col-auto">
        <div class="d-flex align-items-center gap-2">
            <a href="/ucp/admin/email/template" class="btn btn-outline-primary btn-sm">
                <span data-feather="edit-3" class="me-1"></span>Edit Template
            </a>
            <a href="/ucp/admin/email/compose" class="btn btn-primary btn-sm">
                <span data-feather="plus" class="me-1"></span>Compose New Email
            </a>
        </div>
    </div>
</div>

<div class="mx-n4 mx-lg-n6 px-4 px-lg-6 mb-9 bg-body-emphasis border-y mt-2 position-relative top-1">
    <div class="py-4">
        <!-- Search and Filters -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" action="" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Search emails..." value="<?= htmlspecialchars($searchTerm) ?>">
                    <button type="submit" class="btn btn-primary">
                        <span data-feather="search"></span>
                    </button>
                    <?php if (!empty($searchTerm)): ?>
                        <a href="/ucp/admin/email/logs" class="btn btn-outline-secondary">Clear</a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <span class="text-muted">Total: <?= $totalLogs ?> emails</span>
            </div>
        </div>

        <!-- Email Logs Table -->
        <?php if (!empty($logs)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Subject</th>
                            <th>Recipients</th>
                            <th>Sent By</th>
                            <th>Sent Count</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($log['subject']) ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?= $log['recipient_count'] ?> recipients</span>
                                    <div class="small text-muted mt-1">
                                        <?php
                                        $recipients_list = explode(', ', $log['recipients_list']);
                                        if (count($recipients_list) <= 3) {
                                            foreach ($recipients_list as $email) {
                                                echo htmlspecialchars($email) . '<br>';
                                            }
                                        } else {
                                            for ($i = 0; $i < 3; $i++) {
                                                echo htmlspecialchars($recipients_list[$i]) . '<br>';
                                            }
                                            echo '+ ' . (count($recipients_list) - 3) . ' more...';
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <div>System Admin</div>
                                    <div class="small text-muted">Automated</div>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <?= $log['recipient_count'] ?> sent
                                    </span>
                                </td>
                                <td>
                                    <div><?= date('M j, Y', strtotime($log['sent_at'])) ?></div>
                                    <div class="small text-muted"><?= date('H:i:s', strtotime($log['sent_at'])) ?></div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" onclick="viewEmailDetails('<?= htmlspecialchars($log['email_uid']) ?>')">
                                            <span data-feather="eye"></span>
                                        </button>
                                        <button class="btn btn-outline-info" onclick="previewEmailContent('<?= htmlspecialchars($log['email_uid']) ?>')">
                                            <span data-feather="mail"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Email logs pagination">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page - 1 ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>">Previous</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <li class="page-item <?= $i === (int)$page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page + 1 ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        <?php else: ?>
            <div class="text-center py-5">
                <div class="mb-3">
                    <span data-feather="inbox" style="width: 64px; height: 64px;" class="text-muted"></span>
                </div>
                <h4>No emails found</h4>
                <p class="text-muted">
                    <?= !empty($searchTerm) ? 'No emails match your search criteria.' : 'No emails have been sent yet.' ?>
                </p>
                <?php if (empty($searchTerm)): ?>
                    <a href="/ucp/admin/email/compose" class="btn btn-primary">
                        <span data-feather="plus" class="me-1"></span>Send Your First Email
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Email Details Modal -->
<div class="modal fade" id="emailDetailsModal" tabindex="-1" aria-labelledby="emailDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailDetailsModalLabel">Email Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="emailDetailsContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Email Preview Modal -->
<div class="modal fade" id="emailPreviewModal" tabindex="-1" aria-labelledby="emailPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailPreviewModalLabel">Email Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="emailPreviewIframe" style="width: 100%; height: 600px; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function viewEmailDetails(emailUid) {
        $('#emailDetailsModal').modal('show');

        fetch(`/backend/api/private/email/details.php?uid=${emailUid}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const emails = data.emails;

                    let recipientsList = emails.map(e =>
                        `<li><strong>${e.email}</strong></li>`
                    ).join('');

                    const content = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Subject:</h6>
                            <p>${emails[0].subject}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Sent by:</h6>
                            <p>System Admin (Automated)</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Date sent:</h6>
                            <p>${new Date(emails[0].sent_at).toLocaleString()}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Success count:</h6>
                            <p>${emails.length} recipients</p>
                        </div>
                        <div class="col-12">
                            <h6>Recipients:</h6>
                            <ul>${recipientsList}</ul>
                        </div>
                    </div>
                `;

                    document.getElementById('emailDetailsContent').innerHTML = content;
                } else {
                    document.getElementById('emailDetailsContent').innerHTML = '<div class="alert alert-danger">Failed to load email details.</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('emailDetailsContent').innerHTML = '<div class="alert alert-danger">Error loading email details.</div>';
            });
    }

    function previewEmailContent(emailUid) {
        $('#emailPreviewModal').modal('show');

        fetch(`/backend/api/private/email/preview.php?uid=${emailUid}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('emailPreviewIframe').srcdoc = html;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('emailPreviewIframe').srcdoc = '<html><body><div style="padding:20px;">Error loading email preview.</div></body></html>';
            });
    } // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>