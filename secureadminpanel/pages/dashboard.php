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
   Dashboard Stats
================================= */
$totalStmt = $conn->query("SELECT COUNT(*) FROM atheletes");
$totalAthletes = $totalStmt->fetchColumn();

/* Gender counts */
$genderStmt = $conn->query("SELECT gender, COUNT(*) as count FROM atheletes GROUP BY gender");
$genderData = $genderStmt->fetchAll(PDO::FETCH_ASSOC);

$maleCount = 0;
$femaleCount = 0;
foreach ($genderData as $g) {
    if (strtolower($g['gender']) === 'male') $maleCount = $g['count'];
    if (strtolower($g['gender']) === 'female') $femaleCount = $g['count'];
}

/* State counts */
$stateStmt = $conn->query("SELECT state, COUNT(*) as count FROM atheletes GROUP BY state");
$stateData = $stateStmt->fetchAll(PDO::FETCH_ASSOC);

/* Pagination for Athletes Table */
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

/* Fetch paginated athletes */
$stmt = $conn->prepare("
    SELECT appno, names, email, phone, gender, LGA, purpose, emergency_contact, tshirt_size, blood_group, medical_conditions, date
    FROM atheletes
    ORDER BY id DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$athletes = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Total pages */
$totalPages = ceil($totalAthletes / $limit);

include 'header.php';
?>

<div class="main-content">
<section class="section">

<!-- Header -->
<div class="section-header mb-4">
    <h1>Welcome, <?= htmlspecialchars($current_user['name']) ?> 👋</h1>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="dashboard-card bg-gradient-primary text-white shadow-lg">
            <div class="card-icon"><i class="fas fa-user fa-2x"></i></div>
            <div class="card-content">
                <h5>Total Athletes</h5>
                <h3><?= number_format($totalAthletes) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="dashboard-card bg-gradient-success text-white shadow-lg">
            <div class="card-icon"><i class="fas fa-male fa-2x"></i></div>
            <div class="card-content">
                <h5>Male Athletes</h5>
                <h3><?= $maleCount ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="dashboard-card bg-gradient-danger text-white shadow-lg">
            <div class="card-icon"><i class="fas fa-female fa-2x"></i></div>
            <div class="card-content">
                <h5>Female Athletes</h5>
                <h3><?= $femaleCount ?></h3>
            </div>
        </div>
    </div>
</div>
<!-- Charts -->
<div class="row mb-5">
    <div class="col-md-6 mb-3">
        <div class="dashboard-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Gender Distribution</h5>
                <div style="max-width: 300px; margin: auto;">
                    <canvas id="genderChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="dashboard-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Athletes by State</h5>
                <div style="max-width: 300px; margin: auto;">
                    <canvas id="stateChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Athletes Table -->
<div class="dashboard-card shadow-sm mb-5">
    <div class="card-body">
        <h5 class="card-title">Registered Athletes</h5>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Reg. Number</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>State/LGA</th>
                        <th>Run For</th>
                        <th>Emerg. Contact</th>
                        <th>Bib Size</th>
                        <th>Blood Group</th>
                        <th>Medically</th>
                        <th>Date Reg.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($athletes): ?>
                        <?php foreach ($athletes as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['appno']) ?></td>
                            <td><?= htmlspecialchars($row['names']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td><?= htmlspecialchars($row['gender']) ?></td>
                            <td><?= htmlspecialchars($row['LGA']) ?></td>
                            <td><?= htmlspecialchars($row['purpose']) ?></td>
                        <td><?= htmlspecialchars($row['emergency_contact']) ?></td>
                        <td><?=htmlspecialchars($row['tshirt_size'])?></td>
                            <td><?= htmlspecialchars($row['blood_group']) ?></td>
                        <td><?= htmlspecialchars($row['medical_conditions']) ?></td>
                        <td><?=htmlspecialchars($row['date']) ?></td>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center">No athletes found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="text-center mt-3">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page-1 ?>" class="btn btn-lg btn-primary mx-1">Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="btn btn-lg <?= ($i==$page)?'btn-success':'btn-light' ?> mx-1"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page+1 ?>" class="btn btn-lg btn-primary mx-1">Next</a>
            <?php endif; ?>
        </div>
    </div>
</div>

</section>
</div>

<!-- Custom CSS for dashboard -->
<style>
.dashboard-card {
    border-radius: 1rem;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    transition: transform 0.2s, box-shadow 0.2s;
    background: #fff;
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
    background: rgba(0,0,0,0.05);
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
.bg-gradient-primary { background: linear-gradient(135deg,#0d6efd,#6610f2); color:#fff; }
.bg-gradient-success { background: linear-gradient(135deg,#198754,#20c997); color:#fff; }
.bg-gradient-danger { background: linear-gradient(135deg,#dc3545,#fd7e14); color:#fff; }
.btn-lg { padding: 0.6rem 1.2rem; font-size: 1rem; border-radius: 0.5rem; }
</style>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const genderCtx = document.getElementById('genderChart').getContext('2d');
new Chart(genderCtx, {
    type: 'doughnut',
    data: {
        labels: ['Male', 'Female'],
        datasets: [{
            data: [<?= $maleCount ?>, <?= $femaleCount ?>],
            backgroundColor: ['#0d6efd','#dc3545']
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
});

const stateCtx = document.getElementById('stateChart').getContext('2d');
new Chart(stateCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($stateData,'state')) ?>,
        datasets: [{
            label: 'Athletes by State',
            data: <?= json_encode(array_column($stateData,'count')) ?>,
            backgroundColor: '#198754'
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>
