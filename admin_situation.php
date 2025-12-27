<?php
session_start();
if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$student = $_SESSION['student'];
$apogee = $student['apoL_a01_code'];
$page_title = "Mes Inscriptions";
$current_page = "admin";

require 'db.php';

$query = "SELECT Filiere, Annee FROM students_base WHERE apoL_a01_code = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Error preparing statement: ' . $conn->error);
}

$stmt->bind_param("s", $apogee);
$stmt->execute();
$result = $stmt->get_result();
$inscription_data = $result->fetch_assoc();
$stmt->close();
$conn->close();

include 'includes/layout_header.php';
?>

<div class="page-header">
    <h1 class="page-title">Mes Inscriptions</h1>
    <p class="page-subtitle">Session d'Automne Ordinaire 2024/2025</p>
</div>

<!-- Student Info Card -->
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-user me-2"></i>Informations de l'étudiant
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="icon-box me-3">
                        <i class="fas fa-id-card text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Nom complet</small>
                        <strong><?= htmlspecialchars($student['Prenom']) ?> <?= htmlspecialchars($student['Nom']) ?></strong>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="icon-box me-3">
                        <i class="fas fa-fingerprint text-success"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Code Apogée</small>
                        <strong><?= htmlspecialchars($student['apoL_a01_code']) ?></strong>
                    </div>
                </div>
            </div>
            
            <?php if (isset($student['CIN']) && !empty($student['CIN'])): ?>
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="icon-box me-3">
                        <i class="fas fa-id-badge text-info"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">CIN</small>
                        <strong><?= htmlspecialchars($student['CIN']) ?></strong>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($student['CNE']) && !empty($student['CNE'])): ?>
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="icon-box me-3">
                        <i class="fas fa-barcode text-warning"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">CNE</small>
                        <strong><?= htmlspecialchars($student['CNE']) ?></strong>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Inscription Card -->
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-graduation-cap me-2"></i>Inscription Académique
    </div>
    <div class="card-body">
        <?php if (empty($inscription_data['Filiere']) && empty($inscription_data['Annee'])): ?>
            <div class="empty-state">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <h5>Aucune inscription trouvée</h5>
                <p class="text-muted">Aucune information disponible</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="icon-box me-3">
                            <i class="fas fa-book-open text-primary"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Filière</small>
                            <strong><?= htmlspecialchars($inscription_data['Filiere'] ?? 'Non spécifiée') ?></strong>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="icon-box me-3">
                            <i class="fas fa-calendar-alt text-danger"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Année d'études</small>
                            <strong><?= htmlspecialchars($inscription_data['Annee'] ?? 'Non spécifiée') ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="text-center">
    <a href="dashboard.php" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
</div>

<?php include 'includes/layout_footer.php'; ?>