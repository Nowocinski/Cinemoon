<?php

if(!isset($_POST['potwierdzenie']))
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

$zapytanie = $polaczenie->prepare('DELETE FROM pracownicy WHERE id_pracownika=:var1');
$zapytanie->bindValue(':var1', $_SESSION['id_pracownika'], PDO::PARAM_INT);
$zapytanie->execute();

$zapytanie = $polaczenie->prepare('DELETE FROM harmonogram_prac WHERE id_prac=:var1');
$zapytanie->bindValue(':var1', $_SESSION['id_pracownika'], PDO::PARAM_INT);
$zapytanie->execute();

require_once('wyloguj.php');

?>