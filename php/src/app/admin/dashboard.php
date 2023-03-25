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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Willkommen <?php echo $_SESSION["username"]; ?></p>
    <hr>
    <br>
    <h3>Schüler hinzufügen:</h3>
    <div class="add">
        <a href="addUser.php">Hinzufügen</a>
    </div>
    <br>
    <hr>
    <br>
    <h3>Klasse hinzufügen:</h3>
    <a href="addClass.php">Hinzufügen</a>
    <br>
    <hr>
    <br>
    <h3>Schülerliste:</h3>
    <div class="select">
        <form action="dashboard.php" method="post">
            <select name="class">
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
            <button type="submit" name="submit">Auswählen</button>
        </form>
    </div>
    <div class="list">
        <table>
            <tr>
                <th>Benutzername</th>
                <th>Vorname</th>
                <th>Nachname</th>
                <th>E-Mail</th>
            </tr>
            <?php
                require_once '../../inc/db.php';
                $stmt = $pdo->prepare("SELECT * FROM students WHERE CLASS = :class");
                $stmt->bindParam(":class", $_POST["class"]);
                $stmt->execute();
                $users = $stmt->fetchAll();

                foreach($users as $user) {
                    echo '<tr>';
                    echo '<td>' . $user["USERNAME"] . '</td>';
                    echo '<td>' . $user["FIRSTNAME"] . '</td>';
                    echo '<td>' . $user["LASTNAME"] . '</td>';
                    echo '<td>' . $user["EMAIL"] . '</td>';
                    echo '</tr>';
                }
            ?>
        </table>
    </div>
    <br>
    <hr>
    <br>
    <h3>Arbeiten einsammeln:</h3>
    <a href="collectUpload.php">Einsammeln</a>
    <br>
    <hr>
    <br>
    <a href="../../logout.php">Logout</a>
</body>
</html>