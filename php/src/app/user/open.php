<?php
// open a pdf file that is in a folder on the server
session_start();
if(!isset($_SESSION["username"])) {
    header("Location: ../../index.php");
    exit();
}

$file = $_POST["file"];
$fileDestination = '../users/#Task' . '/' . $file;
header('Content-Type: application/pdf');
header('Content-disposition: inline; filename=' . $file);
header('Content-Length: ' . filesize($fileDestination));
readfile($fileDestination);
header("Location: dashboard.php");
?>