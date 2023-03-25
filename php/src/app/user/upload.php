<?php
session_start();
if(!isset($_SESSION["username"])) {
    header("Location: ../index.php");
    exit();
}
if(isset($_POST["submit"])) {
    $file = $_FILES["file"];
    $fileName = $file["name"];
    $fileTmpName = $file["tmp_name"];
    $fileSize = $file["size"];
    $fileError = $file["error"];
    $fileType = $file["type"];
    $fileExt = explode(".", $fileName);
    $fileActualExt = strtolower(end($fileExt));

    require_once '../../inc/db.php';
    $allowed = array("png", "zip", "docx", "pdf");
    if(in_array($fileActualExt, $allowed)) {
        if($fileError === 0) {
            if($fileSize < 1000000) {
                $fileNameNew = $fileName;
                $fileDestination = '../users/' . $_SESSION["username"] . '/' . $fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);
                header("Location: dashboard.php?upload=success");
            } else {
                echo "Die Datei ist zu groß!";
            }
        } else {
            echo "Es ist ein Fehler aufgetreten!";
        }
    } else {
        echo "Du kannst keine Dateien dieses Typs hochladen!";
    }
}
?>