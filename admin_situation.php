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

// Fetch student data from students_base (Filiere and Annee are now here)
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

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-user-check me-2"></i>
        Situation Administrative
    </h1>
    <p class="page-subtitle">
        Session d'Automne Ordinaire 2024/2025
    </p>
</div>

<!-- Student Info Card -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h5 class="mb-0 text-white">
            <i class="fas fa-user me-2"></i>
            Informations de l'étudiant
        </h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box me-3">
                        <i class="fas fa-id-card fa-2x text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Nom complet</small>
                        <strong class="fs-5">
                            <?= htmlspecialchars($student['Prenom']) ?> <?= htmlspecialchars($student['Nom']) ?>
                        </strong>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box me-3">
                        <i class="fas fa-fingerprint fa-2x text-success"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Code Apogée</small>
                        <strong class="fs-5"><?= htmlspecialchars($student['apoL_a01_code']) ?></strong>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box me-3">
                        <i class="fas fa-id-badge fa-2x text-info"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">CIN</small>
                        <strong class="fs-5"><?= htmlspecialchars($student['CIN'] ?? 'N/A') ?></strong>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box me-3">
                        <i class="fas fa-barcode fa-2x text-warning"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">CNE</small>
                        <strong class="fs-5"><?= htmlspecialchars($student['CNE'] ?? 'N/A') ?></strong>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box me-3">
                        <i class="fas fa-birthday-cake fa-2x text-danger"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Date de naissance</small>
                        <strong class="fs-5"><?= htmlspecialchars($student['Date Naissance'] ?? 'N/A') ?></strong>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box me-3">
                        <i class="fas fa-map-marker-alt fa-2x text-secondary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Lieu de naissance</small>
                        <strong class="fs-5"><?= htmlspecialchars($student['Lieu Naissance'] ?? 'N/A') ?></strong>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box me-3">
                        <i class="fas fa-venus-mars fa-2x text-purple"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Sexe</small>
                        <strong class="fs-5">
                            <?php
                            $sexe = $student['Sexe'] ?? 'N/A';
                            if ($sexe == 'M' || $sexe == 'Homme') {
                                echo '<i class="fas fa-mars me-1"></i>Homme';
                            } elseif ($sexe == 'F' || $sexe == 'Femme') {
                                echo '<i class="fas fa-venus me-1"></i>Femme';
                            } else {
                                echo $sexe;
                            }
                            ?>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inscription Information Card -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <h5 class="mb-0 text-white">
            <i class="fas fa-graduation-cap me-2"></i>
            Inscription Académique
        </h5>
    </div>
    <div class="card-body p-4">
        <?php if (empty($inscription_data['Filiere']) && empty($inscription_data['Annee'])): ?>
            <div class="empty-state py-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5>Aucune inscription trouvée</h5>
                <p class="text-muted">Aucune information d'inscription disponible pour cet étudiant.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <!-- Filière Card -->
                <div class="col-md-6">
                    <div class="info-card p-4 border rounded-3 h-100" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 me-3" style="background: rgba(102, 126, 234, 0.2);">
                                <i class="fas fa-book-open fa-2x text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Filière</small>
                                <h4 class="mb-0"><?= htmlspecialchars($inscription_data['Filiere'] ?? 'Non spécifiée') ?></h4>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 8px 16px;">
                                <i class="fas fa-check-circle me-1"></i>Inscrit
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Année Card -->
                <div class="col-md-6">
                    <div class="info-card p-4 border rounded-3 h-100" style="background: linear-gradient(135deg, rgba(240, 147, 251, 0.1) 0%, rgba(245, 87, 108, 0.1) 100%);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 me-3" style="background: rgba(240, 147, 251, 0.2);">
                                <i class="fas fa-calendar-alt fa-2x text-danger"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Année d'études</small>
                                <h4 class="mb-0"><?= htmlspecialchars($inscription_data['Annee'] ?? 'Non spécifiée') ?></h4>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 8px 16px;">
                                <i class="fas fa-graduation-cap me-1"></i>Année universitaire 2024/2025
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Academic Status Summary -->
            <div class="alert alert-info border-0 mt-4" style="background: linear-gradient(135deg, rgba(52, 152, 219, 0.1) 0%, rgba(41, 128, 185, 0.1) 100%); border-left: 4px solid #3498db !important;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x text-info me-3"></i>
                    <div>
                        <h6 class="mb-1"><strong>Statut de l'inscription</strong></h6>
                        <p class="mb-0">
                            Vous êtes inscrit(e) en <strong><?= htmlspecialchars($inscription_data['Filiere'] ?? 'N/A') ?></strong> 
                            - <strong><?= htmlspecialchars($inscription_data['Annee'] ?? 'N/A') ?></strong> 
                            pour l'année universitaire 2024/2025.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Back Button -->
<div class="text-center mt-4">
    <a href="dashboard.php" class="btn btn-outline-primary btn-lg px-5">
        <i class="fas fa-arrow-left me-2"></i>
        Retour au tableau de bord
    </a>
</div>

<style>
.icon-box {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-card {
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.text-purple {
    color: #9b59b6;
}

.bg-gradient {
    border: none;
}
</style>

<?php include 'includes/layout_footer.php'; ?>