<?php
if(isset($_POST['add'])) {
    $firstname = $_POST['name'];
    $lastname = $_POST['surname'];
    $username = $firstname . '.' . $lastname;
    $email = $username . '@schueler-bkvie.de';
    $password = 'Geheim123!';

    require_once '../../inc/db.php';
    $stmt = $pdo->prepare("SELECT * FROM students WHERE USERNAME = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $count = $stmt->rowCount();
    $i = 1; // Counter for the while loop

    $hash = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    if($count > 0) {
       $user = $stmt->fetch();

       while ($user == $username) {
           $i++;
           $username = $username . $i;
       }

        $stmt = $pdo->prepare("INSERT INTO students (FIRSTNAME, LASTNAME, USERNAME, EMAIL, PASSWORD) VALUES (:name, :surname, :username, :email, :password)");
        $stmt->bindParam(':name', $firstname);
        $stmt->bindParam(':surname', $lastname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash);
        $stmt->execute();
        echo 'Der Benutzer wurde erfolgreich angelegt!';
       
    } else {
        $stmt = $pdo->prepare("INSERT INTO students (FIRSTNAME, LASTNAME, USERNAME, EMAIL, PASSWORD) VALUES (:name, :surname, :username, :email, :password)");
        $stmt->bindParam(':name', $firstname);
        $stmt->bindParam(':surname', $lastname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash);
        $stmt->execute();
        echo 'Der Benutzer wurde erfolgreich angelegt!';
    }

    if (!file_exists('users/' . $username)) {
        mkdir('users/' . $username, 0777, true);
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
    <form action="addUser.php" method="post">
        <input type="text" name="name" placeholder="Name">
        <input type="text" name="surname" placeholder="Nachname">
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
        <input type="submit" name="add" value="Hinzufügen">
    </form>
</body>
</html>