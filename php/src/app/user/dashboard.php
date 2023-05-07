<?php
session_start();
if(!isset($_SESSION["username"])) {
    header("Location: ../../index.php");
    exit();
}

if($_SESSION["permission"] == "teacher") {
    header("Location: ../teacher/dashboard.php");
    exit();
}
if($_SESSION["permission"] == "admin") {
    header("Location: ../admin/dashboard.php");
    exit();
}

require_once '../../inc/db.php';

$handed_in = 1;
$stmt = $pdo->prepare("SELECT * FROM students WHERE HANDED_IN = :handed_in AND USERNAME = :username");
$stmt->bindParam(':handed_in', $handed_in);
$stmt->bindParam(':username', $_SESSION["username"]);
$stmt->execute();
$count = $stmt->rowCount();

if ($count > 0) {
    session_unset();
    session_destroy();
    header("Location: ../../index.php?already_handed_in=1");
    exit();
}


$change_password = 1;

$stmt = $pdo->prepare("SELECT * FROM students WHERE CHANGE_PASSWORD = :change_password AND USERNAME = :username");
$stmt->bindParam(':change_password', $change_password);
$stmt->bindParam(':username', $_SESSION["username"]);
$stmt->execute();
$count = $stmt->rowCount();

if ($count > 0) {
    header("Location: changePassword.php");
}

$Taskfolder = "#Aufgaben";
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="icon" href="app/content/svg/school.svg">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body class="text-center bg-secondary">
    
        <div class="container gap-3 bg-light bg-gradient">
            <div class="row gap-3 p-2">
                <div class="border rounded col">
                    <h1>Dashboard</h1>
                    <br>
                    <h3>Willkommen <?php echo $_SESSION["username"];?>!</h3>
                    <hr>
                    <br>
                    <button class="btn btn-primary mb-2" onclick="window.location.href='../../logout.php'">Logout</button>
                    <br>
                </div>
            </div>

            <div class="row gap-3 p-2">
                <div class="border rounded col text-center">
                    <br>
                    <h2>Aufgaben</h2>
                    <br>
                </div>
                <div class="border rounded col">
                    <div>
                        <h2>Arbeits Aufgaben</h2>
                        <hr>
                        <br>
                        <?php
                            $files = scandir('../users/' . $Taskfolder);
                            for ($i = 2; $i < count($files); $i++) {
                                echo '<a href="users/' . $Taskfolder . '/' . $files[$i] . '">' . $files[$i] . '</a>';
                                if(substr($files[$i], -4) == ".pdf") {
                                    echo '<form action="open.php" method="post">';
                                    echo '<input type="hidden" name="file" value="' . $files[$i] . '">';
                                    echo '<button class="btn btn-primary" type="submit" name="submit">Öffnen</button>';
                                    echo '</form>';
                                }
                                if(substr($files[$i], -4) != ".pdf") {
                                    echo '<form action="download.php" method="post">';
                                    echo '<input type="hidden" name="file" value="' . $files[$i] . '">';
                                    echo '<button class="btn btn-primary" type="submit" name="submit">Download</button>';
                                    echo '</form>';
                                }
                                echo '<hr>';
                            }
                        ?>
                        <hr>
                    </div>
                </div>
                <div class="border rounded col">
                    <h2>Abgabe</h2>
                    <hr>
                    <p>Sobald der Abgabe Button gedrückt wurde werden Sie abgemeldet!</p>
                    <button class="btn btn-danger" onclick="window.location.href='turnin.php'">Abgeben</button>
                </div>
            </div>

            <div class="row gap-3 p-2">
                <div class="border rounded col text-center">
                    <br>
                    <h2>Abgabe</h2>
                    <br>
                </div>
                <div class="border rounded col">
                    <h2>Upload der Abgabe</h2>
                    <hr>
                    <br>
                    <form class="form-control border-0" action="upload.php" method="post" enctype="multipart/form-data">
                        <input class="form-control" type="file" id="formfile" name="file">
                        <button class="btn btn-success mt-2" type="submit" name="submit">Hochladen</button>
                    </form>
                </div>
                <div class="border rounded col">
                    <h2>Hochgeladene Dateien</h2>
                    <hr>
                    <br>
                    <?php
                        $files = scandir('../users/' . $_SESSION["username"]);
                        for ($i = 2; $i < count($files); $i++) {
                            echo '<a href="users/' . $_SESSION["username"] . '/' . $files[$i] . '">' . $files[$i] . '</a>';
                            echo '<form action="deletefile.php" method="post">';
                            echo '<input type="hidden" name="file" value="' . $files[$i] . '">';
                            echo '<button class="btn btn-danger" type="submit" name="submit">Löschen</button>';
                            echo '</form>';
                            echo '<hr>';
                        }

                        if(count($files) == 2) {
                            echo 'Keine Dateien hochgeladen!';
                        }
                    ?>
                </div>
            </div>
            
            <div class="row gap-3 p-2">
                <div class="border rounded col bg-light">
                    <footer class="p-3">
                    <span class="text-muted">&copy; 2023 Fabian Ecken</span>
                    </footer>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
</html>