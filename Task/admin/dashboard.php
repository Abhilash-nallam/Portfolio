<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

require_login();

$pdo = getDbConnection();

// ---- Stat cards ----
$stats = ['Total' => 0, 'New' => 0, 'Contacted' => 0, 'Closed' => 0];
$stats['Total'] = (int) $pdo->query('SELECT COUNT(*) FROM leads')->fetchColumn();

$statusCountStmt = $pdo->query('SELECT status, COUNT(*) AS c FROM leads GROUP BY status');
foreach ($statusCountStmt->fetchAll() as $row) {
    $stats[$row['status']] = (int) $row['c'];
}

// ---- Filters: search + status + pagination ----
$search       = trim($_GET['q'] ?? '');
$statusFilter = $_GET['status'] ?? '';
$allowedStatus = ['New', 'Contacted', 'Closed'];
if (!in_array($statusFilter, $allowedStatus, true)) {
    $statusFilter = '';
}

$perPage = 8;
$page    = max(1, (int) ($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;

$where  = [];
$params = [];

if ($search !== '') {
    $where[] = '(name LIKE :search OR email LIKE :search OR message LIKE :search)';
    $params['search'] = '%' . $search . '%';
}
if ($statusFilter !== '') {
    $where[] = 'status = :status';
    $params['status'] = $statusFilter;
}

$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

// Total count for pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM leads $whereSql");
$countStmt->execute($params);
$totalRows  = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($totalRows / $perPage));
$page       = min($page, $totalPages);
$offset     = ($page - 1) * $perPage;

// Fetch leads (sorted by latest first)
$sql  = "SELECT * FROM leads $whereSql ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue(":$key", $val);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$leads = $stmt->fetchAll();

$pageTitle = 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard &mdash; LeadDesk Mini</title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../css/style.css">
</head>
<body class="admin-body">

<div class="admin-layout">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="admin-main">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="admin-content">

            <!-- Stat Cards -->
            <div class="row g-3 stat-row">
                <div class="col-6 col-lg-3">
                    <div class="stat-card stat-total">
                        <div class="stat-icon"><i class="fa-solid fa-layer-group"></i></div>
                        <div><div class="stat-value"><?= $stats['Total'] ?></div><div class="stat-label">Total Leads</div></div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card stat-new">
                        <div class="stat-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
                        <div><div class="stat-value"><?= $stats['New'] ?></div><div class="stat-label">New Leads</div></div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card stat-contacted">
                        <div class="stat-icon"><i class="fa-solid fa-phone-volume"></i></div>
                        <div><div class="stat-value"><?= $stats['Contacted'] ?></div><div class="stat-label">Contacted</div></div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card stat-closed">
                        <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
                        <div><div class="stat-value"><?= $stats['Closed'] ?></div><div class="stat-label">Closed</div></div>
                    </div>
                </div>
            </div>

            <!-- Leads Table Card -->
            <div class="content-card">
                <div class="content-card-head">
                    <h2>Leads<?= $statusFilter ? ' &mdash; ' . e($statusFilter) : '' ?></h2>

                    <form class="search-form" method="GET" action="dashboard.php">
                        <?php if ($statusFilter): ?><input type="hidden" name="status" value="<?= e($statusFilter) ?>"><?php endif; ?>
                        <div class="input-icon">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" name="q" class="form-control" placeholder="Search name, email, message..." value="<?= e($search) ?>">
                        </div>
                        <button class="btn btn-primary-grad" type="submit">Search</button>
                        <?php if ($search || $statusFilter): ?>
                            <a href="dashboard.php" class="btn btn-outline-secondary">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table leads-table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Budget</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Received</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="leadsTableBody">
                        <?php if (empty($leads)): ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">No leads found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($leads as $lead): ?>
                                <tr data-lead-id="<?= (int) $lead['id'] ?>">
                                    <td>#<?= (int) $lead['id'] ?></td>
                                    <td class="fw-semibold"><?= e($lead['name']) ?></td>
                                    <td><a href="mailto:<?= e($lead['email']) ?>"><?= e($lead['email']) ?></a></td>
                                    <td><?= e($lead['budget']) ?></td>
                                    <td class="lead-message" title="<?= e($lead['message']) ?>"><?= e(mb_strimwidth($lead['message'], 0, 50, '...')) ?></td>
                                    <td>
                                        <select class="form-select form-select-sm status-select <?= status_badge_class($lead['status']) ?>" data-lead-id="<?= (int) $lead['id'] ?>">
                                            <?php foreach ($allowedStatus as $s): ?>
                                                <option value="<?= $s ?>" <?= $s === $lead['status'] ? 'selected' : '' ?>><?= $s ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="text-nowrap small text-muted"><?= e(format_date($lead['created_at'])) ?></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-icon view-lead-btn" title="View" data-bs-toggle="modal" data-bs-target="#viewLeadModal"
                                            data-name="<?= e($lead['name']) ?>" data-email="<?= e($lead['email']) ?>"
                                            data-budget="<?= e($lead['budget']) ?>" data-message="<?= e($lead['message']) ?>"
                                            data-date="<?= e(format_date($lead['created_at'])) ?>">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon text-danger delete-lead-btn" title="Delete" data-lead-id="<?= (int) $lead['id'] ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav class="pagination-wrap">
                    <ul class="pagination">
                        <?php
                        $qs = fn($p) => http_build_query(array_merge($_GET, ['page' => $p]));
                        ?>
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= $qs(max(1, $page - 1)) ?>">&laquo;</a>
                        </li>
                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                            <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= $qs($p) ?>"><?= $p ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= $qs(min($totalPages, $page + 1)) ?>">&raquo;</a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>
    </div>
</div>

<!-- View Lead Modal -->
<div class="modal fade" id="viewLeadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-id-card me-2"></i>Lead Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <dl class="lead-detail-list">
                    <dt>Name</dt><dd id="mdName"></dd>
                    <dt>Email</dt><dd id="mdEmail"></dd>
                    <dt>Budget</dt><dd id="mdBudget"></dd>
                    <dt>Message</dt><dd id="mdMessage"></dd>
                    <dt>Received</dt><dd id="mdDate"></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<!-- Toast container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

<!-- Loading overlay -->
<div class="loading-overlay d-none" id="loadingOverlay"><div class="spinner-border" role="status"></div></div>

<script>
    const CSRF_TOKEN = "<?= csrf_token() ?>";
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/admin.js"></script>
</body>
</html>
