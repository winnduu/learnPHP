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

if(isset($_GET['send'])){

    
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