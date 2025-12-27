<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['student'])) {
    header("Location: dashboard.php");
    exit();
}

require_once 'db.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $apogee = trim($_POST['apogee']);
    $birthdate_input = $_POST['birthdate'];
    
    // Convert input date (yyyy-mm-dd) to database format (dd/mm/yyyy)
    $birthdate = date('d/m/Y', strtotime($birthdate_input));
    
    // Updated query to use new column names
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }
        
        .university-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .university-logo h3 {
            color: white;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .card-header-custom h4 {
            margin: 0;
            font-weight: 600;
        }
        
        .card-header-custom p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }
        
        .login-body {
            padding: 40px 30px;
        }
        
        .instructions-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border-right: 4px solid var(--accent-color);
        }
        
        .instructions-card h6 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .instructions-card ol {
            margin: 0;
            padding-right: 20px;
        }
        
        .instructions-card li {
            margin-bottom: 8px;
            color: #555;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label-dual {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .form-label-dual .label-ar {
            font-size: 1rem;
        }
        
        .form-label-dual .label-fr {
            font-size: 0.9rem;
            color: #7f8c8d;
        }
        
        .form-control-custom {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control-custom:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
            outline: none;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, var(--accent-color) 0%, #2980b9 100%);
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }
        
        .footer-text {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            color: white;
            font-size: 0.9rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }
        
        .alert-custom {
            border-radius: 10px;
            border: none;
            padding: 15px;
            margin-bottom: 20px;
            border-right: 4px solid var(--danger-color);
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- University Title -->
        <div class="university-logo">
            <h3>
                <i class="fas fa-university me-2"></i>
                كلية العلوم القانونية والسياسية سطات
            </h3>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <div class="card-header-custom">
                <h4>
                    <i class="fas fa-user-circle me-2"></i>
                    تسجيل الدخول
                </h4>
                <p>Portail Étudiant - Connexion</p>
            </div>

            <div class="login-body">
                <!-- Instructions -->
                <div class="instructions-card">
                    <h6>
                        <i class="fas fa-info-circle me-2"></i>
                        دليل الاستعمال
                    </h6>
                    <ol>
                        <li>ادخل رقم الأبوجي APOGEE</li>
                        <li>ادخل تاريخ الازدياد على الشكل التالي: السنة/الشهر/اليوم (مثال: 1999-02-25)</li>
                        <li>اضغط على زر "الدخول"</li>
                    </ol>
                </div>

                <!-- Error Alert -->
                <?php if ($error): ?>
                <div class="alert alert-danger alert-custom">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" action="">
                    <div class="form-group">
                        <div class="form-label-dual">
                            <span class="label-ar">رقم أبوجي</span>
                            <span class="label-fr">Numéro APOGÉE</span>
                        </div>
                        <input type="text" 
                               class="form-control form-control-custom" 
                               name="apogee" 
                               placeholder="ادخل رقم الأبوجي"
                               required 
                               autofocus>
                    </div>

                    <div class="form-group">
                        <div class="form-label-dual">
                            <span class="label-ar">تاريخ الازدياد</span>
                            <span class="label-fr">Date de naissance</span>
                        </div>
                        <input type="date" 
                               class="form-control form-control-custom" 
                               name="birthdate" 
                               required>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        دخول - Connexion
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-text">
            <i class="fas fa-copyright me-1"></i>
            كلية العلوم القانونية والسياسية سطات - 2024
        </div>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>