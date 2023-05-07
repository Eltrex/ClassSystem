<?php
session_start();
require_once '../../inc/db.php';
$stmt = $pdo->prepare("SELECT * FROM students WHERE USERNAME = :username");
$stmt->bindParam(':username', $_SESSION["username"]);
$stmt->execute();
$count = $stmt->rowCount();

$handed_in = 1;
if($count > 0) {
    $stmt = $pdo->prepare("UPDATE students SET HANDED_IN = :handed_in WHERE USERNAME = :username");
    $stmt->bindParam(':handed_in', $handed_in);
    $stmt->bindParam(':username', $_SESSION["username"]);
    $stmt->execute();
    header("Location: ../../logout.php");
}
?>