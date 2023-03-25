<?php
session_start();
if(!isset($_SESSION["username"])) {
    header("Location: ../index.php");
    exit();
}
if($_SESSION["permission"] != "admin") {
    header("Location: ../index.php");
    exit();
}

if(isset($_POST["submit"])) {
    $subject = $_POST["subject"];
    $year = $_POST["year"];
    $newClass = $subject . $year;

    require_once '../../inc/db.php';
    $stmt = $pdo->prepare("SELECT * FROM classes WHERE SUBJECT = (:subject)");
    $stmt->bindParam(":subject", $newClass);
    $stmt->execute();
    $count = $stmt->rowCount();

    if($count == 0) {
        $stmt = $pdo->prepare("INSERT INTO classes (SUBJECT) VALUES (:subject)");
        $stmt->bindParam(":subject", $newClass);
        $stmt->execute();
        echo "Klasse erfolgreich hinzugefügt!";
    } else {
        echo "Diese Klasse existiert bereits!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="subject" placeholder="Fach">
        <input type="text" name="year" placeholder="Jahrgang">
        <input type="submit" name="submit" value="Hinzufügen">
    </form>
</body>
</html>