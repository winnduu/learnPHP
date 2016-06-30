<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "test")
or die("tot");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Registrierung</title>
</head>
<body>

<?php
$showFormular = true; //Variable ob das Registrierungsformular anezeigt werden soll

if(isset($_GET['register'])) {
	$error = false;
	$email = $_POST['email'];
	$passwort = $_POST['passwort'];
	$passwort2 = $_POST['passwort2'];

	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo 'Bitte eine gültige E-Mail-Adresse eingeben<br>';
		$error = true;
	}
	if(strlen($passwort) == 0) {
		echo 'Bitte ein Passwort angeben<br>';
		$error = true;
	}
	if($passwort != $passwort2) {
		echo 'Die Passwörter müssen übereinstimmen<br>';
		$error = true;
	}

	//Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
	if(!$error)
  {
    $statement = mysqli_query($link, "SELECT * FROM users WHERE email = '".$email."'");
    $result = mysqli_fetch_assoc($statement);
    $usermail = $result["email"];

		if($usermail !== NULL)
    {
      echo $usermail;
      echo 'Diese E-Mail-Adresse ist bereits vergeben<br>';
			$error = true;
		}
	}

	//Keine Fehler, wir können den Nutzer registrieren
	if(!$error) {
		$passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);//

		$statement = mysqli_query($link, "INSERT INTO users (email, passwort) VALUES ('".$email."', '".$passwort_hash."')");

		if($statement) {
			echo 'Du wurdest erfolgreich registriert. <a href="login.php">Zum Login</a>';
			$showFormular = false;
		} else {
			echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
		}
	}
}

if($showFormular) {
?>
<form action="?register=1" method="post">
E-Mail:<br>
<input type="email" size="40" maxlength="250" name="email"><br><br>

Dein Passwort:<br>
<input type="password" size="40"  maxlength="250" name="passwort"><br>

Passwort wiederholen:<br>
<input type="password" size="40" maxlength="250" name="passwort2"><br><br>

<input type="submit" value="Abschicken">
</form>

<?php
} //Ende von if($showFormular)
?>

</body>
</html>
