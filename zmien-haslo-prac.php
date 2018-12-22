<?php

if(!isset($_POST['haslo']))
{
    header('Location: index.php');
    exit();
}

require_once 'connect.php';

$haslo_hash = password_hash($_POST['haslo'], PASSWORD_DEFAULT);

try
{
	$polaczenie = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
}
catch(PDOException $e)
{
	echo "Nie można nazwiązać połączenia z bazą danych";
}

$zapytanie = $polaczenie->prepare('UPDATE pracownicy SET haslo=:var1 WHERE id_pracownika=:var2');
$zapytanie->bindValue(':var1', $haslo_hash, PDO::PARAM_STR);
$zapytanie->bindValue(':var2', $_POST['id'], PDO::PARAM_INT);
$zapytanie->execute();

header('Location: edycja-kont.php');
?>