<?php
session_start();
if(isset($_SESSION['username'])) {
    header('Location: app/user/dashboard.php');
}

require_once 'inc/db.php';
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM students WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $count = $stmt->rowCount();
    if($count > 0) {
        $user = $stmt->fetch();
        if(password_verify($password, $user['PASSWORD'])) {
            $_SESSION['username'] = $username;
            $_SESSION['permission'] = 'student';
            header('Location: app/user/dashboard.php');
        } else {
            header("Location: index.php?wrongPassword=1");
        }
    } else {
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count > 0) {
            $user = $stmt->fetch();
            if(password_verify($password, $user['PASSWORD'])) {
                $_SESSION['username'] = $username;
                if ($user['IS_ADMIN'] == 1) {
                    $_SESSION['permission'] = 'admin';
                    header('Location: app/admin/dashboard.php');
                } else {
                    $_SESSION['permission'] = 'teacher';
                    header('Location: app/teacher/dashboard.php');
                }
            } else {
                header("Location: index.php?wrongPassword=1");
            }
        } else {
            header("Location: index.php?wrongUsername=1");
        }
    }
}

if(isset($_GET['wrongPassword'])) {
    if($_GET['wrongPassword'] == 1) {
        echo '<div class="alert alert-danger" role="alert">Das Passwort ist falsch!</div>';
    }
}

if(isset($_GET['wrongUsername'])) {
    if($_GET['wrongUsername'] == 1) {
        echo '<div class="alert alert-danger" role="alert">Der Benutzername ist falsch!</div>';
    }
}

if(isset($_GET['already_handed_in'])) {
    if($_GET['already_handed_in'] == 1) {
        echo '<div class="alert alert-danger" role="alert">Du hast bereits abgegeben! Warte bis der Lehrer die Arbeiten eingesammelt hat um dich anmelden zu können. Falls das Problem bestehen bleibt melde dich bei deinem Lehrer!</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClassSystem | Login</title>
    <link rel="icon" href="app/content/svg/school.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="bg-secondary">
<!-- login form in bootstrap with a logo above it and copyrith below -->
    <div class="container bg-light border rounded">
        <div class="row">
            <div class="col-12 text-center">
                <img src="app/content/svg/school.svg" alt="ClassSystem" width="100" height="100">
                <h1>ClassSystem</h1>
            </div>
        </div>
        <div class="row border rounded">
            <div class="col-12">
                <form action="index.php" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Benutzername</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Passwort</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="login">Anmelden</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <p>&copy; 2023 Fabian Ecken</p>
            </div>
        </div>
    </div>
</body>
</html>