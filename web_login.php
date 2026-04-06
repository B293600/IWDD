<?php
ob_start();
session_start();
require_once 'login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- Global stylesheet -->
    <link rel="stylesheet" href="style_sheet.css">

    <!-- Page-specific styling only -->
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

        .login-card h2 {
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
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

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

<div class="login-container">
    <div class="login-card">
        <h2>Login</h2>
        <div class="subtitle">Enter your name to begin</div>

        <form action="web_login.php" method="post">
            <input type="text" name="fn" placeholder="First Name">
            <input type="text" name="sn" placeholder="Second Name">

            <!-- Uses global .btn class -->
            <input type="submit" name="login" value="Go" class="btn">

            <!-- Uses global + local override -->
            <input type="submit" name="skip" value="Skip" class="btn btn-secondary">
        </form>
    </div>
</div>

</body>
</html>

<?php
ob_end_flush();
?>
