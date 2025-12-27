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

<!-- Page Header -->
<div class="page-header mb-4">
    <h1 class="page-title mb-1">Tableau de bord</h1>
    <p class="text-muted mb-0">Portail Étudiant - Année universitaire 2024/2025</p>
</div>

<!-- Student Info Bar -->
<div class="alert alert-light border mb-4" style="border-left: 4px solid #3498db !important;">
    <div class="row align-items-center">
        <div class="col-md-9">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                <div>
                    <small class="text-muted d-block">Étudiant</small>
                    <strong><?= htmlspecialchars($student['Prenom']) ?> <?= htmlspecialchars($student['Nom']) ?></strong>
                </div>
                <div class="vr d-none d-md-block"></div>
                <div>
                    <small class="text-muted d-block">Code Apogée</small>
                    <strong><?= htmlspecialchars($student['apoL_a01_code']) ?></strong>
                </div>
                <?php if (isset($student['Filiere']) && !empty($student['Filiere'])): ?>
                <div class="vr d-none d-md-block"></div>
                <div>
                    <small class="text-muted d-block">Filière</small>
                    <strong><?= htmlspecialchars($student['Filiere']) ?></strong>
                </div>
                <?php endif; ?>
                <?php if (isset($student['Annee']) && !empty($student['Annee'])): ?>
                <div class="vr d-none d-md-block"></div>
                <div>
                    <small class="text-muted d-block">Année</small>
                    <strong><?= htmlspecialchars($student['Annee']) ?></strong>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-3 text-md-end mt-3 mt-md-0">
            <small class="text-muted d-block">Session</small>
            <strong>Automne 2024/2025</strong>
        </div>
    </div>
</div>

<!-- Main Navigation Cards -->
<div class="row g-4 mb-4">
    <!-- Mes Inscriptions -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100 hover-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start">
                    <div class="icon-box me-3">
                        <i class="fas fa-user-check fa-2x text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-2">Mes Inscriptions</h5>
                        <p class="card-text text-muted small mb-3">
                            Consultez vos informations d'inscription administrative et votre filière d'études.
                        </p>
                        <?php if (isset($student['Filiere']) && !empty($student['Filiere'])): ?>
                        <div class="mb-3">
                            <span class="badge bg-light text-dark border">
                                <?= htmlspecialchars($student['Filiere']) ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        <a href="admin_situation.php" class="btn btn-outline-primary btn-sm">
                            Voir les détails <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Situation Pédagogique -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100 hover-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start">
                    <div class="icon-box me-3">
                        <i class="fas fa-book-open fa-2x text-success"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-2">Situation Pédagogique</h5>
                        <p class="card-text text-muted small mb-3">
                            Consultez la liste de vos modules inscrits et vos groupes par semestre.
                        </p>
                        <div class="mb-3">
                            <span class="badge bg-light text-dark border">
                                <i class="fas fa-book me-1"></i>Modules & Groupes
                            </span>
                        </div>
                        <a href="pedagogic_situation.php" class="btn btn-outline-success btn-sm">
                            Voir les détails <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Information Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border h-100">
            <div class="card-body text-center p-4">
                <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                <h6 class="card-title mb-2">Calendrier</h6>
                <p class="text-muted small mb-0">Session d'Automne 2024/2025</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border h-100">
            <div class="card-body text-center p-4">
                <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                <h6 class="card-title mb-2">Parcours</h6>
                <p class="text-muted small mb-0">
                    <?= htmlspecialchars($student['Annee'] ?? 'En cours') ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border h-100">
            <div class="card-body text-center p-4">
                <i class="fas fa-university fa-3x text-muted mb-3"></i>
                <h6 class="card-title mb-2">Faculté</h6>
                <p class="text-muted small mb-0">Sciences Juridiques</p>
            </div>
        </div>
    </div>
</div>



<!-- Notice -->
<div class="alert alert-light border mt-4">
    <div class="row">
        <div class="col-md-6 mb-3 mb-md-0">
            <h6 class="mb-2">إعلان مهم</h6>
            <p class="small mb-0 text-muted" dir="rtl">
                يمكنك الآن الاطلاع على وضعيتك الإدارية والبيداغوجية من خلال هذه المنصة.
                في حالة وجود أي استفسار، يرجى التواصل مع الإدارة.
            </p>
        </div>
        <div class="col-md-6">
            <h6 class="mb-2">Annonce Importante</h6>
            <p class="small mb-0 text-muted">
                Vous pouvez maintenant consulter votre situation administrative et pédagogique via cette plateforme.
                Pour toute question, veuillez contacter l'administration.
            </p>
        </div>
    </div>
</div>

<style>
/* Divider */
.vr {
    width: 1px;
    background-color: #dee2e6;
    opacity: 1;
    align-self: stretch;
}

/* Icon box */
.icon-box {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border-radius: 0.5rem;
}

/* Hover card effect */
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
}

/* Quick link hover */
.quick-link {
    transition: all 0.2s ease;
}

.quick-link:hover {
    background-color: #f8f9fa;
    border-color: #3498db !important;
}

/* Clean shadows */
.shadow-sm {
    box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.05) !important;
}

/* Cards */
.card {
    border-radius: 0.5rem;
}

/* Page header */
.page-header {
    border-bottom: none;
    padding-bottom: 0;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #2c3e50;
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 0.35rem 0.75rem;
}

/* Alert */
.alert {
    border-radius: 0.5rem;
}

/* Buttons */
.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.btn-outline-primary,
.btn-outline-success {
    border-width: 1.5px;
}

.btn-outline-primary:hover,
.btn-outline-success:hover {
    transform: translateX(2px);
}
</style>

<?php include 'includes/layout_footer.php'; ?>