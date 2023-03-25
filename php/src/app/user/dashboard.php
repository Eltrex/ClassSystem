<?php
session_start();
if(!isset($_SESSION["username"])) {
    header("Location: ../../index.php");
    exit();
}
if($_SESSION["permission"] == "admin") {
    header("Location: ../../admin/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
    </head>
    <body>
        <h1>Dashboard</h1>
        <p>Willkommen <?php echo $_SESSION["username"]; ?></p>
        <hr>
        <br>
        <h3>Dateien hochladen:</h3>
        <div class="upload">
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="file">
                <button type="submit" name="submit">Upload</button>
            </form>
        </div>
        <br>
        <hr>
        <br>
        <h3>Aufgaben:</h3>
        // Show the files from the users/Task folder and add a button to download them and if it is a .pdf file add a button to open it
        <div>
            <hr>
            <?php
                $files = scandir('../users/#Task');
                for ($i = 2; $i < count($files); $i++) {
                    echo '<a href="users/#Task/' . $files[$i] . '">' . $files[$i] . '</a>';
                    echo '<form action="download.php" method="post">';
                    echo '<input type="hidden" name="file" value="' . $files[$i] . '">';
                    echo '<button type="submit" name="submit">Download</button>';
                    echo '</form>';
                    if(substr($files[$i], -4) == ".pdf") {
                        echo '<form action="open.php" method="post">';
                        echo '<input type="hidden" name="file" value="' . $files[$i] . '">';
                        echo '<button type="submit" name="submit">Öffnen</button>';
                        echo '</form>';
                    }
                    echo '<hr>';
                }
            ?>
            <hr>
        </div>
        <br>
        <hr>
        <br>
        <div>
            <h3>Bereits hochgeladene Dateien:</h3>
            <hr>
            <?php
                $files = scandir('../users/' . $_SESSION["username"]);
                for ($i = 2; $i < count($files); $i++) {
                    echo '<a href="users/' . $_SESSION["username"] . '/' . $files[$i] . '">' . $files[$i] . '</a>';
                    echo '<form action="deletefile.php" method="post">';
                    echo '<input type="hidden" name="file" value="' . $files[$i] . '">';
                    echo '<button type="submit" name="submit">Löschen</button>';
                    echo '</form>';
                    echo '<hr>';
                }

                if(count($files) == 2) {
                    echo 'Keine Dateien hochgeladen!';
                }
            ?>
            <hr>
        </div>

        <a href="../../logout.php">Logout</a>
    </body>
</html>