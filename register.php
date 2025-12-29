<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $gender   = $_POST['gender'];
    $phone    = $_POST['phone'];
    $dob      = $_POST['dob'];

    // Encrypt password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data
    $sql = "INSERT INTO users (fullname, email, username, password, gender, phone, dob)
            VALUES ('$fullname', '$email', '$username', '$hashed_password', '$gender', '$phone', '$dob')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Registration successful');
                window.location.href='index.html';
              </script>";
    } else {
        echo "Error: Registration failed";
    }
}
?>
