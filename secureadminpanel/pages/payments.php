<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}

/* ==============================
   Get Current User
================================= */
$stmtUser = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$current_user = $stmtUser->fetch(PDO::FETCH_ASSOC);

/* ==============================
   TOTAL ATHLETES COUNT
================================= */
$stmtTotal = $conn->query("SELECT COUNT(*) FROM atheletes");
$totalu = $stmtTotal->fetchColumn();

/* ==============================
   PAGINATION
================================= */
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

/* Total successful payments */
$countStmt = $conn->prepare("SELECT COUNT(*) FROM paymenthistory WHERE payment_status = :status");
$countStmt->bindValue(':status', 1, PDO::PARAM_INT);
$countStmt->execute();
$totalPayments = $countStmt->fetchColumn();
$totalPages = ceil($totalPayments / $limit);

/* FETCH PAGINATED PAYMENTS */
$stmtPayments = $conn->prepare("
    SELECT id, amount_paid, email, payment_ref, payment_date, channel, gatewayRes
    FROM paymenthistory
    WHERE payment_status = :status
    ORDER BY id DESC
    LIMIT :limit OFFSET :offset
");

$stmtPayments->bindValue(':status', 1, PDO::PARAM_INT);
$stmtPayments->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmtPayments->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtPayments->execute();
$payments = $stmtPayments->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="main-content">
<section class="section">
    <div class="section-header">
        <h1>Welcome, <?= htmlspecialchars($current_user['name']) ?>!</h1>
    </div>

    <!-- Dashboard Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-3">
            <div class="dashboard-card bg-gradient-primary text-white shadow-lg">
                <div class="card-icon"><i class="far fa-user fa-2x"></i></div>
                <div class="card-content">
                    <h5>Total Registered Athletes</h5>
                    <h3><?= number_format($totalu) ?></h3>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-3">
            <div class="dashboard-card bg-gradient-success text-white shadow-lg">
                <div class="card-icon"><i class="fas fa-file-alt fa-2x"></i></div>
                <div class="card-content">
                    <h5><a href="card.php" class="text-white text-decoration-none">Update Requests</a></h5>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-3">
            <div class="dashboard-card bg-gradient-danger text-white shadow-lg">
                <div class="card-icon"><i class="fas fa-credit-card fa-2x"></i></div>
                <div class="card-content">
                    <h5>Total Payments</h5>
                    <h3><?= number_format($totalPayments) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <h4 class="text-center mb-3">ISF Marathon 2024 Payments</h4>
    <div class="table-responsive shadow-sm rounded bg-white p-3">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Amount</th>
                    <th>Email</th>
                    <th>Payment Ref</th>
                    <th>Payment Date</th>
                    <th>Channel</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($payments as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['amount_paid']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['payment_ref']) ?></td>
                    <td><?= htmlspecialchars($row['payment_date']) ?></td>
                    <td><?= htmlspecialchars($row['channel']) ?></td>
                    <td><?= htmlspecialchars($row['gatewayRes']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <nav aria-label="Payments pagination">
        <ul class="pagination justify-content-center mt-3">
            <?php if($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page-1 ?>">Previous</a></li>
            <?php endif; ?>

            <?php for($i=1; $i<=$totalPages; $i++): ?>
                <li class="page-item <?= $i==$page?'active':'' ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>

            <?php if($page < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page+1 ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</section>
</div>

<!-- Custom CSS for professional cards -->
<style>
.dashboard-card {
    position: relative;
    border-radius: 1rem;
    padding: 1.5rem 1rem;
    display: flex;
    align-items: center;
    transition: transform 0.2s, box-shadow 0.2s;
}
.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.2);
}
.dashboard-card .card-icon {
    flex-shrink: 0;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}
.dashboard-card .card-content h5 {
    font-weight: 600;
    font-size: 1rem;
    margin: 0;
}
.dashboard-card .card-content h3 {
    margin: 0.25rem 0 0 0;
    font-weight: bold;
    font-size: 1.5rem;
}
.bg-gradient-primary { background: linear-gradient(135deg,#0d6efd,#6610f2);}
.bg-gradient-success { background: linear-gradient(135deg,#198754,#20c997);}
.bg-gradient-danger { background: linear-gradient(135deg,#dc3545,#fd7e14);}
</style>

<!-- Optional JS: DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script>
$(document).ready(function(){
    $('.table').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy','excel','pdf','print'],
        paging: false
    });
});
</script>
