<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "test")
or die("SQL Connector couldnt connect");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

<?php
if(isset($_GET['login'])) {
	$email = $_POST['email'];
	$passwort = $_POST['passwort'];

  $statement = mysqli_query($link, "SELECT * FROM users WHERE email = '".$email."'");
  $result = mysqli_fetch_assoc($statement);
  $usermail = $result['email'];

    if($usermail == $email)
      {
        if(password_verify($passwort, $result['passwort']))
        //if($passwort == $result['passwort'])
        {
            $_SESSION['userid'] = $result['id'];
            die('Login erfolgreich. Weiter zu <a href="backend.php">internen Bereich</a>');
            echo "läuft";
        }
        else
        {
            echo "passwort falsch.";
        }
      }
    else
    {
      echo "nope";
    }
}
?>
<form action="?login=1" method="post">
E-Mail:<br>
<input type="email" size="40" maxlength="250" name="email"><br><br>

Dein Passwort:<br>
<input type="password" size="40"  maxlength="250" name="passwort"><br>

<input type="submit" value="Anmelden">
  <a href="recovery.php">Passwort-Recovery</a>
</form>
