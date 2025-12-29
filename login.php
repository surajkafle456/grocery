<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {

            $_SESSION['username'] = $row['username'];
            header("Location: dashboard.php");

        } else {
            echo "<script>alert('Wrong password'); window.location.href='index.html';</script>";
        }

    } else {
        echo "<script>alert('User not found'); window.location.href='index.html';</script>";
    }
}
?>
