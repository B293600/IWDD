<?php
// Creates output buffering and PHP session handling, processes form submission, store user information in session
ob_start();
session_start();
// Logs into MySQL database, contains PDO 
require_once 'login.php';

// Create error message variable
$error = "";

// Handles form submission, runs when user submits the login form (or skips)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Validates input and stores user details in the session
    if (isset($_POST['login'])) {
        $fn = trim($_POST['fn'] ?? '');
        $sn = trim($_POST['sn'] ?? '');

        if (!empty($fn)) {

            // Stores user information in session and redirect to homepage
            $_SESSION['fn'] = $fn;
            $_SESSION['sn'] = $sn;
            $_SESSION['user'] = $fn . ' ' . $sn;

            header("Location: index.php");
            exit();
// Sets error message
        } else {
            $error = "First name is required.";
        }
    }
// Handles skip button, assignes a default Guest user
    if (isset($_POST['skip'])) {
        $_SESSION['user'] = "Guest";

        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link rel="stylesheet" href="style_sheet.css">
<!-- Contains layout and styling of login page -->
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            width: 320px;
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }

        .login-card input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #111;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>
<!-- Main login interface container -->
<div class="login-container">
    <div class="login-card">
        <h2>Login</h2>
        <div class="subtitle">Enter your name to begin</div>

        <?php if (!empty($error)) : ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
<!-- Creates form -->
        <form action="web_login.php" method="post">
            <input type="text" name="fn" placeholder="First Name">
            <input type="text" name="sn" placeholder="Second Name">

            <input type="submit" name="login" value="Go" class="btn">
            <input type="submit" name="skip" value="Skip" class="btn btn-secondary">
        </form>
    </div>
</div>

</body>
</html>

<?php
// Flush output buffer and send content to the browser
ob_end_flush();
?>
