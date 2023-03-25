<?php
session_start();
if(!isset($_SESSION["username"])) {
    header("Location: ../../index.php");
    exit();
}

// download file
if(isset($_POST["submit"])) {
    $file = $_POST["file"];
    $fileDestination = '../users/#Task' . '/' . $file;
    header('Content-Type: application/octet-stream');
    header('Content-disposition: attachment; filename=' . $file);
    header('Content-Length: ' . filesize($fileDestination));
    readfile($fileDestination);
    header("Location: dashboard.php");
}
?>