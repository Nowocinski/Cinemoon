<?php

if(!isset($_POST['id']))
{
    header('Location: index.php');
    exit();
}

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'connect.php';

try
{
	$polaczenie = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
}
catch(PDOException $e)
{
	echo "Nie można nazwiązać połączenia z bazą danych";
}

$zapytanie = $polaczenie->prepare('DELETE FROM aktualnosci WHERE id=:id');
$zapytanie->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
$zapytanie->execute();

header('Location: zarzadanie-aktualnosciami.php');
?>