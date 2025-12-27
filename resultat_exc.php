<?php
// Show PHP errors (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$student = $_SESSION['student'];

require_once 'db.php';

if (!isset($student['apoL_a01_code'])) {
    die("Error: 'apoL_a01_code' is not set.");
}

$code_apogee = $student['apoL_a01_code'];

// === SESSION D’AUTOMNE ===
$query_autumn = "
    SELECT nom_module, note, prof
    FROM notes_exc
    WHERE apoL_a01_code = ?
";
$stmt_autumn = $conn->prepare($query_autumn);
if (!$stmt_autumn) {
    die("Erreur SQL Autumn: " . $conn->error);
}
$stmt_autumn->bind_param("s", $code_apogee);
$stmt_autumn->execute();
$result_autumn = $stmt_autumn->get_result();
$notes_autumn = $result_autumn->fetch_all(MYSQLI_ASSOC);
$stmt_autumn->close();

// === SESSION RATTRAPAGE ===
$query_ratt = "
    SELECT nom_module, note, prof
    FROM notes_exc_ratt
    WHERE apoL_a01_code = ?
";
$stmt_ratt = $conn->prepare($query_ratt);
if (!$stmt_ratt) {
    die("Erreur SQL Rattrapage: " . $conn->error);
}
$stmt_ratt->bind_param("s", $code_apogee);
$stmt_ratt->execute();
$result_ratt = $stmt_ratt->get_result();
$notes_ratt = $result_ratt->fetch_all(MYSQLI_ASSOC);
$stmt_ratt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            height: 100vh;
            overflow: hidden;
        }
        .wrapper {
            display: flex;
            flex-direction: row;
            height: 100%;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            flex-shrink: 0;
            transition: transform 0.3s ease-in-out;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            margin-bottom: 10px;
            display: block;
            padding: 10px 15px;
            border-radius: 4px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #f8f9fa;
        }
        .table {
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                transform: translateX(-100%);
                z-index: 10;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .content {
                padding: 15px;
            }
        }
        .close-sidebar {
            display: none;
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            background: none;
            border: none;
            color: white;
        }
        .sub-text {
            font-size: 0.8em;
            display: block;
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .close-sidebar {
                display: block;
            }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="close-sidebar" id="closeSidebar">&times;</button>
        <div class="px-3 text-center">
            <img src="images/logo.png" alt="Logo" class="img-fluid my-3" style="max-width: 150px;">
        </div>
        <div class="card mx-3 mb-4">
            <div class="card-body text-center">
                <h5 style="color:black" class="card-title mb-2"><?= htmlspecialchars($student['apoL_a03_prenom']) . " " . htmlspecialchars($student['apoL_a02_nom']) ?></h5>
                <p class="card-text text-muted">Code Apogee: <?= htmlspecialchars($student['apoL_a01_code']) ?></p>
            </div>
        </div>
        <div class="d-grid gap-2 px-3">
            <a href="admin_situation.php" class="btn btn-secondary btn-block">Mes inscriptions</a>
            <a href="pedagogic_situation.php" class="btn btn-secondary btn-block">Situation pédagogique</a>
            <a href="resultat.php" class="btn btn-secondary btn-block">
                Resultat <br><span class="sub-text">(Licence Fondamentale)</span>
            </a>
            <a href="resultat_ratt.php" class="btn btn-secondary btn-block">
                Resultat Session Rattrapage <br><span class="sub-text">(Licence Fondamentale)</span>
            </a>
            <a href="resultat_exc.php" class="btn btn-secondary btn-block">
                Resultat <br><span class="sub-text">(Centre D'excellence)</span>
            </a>
            <a href="logout.php" class="btn btn-danger btn-block">Se déconnecter</a>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Navbar for mobile -->
        <nav class="navbar navbar-dark bg-dark d-md-none">
            <div class="container-fluid">
                <button class="btn btn-outline-light" id="toggleSidebar">☰ Menu</button>
                <span class="navbar-brand">Tableau de bord</span>
            </div>
        </nav>

        <!-- Résultat Session d'automne -->
 <!-- Toggle between sessions using buttons -->
<div class="d-flex align-items-center justify-content-between mt-4 flex-wrap">
    <h2 class="mb-2">Résultats - Centre d'Excellence</h2>
    <div class="btn-group" role="group" aria-label="Session Toggle">
        <button type="button" id="btnAutumn" class="btn btn-primary active">Session d'automne</button>
        <button type="button" id="btnRatt" class="btn btn-outline-primary">Session de printemps
</button>
    </div>
</div>
<b>Année universitaire : 2024-2025</b>

<!-- Table container -->
<table class="table table-striped table-bordered mt-3">
    <thead class="table-dark">
        <tr>
            <th>Nom du Module</th>
            <th>Note</th>
            <th>Professeur</th>
        </tr>
    </thead>
    <tbody id="resultBody">
        <?php if (empty($notes_autumn)): ?>
            <tr><td colspan="3" class="text-center text-muted">لا توجد نتائج المرجو الإعادة لاحقا</td></tr>
        <?php else: ?>
            <?php foreach ($notes_autumn as $note): ?>
                <tr>
                    <td><?= htmlspecialchars($note['nom_module']) ?></td>
                    <td><?= htmlspecialchars($note['note']) ?></td>
                    <td><?= htmlspecialchars($note['prof']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
    </div>
</div>
<!-- JavaScript to switch table content -->
<script>
    const btnAutumn = document.getElementById('btnAutumn');
    const btnRatt = document.getElementById('btnRatt');
    const resultBody = document.getElementById('resultBody');

    const notesAutumn = <?= json_encode($notes_autumn) ?>;
    const notesRatt = <?= json_encode($notes_ratt) ?>;

    function updateTable(data) {
        resultBody.innerHTML = '';
        if (data.length === 0) {
            resultBody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">لا توجد نتائج المرجو الإعادة لاحقا</td></tr>';
            return;
        }
        data.forEach(note => {
            resultBody.innerHTML += `
                <tr>
                    <td>${note.nom_module}</td>
                    <td>${note.note}</td>
                    <td>${note.prof}</td>
                </tr>
            `;
        });
    }

    btnAutumn.addEventListener('click', () => {
        btnAutumn.classList.add('btn-primary', 'active');
        btnAutumn.classList.remove('btn-outline-primary');
        btnRatt.classList.add('btn-outline-primary');
        btnRatt.classList.remove('btn-primary', 'active');
        updateTable(notesAutumn);
    });

    btnRatt.addEventListener('click', () => {
        btnRatt.classList.add('btn-primary', 'active');
        btnRatt.classList.remove('btn-outline-primary');
        btnAutumn.classList.add('btn-outline-primary');
        btnAutumn.classList.remove('btn-primary', 'active');
        updateTable(notesRatt);
    });
</script>
<script>
    const toggleSidebar = document.getElementById("toggleSidebar");
    const closeSidebar = document.getElementById("closeSidebar");
    const sidebar = document.getElementById("sidebar");

    toggleSidebar?.addEventListener("click", () => {
        sidebar.classList.toggle("show");
    });
    closeSidebar?.addEventListener("click", () => {
        sidebar.classList.remove("show");
    });
</script>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
