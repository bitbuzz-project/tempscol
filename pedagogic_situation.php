<?php
session_start();
if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$student = $_SESSION['student'];
$apogee = $student['apoL_a01_code'];
$page_title = "Situation Pédagogique";
$current_page = "pedagogic";

require 'db.php';

$query = "
    SELECT 
        ps.`Code Module`,
        ps.Groupe,
        m.NameFR,
        m.NameAR,
        m.Semester
    FROM peda_sit ps
    LEFT JOIN mods m ON ps.`Code Module` = m.code
    WHERE ps.apogee = ?
    ORDER BY ps.`Code Module`
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die('Error: ' . $conn->error);
}

$stmt->bind_param("s", $apogee);
$stmt->execute();
$result = $stmt->get_result();

$all_modules = [];
while ($row = $result->fetch_assoc()) {
    $all_modules[] = $row;
}

$stmt->close();
$conn->close();

include 'includes/layout_header.php';
?>

<div class="page-header">
    <h1 class="page-title">Situation Pédagogique</h1>
    <p class="page-subtitle">Mes inscriptions aux modules - Session 2024/2025</p>
</div>

<!-- Summary Card -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-1"><?= htmlspecialchars($student['Prenom']) ?> <?= htmlspecialchars($student['Nom']) ?></h5>
                <div class="d-flex gap-3 flex-wrap">
                    <span class="text-muted small">
                        <i class="fas fa-id-card me-1"></i>
                        <?= htmlspecialchars($student['apoL_a01_code']) ?>
                    </span>
                    <?php if (isset($student['Filiere'])): ?>
                    <span class="text-muted small">
                        <i class="fas fa-graduation-cap me-1"></i>
                        <?= htmlspecialchars($student['Filiere']) ?>
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <span class="badge bg-primary" style="font-size: 14px; padding: 8px 16px;">
                    <?= count($all_modules) ?> Modules
                </span>
            </div>
        </div>
    </div>
</div>

<?php if (empty($all_modules)): ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-inbox fa-4x mb-3"></i>
                <h5>Aucune inscription trouvée</h5>
                <p class="text-muted">Aucune donnée pédagogique disponible</p>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-header">
            <i class="fas fa-list me-2"></i>Liste des Modules
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 20%">Code</th>
                        <th style="width: 45%">Module</th>
                        <th style="width: 15%">Groupe</th>
                        <th style="width: 15%">Semestre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1;
                    foreach ($all_modules as $module): 
                        $module_name = $module['NameAR'] ?: ($module['NameFR'] ?: $module['Code Module']);
                        $semester = $module['Semester'];
                        if (empty($semester) && preg_match('/(\d)/', $module['Code Module'], $matches)) {
                            $semester = 'S' . $matches[1];
                        }
                    ?>
                    <tr>
                        <td class="text-center"><strong><?= $counter++ ?></strong></td>
                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($module['Code Module']) ?></span></td>
                        <td>
                            <strong><?= htmlspecialchars($module_name) ?></strong>
                            <?php if (!empty($module['NameAR']) && !empty($module['NameFR'])): ?>
                            <br><small class="text-muted"><?= htmlspecialchars($module['NameFR']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($module['Groupe'])): ?>
                                <span class="badge bg-primary">Groupe <?= htmlspecialchars($module['Groupe']) ?></span>
                            <?php else: ?>
                                <span class="badge bg-light text-dark">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($semester)): ?>
                                <span class="badge bg-info text-white"><?= htmlspecialchars($semester) ?></span>
                            <?php else: ?>
                                <span class="badge bg-light text-dark">N/A</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-body text-center border-top">
            <strong>Total: <?= count($all_modules) ?> modules</strong>
        </div>
    </div>
<?php endif; ?>

<div class="text-center mt-4">
    <a href="dashboard.php" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
</div>

<?php include 'includes/layout_footer.php'; ?>