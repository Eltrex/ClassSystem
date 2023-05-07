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

if(!isset($_POST["class"])) {
    $_POST["class"] = "all";
}

require_once '../../inc/db.php';

$splitUsername = array();
$splitUsername = explode('.', $_SESSION["username"]);
$change_password = 1;

$stmt = $pdo->prepare("SELECT * FROM teachers WHERE CHANGE_PASSWORD = :change_password AND USERNAME = :username");
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

            <!--Anzeige und Hochladen von Aufgaben-->
            <div class="row gap-3 p-2">
                <div class="border rounded col text-center">
                    <br>
                    <h2>Aufgaben</h2>
                    <br>
                </div>
                <div class="border rounded col">
                    <br>
                    <h4>Aufgaben hochladen</h4>
                    <hr>
                    <br>
                    <form class="form-control border-0" action="upload.php" method="post" enctype="multipart/form-data">
                        <input class="form-control" type="file" id="formfile" name="file">
                        <button class="btn btn-success mt-2" type="submit" name="submit">Hochladen</button>
                    </form>
                    <br>
                </div>
                <div class="border rounded col">
                    <h4>Hochgeladene Datein</h4>
                    <hr>
                    <br>
                    <?php
                    $files = scandir('../users/' . $Taskfolder);
                    for ($i = 2; $i < count($files); $i++) {
                        echo '<a href="../users/' . $Taskfolder . '/' . $files[$i] . '">' . $files[$i] . '</a>';
                            echo '<form class="form-control border-0" action="deletefile.php" method="post">';
                            echo '<input type="hidden" name="file" id="file' . $i . '" value="' . $files[$i] . '">';
                            echo '<label for="file' . $i . '" class="form-label"></label><button class="btn btn-danger" type="submit" name="submit">Löschen</button></label>';
                            echo '</form>';
                            echo '<hr>';
                        }
        
                        if(count($files) == 2) {
                            echo 'Keine Dateien hochgeladen!';
                        }
                    ?>
                    <br>
                </div>
            </div>

            <!--Anzeige und Hinzufügen von Schülern-->
            <div class="row gap-3 p-2">
                <div class="border rounded col text-center">
                    <br>
                    <h2>Schüler/-innen</h2>
                    <hr>
                    <br>
                    <button class="btn btn-primary mb-2" onclick="window.location.href='addUser.php'">Schüler Hinzufügen</button>
                    <button class="btn btn-primary mb-2" onclick="window.location.href='addClass.php'">Klasse Hinzufügen</button>
                    <br>
                    <button class="btn btn-success mb-2" onclick="window.location.href='collectUpload.php'">Abgaben Einsammeln</button>
                    <br>
                </div>
                <div class="border rounded col">
                    <!--Dropdown für die Auswahl der verschiedenen Klassen-->
                    <h2>Schüler/-innen Liste</h2>
                    <hr>
                    <br>
                    <div class="form-control border-0">
                        <form action="dashboard.php" method="post">
                            <div class="row">
                                <div class="col">
                                    <select class="form-select" name="class">
                                        <option value="all">Alle</option>
                                        <?php
                                            require_once '../../inc/db.php';
                                            $stmt = $pdo->prepare("SELECT * FROM classes");
                                            $stmt->execute();
                                            $classes = $stmt->fetchAll();
                        
                                            foreach($classes as $class) {
                                                echo '<option value="' . $class["SUBJECT"] . '">' . $class["SUBJECT"] . '</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary" type="submit" name="submit">Auswählen</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--Tabelle welche die Schüler anhand der Klasse anzeigt-->
                    <div>
                        <table class="table">
                            <tr>
                                <th scope="col">Benutzername</th>
                                <th scope="col">Vorname</th>
                                <th scope="col">Nachname</th>
                                <th scope="col">E-Mail</th>
                            </tr>
                            <?php
                                require_once '../../inc/db.php';

                                if($_POST["class"] == "all") {
                                    $stmt = $pdo->prepare("SELECT * FROM students");
                                } else if($_POST["class"] != "all") {
                                    $stmt = $pdo->prepare("SELECT * FROM students WHERE CLASS = :class");
                                    $stmt->bindParam(":class", $_POST["class"]);
                                }
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach($users as $user) {
                                    echo '<tr>';
                                    echo '<td scope="row">' . $user["USERNAME"] . '</td>';
                                    echo '<td scope="row">' . $user["FIRSTNAME"] . '</td>';
                                    echo '<td scope="row">' . $user["LASTNAME"] . '</td>';
                                    echo '<td scope="row">' . $user["EMAIL"] . '</td>';
                                    echo '</tr>';
                                }
                            ?>
                        </table>
                    </div>
                </div>
                <div class="border rounded col">
                    
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