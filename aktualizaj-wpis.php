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

$zapytanie = $polaczenie->prepare('UPDATE aktualnosci SET temat=:temat, tresc=:tresc WHERE id=:id');
$zapytanie->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
$zapytanie->bindValue(':temat', htmlentities($_POST['temat']), PDO::PARAM_STR);
$zapytanie->bindValue(':tresc', nl2br(htmlentities($_POST['tresc'])), PDO::PARAM_STR);
$zapytanie->execute();

header('Location: zarzadanie-aktualnosciami.php');
?>