<?php
session_start();
if(!isset($_SESSION["username"])) {
    header("Location: ../../index.php");
    exit();
}
if($_SESSION["permission"] != "admin") {
    header("Location: ../../index.php");
    exit();
}

$file_name = "Abgaben";
$files = array();
$files = scandir('../users');
$zip = new ZipArchive();
$zip->open($file_name . '.zip', ZipArchive::CREATE);
for ($i = 2; $i < count($files); $i++) {
    $files2 = scandir('../users/' . $files[$i]);
    for ($j = 2; $j < count($files2); $j++) {
        $zip->addFile('../users/' . $files[$i] . '/' . $files2[$j], $files[$i] . '/' . $files2[$j]);
    }
}
$zip->close();
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $file_name . '.zip');
header('Content-Length: ' . filesize($file_name . '.zip'));
readfile($file_name . '.zip');
unlink($file_name . '.zip');
header("Location: dashboard.php");
?>