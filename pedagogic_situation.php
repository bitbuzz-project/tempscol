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

// Simple query - just get all modules for the student
$query = "
    SELECT 
        ps.`Code Module`,
        ps.Groupe,
        m.NameFR,
        m.NameAR,
        m.Semester,
        m.`Academic Year`,
        m.`Element Type`
    FROM peda_sit ps
    LEFT JOIN mods m ON ps.`Code Module` = m.code
    WHERE ps.apogee = ?
    ORDER BY ps.`Code Module`
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die('Error preparing statement: ' . $conn->error);
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

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-book-open me-2"></i>
        Situation Pédagogique
    </h1>
    <p class="page-subtitle">
        Mes inscriptions aux modules - Session d'Automne Ordinaire 2024/2025
    </p>
</div>

<!-- Student Info Summary -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-2">
                    <i class="fas fa-user-graduate me-2 text-primary"></i>
                    <?= htmlspecialchars($student['Prenom']) ?> <?= htmlspecialchars($student['Nom']) ?>
                </h5>
                <div class="d-flex flex-wrap gap-3">
                    <span class="text-muted">
                        <i class="fas fa-id-card me-1"></i>
                        <strong>Apogée:</strong> <?= htmlspecialchars($student['apoL_a01_code']) ?>
                    </span>
                    <?php if (isset($student['Filiere']) && !empty($student['Filiere'])): ?>
                    <span class="text-muted">
                        <i class="fas fa-graduation-cap me-1"></i>
                        <strong>Filière:</strong> <?= htmlspecialchars($student['Filiere']) ?>
                    </span>
                    <?php endif; ?>
                    <?php if (isset($student['Annee']) && !empty($student['Annee'])): ?>
                    <span class="text-muted">
                        <i class="fas fa-calendar-alt me-1"></i>
                        <strong>Année:</strong> <?= htmlspecialchars($student['Annee']) ?>
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-primary px-4 py-2" style="font-size: 1rem;">
                    <i class="fas fa-book me-2"></i>
                    <?= count($all_modules) ?> Modules
                </span>
            </div>
        </div>
    </div>
</div>

<?php if (empty($all_modules)): ?>
    <!-- Empty State -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="empty-state py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5>Aucune inscription trouvée</h5>
                <p class="text-muted">Aucune donnée pédagogique disponible pour cet étudiant.</p>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Modules Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4">
            <h5 class="mb-0">
                <i class="fas fa-list text-primary me-2"></i>
                Liste des Modules Inscrits
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 20%">
                                <i class="fas fa-barcode me-2"></i>Code Module
                            </th>
                            <th style="width: 45%">
                                <i class="fas fa-book me-2"></i>Nom du Module
                            </th>
                            <th style="width: 15%">
                                <i class="fas fa-users me-2"></i>Groupe
                            </th>
                            <th style="width: 15%">
                                <i class="fas fa-calendar-alt me-2"></i>Semestre
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $counter = 1;
                        foreach ($all_modules as $module): 
                            // Get module name (Arabic priority, or French, or code)
                            $module_name = '';
                            if (!empty($module['NameAR'])) {
                                $module_name = $module['NameAR'];
                            } elseif (!empty($module['NameFR'])) {
                                $module_name = $module['NameFR'];
                            } else {
                                $module_name = $module['Code Module'];
                            }
                            
                            // Get semester (from mods or extract from code)
                            $semester = $module['Semester'];
                            if (empty($semester) && preg_match('/(\d)/', $module['Code Module'], $matches)) {
                                $semester = 'S' . $matches[1];
                            }
                        ?>
                        <tr>
                            <td class="text-center">
                                <strong><?= $counter++ ?></strong>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-2">
                                    <?= htmlspecialchars($module['Code Module']) ?>
                                </span>
                            </td>
                            <td>
                                <div>
                                    <strong><?= htmlspecialchars($module_name) ?></strong>
                                    
                                    <?php if (!empty($module['NameAR']) && !empty($module['NameFR'])): ?>
                                    <br>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($module['NameFR']) ?>
                                    </small>
                                    <?php endif; ?>
                                    
                                    <?php if (empty($module['NameAR']) && empty($module['NameFR'])): ?>
                                    <br>
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Nom du module non défini
                                    </small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($module['Groupe'])): ?>
                                    <span class="badge bg-primary px-3 py-2">
                                        Groupe <?= htmlspecialchars($module['Groupe']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($semester)): ?>
                                    <span class="badge bg-info text-white px-3 py-2">
                                        <?= htmlspecialchars($semester) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light text-center">
            <strong>Total: <?= count($all_modules) ?> modules</strong>
        </div>
    </div>
<?php endif; ?>

<!-- Back Button -->
<div class="text-center mt-4">
    <a href="dashboard.php" class="btn btn-outline-primary btn-lg px-5">
        <i class="fas fa-arrow-left me-2"></i>
        Retour au tableau de bord
    </a>
</div>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.1);
    transition: all 0.2s ease;
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table tbody td {
    vertical-align: middle;
    padding: 15px;
}
</style>

<?php include 'includes/layout_footer.php'; ?>