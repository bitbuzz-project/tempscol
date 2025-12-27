<?php
session_start();
if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$student = $_SESSION['student'];
$page_title = "Tableau de bord";
$current_page = "dashboard";

include 'includes/layout_header.php';
?>

<div class="page-header">
    <h1 class="page-title">Tableau de bord</h1>
    <p class="page-subtitle">Année universitaire 2024/2025</p>
</div>

<!-- Info Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card hover-card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="icon-box me-3">
                        <i class="fas fa-user-check fa-lg text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-2">Mes Inscriptions</h5>
                        <p class="text-muted small mb-3">Informations administratives et filière</p>
                        <?php if (isset($student['Filiere']) && !empty($student['Filiere'])): ?>
                        <span class="badge bg-light text-dark border"><?= htmlspecialchars($student['Filiere']) ?></span>
                        <?php endif; ?>
                        <div class="mt-3">
                            <a href="admin_situation.php" class="btn btn-sm btn-outline-primary">
                                Voir détails <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card hover-card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="icon-box me-3">
                        <i class="fas fa-book-open fa-lg text-success"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-2">Situation Pédagogique</h5>
                        <p class="text-muted small mb-3">Modules inscrits et groupes</p>
                        <span class="badge bg-light text-dark border">
                            <i class="fas fa-book me-1"></i>Modules & Groupes
                        </span>
                        <div class="mt-3">
                            <a href="pedagogic_situation.php" class="btn btn-sm btn-outline-success">
                                Voir détails <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Student Info -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-user me-2"></i>Informations Étudiant
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-4">
                <small class="text-muted d-block mb-1">Nom complet</small>
                <strong><?= htmlspecialchars($student['Prenom']) ?> <?= htmlspecialchars($student['Nom']) ?></strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block mb-1">Code Apogée</small>
                <strong><?= htmlspecialchars($student['apoL_a01_code']) ?></strong>
            </div>
            <?php if (isset($student['Filiere'])): ?>
            <div class="col-md-4">
                <small class="text-muted d-block mb-1">Filière</small>
                <strong><?= htmlspecialchars($student['Filiere']) ?></strong>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/layout_footer.php'; ?>