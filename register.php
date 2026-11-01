<?php
session_start();
include "db.php"; // Include database connection

$error = "";

if (isset($_POST['register'])) {
    // Collect and trim form data
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $address  = trim($_POST['address']);
    $password = trim($_POST['password']); // ⚠️ Plain password (you can hash with password_hash)

    // Server-side validation
    if (!preg_match("/^[A-Za-z\s]+$/", $fullname)) {
        $error = "Full name can only contain letters and spaces.";
    } elseif (!preg_match("/^[A-Za-z0-9_]{3,20}$/", $username)) {
        $error = "Username must be 3-20 characters and can contain letters, numbers, and underscores.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($phone != "" && !preg_match("/^9[0-9]{9}$/", $phone)) {
        $error = "Phone number must start with 9 and be 10 digits.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Check if username or email already exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' OR email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username or Email already exists.";
        }
    }

    // Insert into database if no error
    if ($error == "") {
        $sql = "INSERT INTO users (fullname, username, email, phone, address, password)
                VALUES ('$fullname', '$username', '$email', '$phone', '$address', '$password')"; // Plain password
        if (mysqli_query($conn, $sql)) {
            $user_id = mysqli_insert_id($conn);

            // Auto-login after registration
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;

            // Redirect to intended page if set
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
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .register-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #28a745;
        }
        .register-container input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .register-container button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .register-container button:hover {
            background-color: #218838;
        }
        .register-container .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .register-container p {
            text-align: center;
            margin-top: 10px;
        }
        .register-container a {
            color: #28a745;
            text-decoration: none;
        }
        .register-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php include "nav_bar.php"; ?>

<div class="register-container">
    <h2><i class="fa fa-user-plus"></i> Register</h2>

    <?php if($error != "") { ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>

    <form method="post" id="registerForm" onsubmit="return validateForm()">
        <input type="text" name="fullname" id="fullname" placeholder="Full Name" required><br>
        <input type="text" name="username" id="username" placeholder="Username" required><br>
        <input type="email" name="email" id="email" placeholder="Email" required><br>
        <input type="text" name="phone" id="phone" placeholder="Phone Number"><br>
        <input type="text" name="address" id="address" placeholder="Address"><br>
        <input type="password" name="password" id="password" placeholder="Password" required><br>
        <button type="submit" name="register">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

<script>
function validateForm() {
    let fullname = document.getElementById('fullname').value.trim();
    let username = document.getElementById('username').value.trim();
    let email = document.getElementById('email').value.trim();
    let phone = document.getElementById('phone').value.trim();
    let password = document.getElementById('password').value.trim();

    if (!/^[A-Za-z\s]+$/.test(fullname)) {
        alert("Full name can only contain letters and spaces.");
        return false;
    }
    if (!/^[A-Za-z0-9_]{3,20}$/.test(username)) {
        alert("Username must be 3-20 characters and can contain letters, numbers, and underscores.");
        return false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }
    if (phone !== "" && !/^9[0-9]{9}$/.test(phone)) {
        alert("Phone number must start with 9 and be 10 digits.");
        return false;
    }
    if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return false;
    }
    return true;
}
</script>

</body>
</html>
