<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Fetch user from database
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // Save login session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Redirect user back to intended page if set
        if (isset($_SESSION['redirect_after_login'])) {
            $redirect = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            header("Location: $redirect");
            exit;
        } else {
            header("Location: home.php");
            exit;
        }
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        <?php if(file_exists("nav_bar.php")): ?>
        body { padding-top: 60px; } /* Adjust if nav bar is fixed */
        <?php endif; ?>
        .login-container {
            max-width: 400px;
            margin: 60px auto;
            padding: 25px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #28a745;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-container button:hover {
            background-color: #218838;
        }
        .login-container .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .login-container p {
            text-align: center;
            margin-top: 15px;
        }
        .login-container a {
            color: #28a745;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .admin-login {
            display: block;
            margin: 20px auto 0;
            text-align: center;
        }
        .admin-login a {
            display: inline-block;
            padding: 8px 15px;
            background-color: #ffc107;
            color: #000;
            border-radius: 3px;
            font-weight: bold;
        }
        .admin-login a:hover {
            background-color: #e0a800;
            color: #fff;
        }
    </style>
</head>
<body>

<?php
// Include navigation bar if exists
if (file_exists("nav_bar.php")) {
    include "nav_bar.php";
}
?>

<div class="login-container">
    <h2><i class="fa fa-user"></i> User Login</h2>

    <?php if(isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="login">
            <i class="fa fa-sign-in-alt"></i> Login
        </button>
    </form>

    <div class="admin-login">
        <a href="admin_login.php"><i class="fa fa-lock"></i> Admin Login</a>
    </div>

    <p>
        Don't have an account? 
        <a href="register.php">Register here</a>
    </p>
</div>

</body>
</html>





