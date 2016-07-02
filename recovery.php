<?php
$link = mysqli_connect("localhost", "root", "", "test")
or die ("Datenbankanbindung funktioniert nicht");

function random_string()
{
    if(function_exists('random_bytes'))
    {
        $bytes = random_bytes(16);
        $str = bin2hex($bytes);
    }
    else if(function_exists('openssl_random_pseudo_bytes'))
    {
        $bytes = openssl_random_pseudo_bytes(16);
        $str = bin2hex($bytes);
    }
    else if(function_exists('mcrypt_create_iv'))
    {
        $bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
        $str = bin2hex($bytes);
    }
    else
    {
        $str = md5(uniqid('123456789098', true));
    }
    return $str;
}

if(isset($_GET['send']))
{
    if(!isset($_POST['email']) || empty($_POST['email']))
    {
        
    }
    else 
    {
        $email = $_POST['email'];
        $statement = mysqli_query($link, "SELECT * FROM users WHERE email = '".$email."'");
        $result = mysqli_fetch_assoc($statement);
        $usermail = $result["email"];
        if($usermail === false)
        {
            $error = "<b>Kein User gefunden</b>";
        }
            else
            {
                $userid = $result['id'];
                $passwortcode = random_string();
                $statement = mysqli_query($link, "UPDATE users SET passwortcode = '".$passwortcode."', passwortcode_time = NOW() WHERE id= '".$userid."'");
               //$result = $statement->execute(array('passwortcode' => sha1($passwortcode), 'userid' => $user['id']));
                //$result = mysqli_fetch_assoc($statement);
                $usermail = $result["email"];


                $empfaenger = $result['email'];
                $betreff = "neues PW für website";
                $from = "von mir";
                $url_passwortcode = 'http://localhost/passwordrecovery.php?userid='.$result['id'].'&code='.$passwortcode;
                $text = 'Hallo '.$result['vorname'].',
                    für deinen Account auf www.php-einfach.de wurde nach einem neuen Passwort gefragt. Um ein neues Passwort zu vergeben, rufe innerhalb der nächsten 24 Stunden die folgende Website auf:
                    '.$url_passwortcode.'

                    Sollte dir dein Passwort wieder eingefallen sein oder hast du dies nicht angefordert, so bitte ignoriere diese E-Mail.
 
                    Viele Grüße,
                    dein RZ-Team';

                    mail($empfaenger, $betreff, $text, $from);
 
			echo "Ein Link um dein Passwort zurückzusetzen wurde an deine E-Mail-Adresse gesendet.";
			$showForm = false;
			
            }
    }

}

$showForm = true;

if ($showForm):
    ?>
<h1>Du Dummer Spasst hast dein Passwort Vergessen</h1>
Gib hier deine E-Mail-Adresse ein, um ein neues Passwort anfordern!<br /><br />

<?php
if(isset($error) && !empty($error))
{
    echo $error;
}
?>

<form action="?send=1" method="POST">
    E-Mail:<br />
    <input  type="email" name="email"><br />
    <input type="submit" value="Neues Passwort beantragen!">
</form>


<?php
endif; //ShowForm Ende des IFs
?>