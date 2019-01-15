<?php

if(!isset($_POST['element']))
{
    header('Location: index.php');
    exit();
}

require_once 'connect.php';

if (session_status() == PHP_SESSION_NONE)
    session_start();

try
{
	$polaczenie = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
}
catch(PDOException $e)
{
	echo "Nie można nazwiązać połączenia z bazą danych";
}

$zapytanie = $polaczenie->prepare("DELETE FROM harmonogram_prac WHERE harmonogram_prac.id=:var");
$zapytanie->bindValue(':var', $_POST['element'], PDO::PARAM_INT);
$zapytanie->execute();

$_SESSION['powiadomienie'] = '<div class="row"><div class="col-lg-12"><div class="alert alert-dismissable alert-success"> <button data-dismiss="alert" class="close" type="button">&times;</button>
    Usunięto zdarzenie z harmonogramu prac
</div></div></div>';

header('Location: harmonogram.php');

?>