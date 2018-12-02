<?php

if(!isset($_POST['element']))
{
    header('Location: index.php');
    exit();
}

require_once 'connect.php';

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

header('Location: harmonogram.php');

?>