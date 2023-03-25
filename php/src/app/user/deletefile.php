<?php
//delete file from user folder
session_start();
if(!isset($_SESSION["username"])) {
    header("Location: ../index.php");
    exit();
}

if(isset($_POST["submit"])) {
    $file = $_POST["file"];
    $fileDestination = '../users/' . $_SESSION["username"] . '/' . $file;
    unlink($fileDestination);
    header("Location: dashboard.php?delete=success");
}