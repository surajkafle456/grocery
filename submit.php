<?php
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if($_POST['action'] === 'continue'){
        header("Location: home.php");
        exit;
    }
    if($_POST['action'] === 'payment'){
        header("Location: payment.php");
        exit;
    }
}
?>
