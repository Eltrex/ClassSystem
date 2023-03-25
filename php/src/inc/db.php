<?php
// Verbindungsdaten zur DB:
$db_host = 'db';
$db_name = 'ClassSystem';
$db_user = 'root';
$db_password = 'toor';
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
?>