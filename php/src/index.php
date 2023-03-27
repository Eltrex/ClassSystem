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
            $_SESSION['permission'] = 'user';
            header('Location: app/user/dashboard.php');
        } else {
            echo 'Das Passwort ist falsch!';
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
                $_SESSION['permission'] = 'admin';
                header('Location: app/admin/dashboard.php');
            } else {
                echo 'Das Passwort ist falsch!';
            }
        } else {
            echo 'Der Benutzer existiert nicht!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClassSystem</title>
    <link rel="icon" href="app/content/svg/school.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/signin.css">
</head>
<body class="text-center">
    
    <main class="form-signin">
      <form action="index.php" method="post">
        <img class="mb-4" src="app/content/svg/school.svg" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">Bitte anmelden</h1>
    
        <div class="form-floating">
          <input name="username" type="text" class="form-control" id="floatingInput" placeholder="vorname.nachname">
          <label for="floatingInput">Benutzername</label>
        </div>
        <div class="form-floating">
          <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
          <label for="floatingPassword">Password</label>
        </div>
    
        <button class="w-100 btn btn-lg btn-primary" name="login" type="submit">Anmelden</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2023</p>
      </form>
    </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
</html>