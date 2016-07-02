<?php
session_start();
$link = new mysqli_connect("localhost", "root", "", "test");

if(!isset($_GET['userid']) || !isset($_GET['code'])) {
    die("No RecoveryKey found in the Databse");
}

$userid = $_GET['userid'];
$code = $_GET['code'];

//Abfrage des Nutzers
    $statement = mysqli_query($link, "SELECT * FROM users WHERE id = '".$userid."'");
    $result = mysqli_fetch_assoc($statement);
    $user = $result; //QnD Test ;)

//Überprüfe dass ein Nutzer gefunden wurde und dieser auch ein Passwortcode hat
if($user === null || $user['passwortcode'] === null) {
    die("User not found.");
}

if($user['passwortcode_time'] === null || strtotime($user['passwortcode_time']) < (time()-24*3600) ) {
    die("RecoveryCode ist zu lang (24h+)");
}


//Überprüung des übergebenen RecoveryCodes
if(sha1($code) != $user['passwortcode']) {
    die("Used RecoveryKey was wrong.");
}

//RecoveryCode stimmt überein und ist noch nicht abgelaufen
if(isset($_GET['send'])) {
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];

    if($passwort != $passwort2) {
        echo "Passwords didn't Match";
    } else { //Speichere neues Passwort und lösche den Code //TODO: Umbauen auf MSQLI
        $passworthash = password_hash($passwort, PASSWORD_DEFAULT);
        $statement = $pdo->prepare("UPDATE users SET passwort = :passworthash, passwortcode = NULL, passwortcode_time = NULL WHERE id = :userid");
        $result = $statement->execute(array('passworthash' => $passworthash, 'userid'=> $userid ));

        if($result) {
            die("Password_Recovery successfull!");
        }
    }
}
?>

<h1>Neues Passwort vergeben</h1>
<form action="?send=1&amp;userid=<?php echo htmlentities($userid); ?>&amp;code=<?php echo htmlentities($code); ?>" method="post">
    Bitte gib ein neues Passwort ein:<br>
    <input type="password" name="passwort"><br><br>

    Passwort erneut eingeben:<br>
    <input type="password" name="passwort2"><br><br>

    <input type="submit" value="Passwort speichern">
</form>