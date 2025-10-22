<?php
$categories = getCategories();
$category_slug = $_GET['c'] ?? '';
$category = null;
$regulations = [];

// Calculate total regulations across all categories
$totalRegulations = 0;
foreach ($categories as $cat) {
    $catRegulations = getCategoryRegulationBySlug($cat['slug']);
    $totalRegulations += count($catRegulations);
}

if (!empty($category_slug)) {
    $category = getCategoryBySlug($category_slug);
    $regulations = getCategoryRegulationBySlug($category_slug);
}
?>

<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Manage Regulations</h2>
    </div>
    <div class="col-auto">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-info fs-8"><?= $totalRegulations ?> Total Regulations</span>
            <a class="btn btn-primary" href="/ucp/admin/regulation/upload"><span class="fas fa-plus me-2"></span>Add regulation</a>
        </div>
    </div>
</div>

<?php if (empty($category_slug)): ?>
    <!-- Categories Overview -->
    <div id="regulations" data-list='{"valueNames":["category","regulations"],"page":10,"pagination":true}'>
        <div class="row align-items-center justify-content-between g-3 mb-4">
            <div class="col col-auto">
                <div class="search-box">
                    <form class="position-relative">
                        <input class="form-control search-input search" type="search" placeholder="Search categories and regulations" aria-label="Search" />
                        <span class="fas fa-search search-box-icon"></span>
                    </form>
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex align-items-center">
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['slug'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="mx-n4 mx-lg-n6 px-4 px-lg-6 mb-9 bg-body-emphasis border-y mt-2 position-relative top-1">
            <div class="row g-4 py-4">
                <?php foreach ($categories as $cat): ?>
                    <?php
                    $categoryRegulations = getCategoryRegulationBySlug($cat['slug']);
                    $regulationCount = count($categoryRegulations);
                    ?>
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 hover-actions-trigger">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-m me-3 bg-primary-subtle text-primary">
                                        <span data-feather="file-text"></span>
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="mb-0 category"><?= htmlspecialchars($cat['name']) ?></h5>
                                        <p class="mb-0 text-body-tertiary fs-9 regulations"><?= $regulationCount ?> regulation<?= $regulationCount !== 1 ? 's' : '' ?></p>
                                    </div>
                                </div>

                                <p class="text-body-secondary fs-9 mb-3">
                                    <?= htmlspecialchars($cat['description'] ?? 'Manage and edit regulations for ' . $cat['name'] . ' category.') ?>
                                </p>

                                <div class="mt-auto">
                                    <div class="d-flex gap-2">
                                        <a href="?c=<?= $cat['slug'] ?>" class="btn btn-primary btn-sm flex-1">
                                            <span data-feather="eye" class="me-1"></span>View Regulations
                                        </a>
                                        <a href="/ucp/admin/regulation/edit?category=<?= $cat['slug'] ?>" class="btn btn-outline-secondary btn-sm">
                                            <span data-feather="edit-2"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Single Category View -->
    <div class="row align-items-center justify-content-between g-3 mb-4 px-4 px-lg-6">
        <div class="col-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="?">All Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($category['name'] ?? 'Category') ?></li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <div class="d-flex align-items-center gap-2">
                <a href="?" class="btn btn-outline-secondary btn-sm">
                    <span class="fas fa-arrow-left me-1"></span>Back to Overview
                </a>
                <a href="/ucp/admin/regulation/edit?category=<?= $category_slug ?>" class="btn btn-primary btn-sm">
                    <span data-feather="edit-3" class="me-1"></span>Edit Category
                </a>
            </div>
        </div>
    </div>

    <?php if (!empty($regulations)): ?>
        <div class="mx-n4 mx-lg-n6 px-4 px-lg-6 mb-9 bg-body-emphasis border-y mt-2 position-relative top-1">
            <?php foreach ($regulations as $regulation): ?>
                <div class="border-bottom border-translucent py-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="mb-2 text-body-emphasis"><?= htmlspecialchars($category['name']) ?> Regulations</h4>
                            <div class="d-flex align-items-center text-body-tertiary fs-9">
                                <span class="me-2">Category: <?= htmlspecialchars($category['name']) ?></span>
                                <span class="fas fa-circle me-2" style="font-size: 4px;"></span>
                                <span>Updated: <?= date('M j, Y', strtotime($regulation['updated_at'] ?? $regulation['created_at'])) ?></span>
                            </div>
                        </div>
                        <div class="btn-group">
                            <a href="/ucp/admin/regulation/edit?c=<?= $category_slug ?>" class="btn btn-primary btn-sm">
                                <span data-feather="edit-2" class="me-1"></span>Edit
                            </a>
                            <button class="btn btn-outline-danger btn-sm" onclick="deleteRegulation(<?= $regulation['id'] ?>)">
                                <span data-feather="trash-2"></span>
                            </button>
                        </div>
                    </div>

                    <div class="regulation-content text-body">
                        <?= $regulation['content'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <span data-feather="file-x" style="width: 48px; height: 48px;" class="text-body-tertiary"></span>
            </div>
            <h4 class="text-body-emphasis mb-3">No Regulations Found</h4>
            <p class="text-body-secondary mb-4">
                This category doesn't have any regulations yet. Create your first regulation to get started.
            </p>
            <a href="/ucp/admin/regulation/upload?category=<?= $category_slug ?>" class="btn btn-primary">
                <span class="fas fa-plus me-2"></span>Create First Regulation
            </a>
        </div>
    <?php endif; ?>

<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Category filter functionality
        const categoryFilter = document.getElementById('categoryFilter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', function() {
                const selectedCategory = this.value;
                if (selectedCategory) {
                    window.location.href = `?c=${selectedCategory}`;
                } else {
                    window.location.href = '?';
                }
            });
        }
    });

    function deleteRegulation(id) {
        if (confirm('Are you sure you want to delete this regulation? This action cannot be undone.')) {
            fetch('/backend/api/private/delete/regulation.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notify('Regulation deleted successfully!', 'success');
                        location.reload();
                    } else {
                        notify('Failed to delete regulation: ' + (data.message || 'Unknown error'), 'error');
                    }
                })
                .catch(error => {
                    notify('Error deleting regulation. Please try again.', 'error');
                    console.error('Delete error:', error);
                });
        }
    }

    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>