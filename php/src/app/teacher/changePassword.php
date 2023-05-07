<?php
session_start();
if(!isset($_SESSION["username"])) {
    header("Location: ../../index.php");
    exit();
}
if($_SESSION["permission"] == "student") {
    header("Location: ../../index.php");
    exit();
}

if(isset($_POST['change'])) {
    $new_password = $_POST['new_password'];
    $new_password_repeat = $_POST['new_password_repeat'];
    $change_password = 0;

    require_once '../../inc/db.php';
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE USERNAME = :username");
    $stmt->bindParam(':username', $_SESSION["username"]);
    $stmt->execute();
    $count = $stmt->rowCount();

    if($count > 0) {
        $user = $stmt->fetch();
        if($new_password == $new_password_repeat) {
            $hash = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE teachers SET PASSWORD = :password, CHANGE_PASSWORD = :change_password WHERE USERNAME = :username");
            $stmt->bindParam(':password', $hash);
            $stmt->bindParam(':change_password', $change_password);
            $stmt->bindParam(':username', $_SESSION["username"]);
            $stmt->execute();
            header("Location: changePassword.php?succes=1");
        } else {
            header("Location: changePassword.php?error=1");
        }
    }
}

if(isset($_GET['succes'])) {
    if($_GET['succes'] == 1) {
        echo '<div class="alert alert-success" role="alert">Passwort wurde erfolgreich geändert!</div>';
    }
}

if(isset($_GET['error'])) {
    if($_GET['error'] == 1) {
        echo '<div class="alert alert-danger" role="alert">Passwörter stimmen nicht überein!</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClassSystem | Passwort ändern</title>
    <link rel="icon" href="app/content/svg/school.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="bg-secondary">
    <div class="container gap-3 bg-light border rounded">
        <div class="row gap-3 p-2">
            <div class="border rounded col">
                <h1>Passwort ändern</h1>
                <br>
                <form action="changePassword.php" method="post">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_repeat" class="form-label">Repeat New Password</label>
                        <input type="password" class="form-control" id="new_password_repeat" name="new_password_repeat" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="change">Change Password</button>
                </form>
                <br>
                <button class="btn btn-primary mb-2" onclick="window.location.href='dashboard.php'">Back</button>
                <br>
            </div>
        </div>
</body>
</html>