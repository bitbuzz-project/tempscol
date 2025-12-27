<?php
if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}
$student = $_SESSION['student'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Portail Étudiant' ?></title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="wrapper">
    <!-- Modern Sidebar -->
    <aside class="sidebar" id="sidebar">
        <button class="close-sidebar" id="closeSidebar">&times;</button>
        
        <!-- Logo Section -->
        <div class="text-center py-3 border-bottom border-white border-opacity-10">
            <img src="images/logo.png" alt="Logo" class="img-fluid" style="max-width: 120px;" 
                 onerror="this.style.display='none'">
        </div>
        
        <!-- User Profile Section -->
        <div class="user-profile">
            <img src="images/avatar-default.png" alt="Avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($student['Prenom']) ?>&background=2c3e50&color=fff'">
            <div class="user-name"><?= htmlspecialchars($student['Prenom']) ?> <?= htmlspecialchars($student['Nom']) ?></div>
            <div class="user-code">Apogée: <?= htmlspecialchars($student['apoL_a01_code']) ?></div>
            <?php if (isset($student['Filiere']) && !empty($student['Filiere'])): ?>
                <div class="user-filiere">
                    <small><i class="fas fa-graduation-cap me-1"></i><?= htmlspecialchars($student['Filiere']) ?></small>
                </div>
            <?php endif; ?>
        </div>

        <!-- Navigation Sections -->
        <nav class="nav-section">
            <h6>Navigation Principale</h6>
            <a href="dashboard.php" class="nav-link <?= ($current_page ?? '') === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-home"></i>
                <span>Tableau de bord</span>
            </a>
            <a href="admin_situation.php" class="nav-link <?= ($current_page ?? '') === 'admin' ? 'active' : '' ?>">
                <i class="fas fa-user-check"></i>
                <span>Mes Inscriptions</span>
            </a>
            <a href="pedagogic_situation.php" class="nav-link <?= ($current_page ?? '') === 'pedagogic' ? 'active' : '' ?>">
                <i class="fas fa-book-open"></i>
                <span>Situation Pédagogique</span>
            </a>

            <h6>Outils ( Bientôt disponible )</h6>
           
            <a href="logout.php" class="nav-link logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="content" id="mainContent">
        <!-- Mobile Header -->
        <div class="mobile-header d-md-none">
            <button class="btn btn-light" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            <span><?= $page_title ?? 'Portail Étudiant' ?></span>
        </div>