<?php
session_start();
if (isset($_SESSION['student'])) {
    header("Location: dashboard.php");
    exit();
}

require_once 'db.php';
$error = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $apogee = trim($_POST['apogee']);
    $birthdate_input = $_POST['birthdate'];
    $birthdate = date('d/m/Y', strtotime($birthdate_input));
    
    $query = $conn->prepare("
        SELECT apoL_a01_code, Nom, Prenom, CIN, Filiere, Annee, `Date Naissance`, `Lieu Naissance`, Sexe, CNE 
        FROM students_base 
        WHERE apoL_a01_code = ? AND `Date Naissance` = ?
    ");
    
    if (!$query) {
        die("Query preparation failed: " . $conn->error);
    }
    
    $query->bind_param('ss', $apogee, $birthdate);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['student'] = $result->fetch_assoc();
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Code Apogée ou date de naissance incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كلية العلوم القانونية والسياسية سطات</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: var(--light-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }
        
        .login-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }
        
        .login-header {
            padding: 40px 32px 32px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }
        
        .login-header h3 {
            color: var(--text-dark);
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 8px;
        }
        
        .login-header p {
            margin: 0;
            color: var(--text-muted);
            font-size: 14px;
        }
        
        .login-body {
            padding: 32px;
        }
        
        .info-box {
            background: var(--light-bg);
            border-radius: var(--radius-md);
            padding: 16px;
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
        }
        
        .info-box h6 {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 13px;
        }
        
        .info-box ol {
            margin: 0;
            padding-right: 20px;
            font-size: 13px;
            color: var(--text-muted);
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }
        
        .input-icon .form-control {
            padding-left: 44px;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            border-radius: var(--radius-sm);
            border: none;
            background: var(--accent-color);
            color: var(--white);
            transition: var(--transition);
            margin-top: 8px;
        }
        
        .btn-login:hover {
            background: #0052cc;
            transform: translateY(-1px);
        }
        
        .footer-text {
            text-align: center;
            margin-top: 24px;
            color: var(--text-muted);
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <h3><i class="fas fa-university me-2"></i>تسجيل الدخول</h3>
                <p>Portail Étudiant - كلية العلوم القانونية والسياسية سطات</p>
            </div>

            <div class="login-body">
                <div class="info-box">
                    <h6><i class="fas fa-info-circle me-2"></i>دليل الاستعمال</h6>
                    <ol>
                        <li>ادخل رقم الأبوجي APOGEE</li>
                        <li>ادخل تاريخ الازدياد</li>
                        <li>اضغط على زر "الدخول"</li>
                    </ol>
                </div>

                <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">رقم أبوجي / Numéro APOGÉE</label>
                        <div class="input-icon">
                            <i class="fas fa-id-card"></i>
                            <input type="text" class="form-control" name="apogee" placeholder="ادخل رقم الأبوجي" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">تاريخ الازدياد / Date de naissance</label>
                        <div class="input-icon">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="date" class="form-control" name="birthdate" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>دخول - Connexion
                    </button>
                </form>
            </div>
        </div>

        <div class="footer-text">
            <i class="fas fa-copyright me-1"></i>كلية العلوم القانونية والسياسية سطات - 2024
        </div>
    </div>
</body>
</html>