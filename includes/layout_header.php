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
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <button class="close-sidebar" id="closeSidebar">&times;</button>
        
        <!-- Logo -->
        <div class="sidebar-logo">
            <img src="images/logo.png" alt="Logo" onerror="this.style.display='none'">
        </div>
        
        <!-- User Profile -->
        <div class="user-profile">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($student['Prenom']) ?>&background=0066ff&color=fff" alt="Avatar">
            <div class="user-name"><?= htmlspecialchars($student['Prenom']) ?> <?= htmlspecialchars($student['Nom']) ?></div>
            <div class="user-code"><?= htmlspecialchars($student['apoL_a01_code']) ?></div>
        </div>

        <!-- Navigation -->
        <nav class="nav-section">
            <h6>Menu Principal</h6>
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
           
            <a href="logout.php" class="nav-link logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="content">
        <!-- Mobile Header -->
        <div class="mobile-header">
            <button id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            <span><?= $page_title ?? 'Portail Étudiant' ?></span>
        </div>