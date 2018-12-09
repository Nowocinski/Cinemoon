<?php

if(!isset($_POST['imie']))
{
	header('Location: index.php');
	exit();
}

$zahasowane_haslo = password_hash($_POST['haslo'], PASSWORD_DEFAULT);

require_once 'connect.php';

try
{
	$polaczenie = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
}
catch(PDOException $e)
{
	echo "Nie można nazwiązać połączenia z bazą danych";
}

$zapytanie = $polaczenie->prepare('INSERT INTO pracownicy VALUES ("", :tk, :im, :mi, :ad, :em, :hs, :nt, :na)');
$zapytanie->bindValue(':tk', $_POST['typ'], PDO::PARAM_STR);
$zapytanie->bindValue(':im', $_POST['imie'], PDO::PARAM_STR);
$zapytanie->bindValue(':mi', $_POST['miejscowosc'], PDO::PARAM_STR);
$zapytanie->bindValue(':ad', $_POST['adres'], PDO::PARAM_STR);
$zapytanie->bindValue(':em', $_POST['email'], PDO::PARAM_STR);
$zapytanie->bindValue(':hs', $zahasowane_haslo, PDO::PARAM_STR);
$zapytanie->bindValue(':nt', $_POST['telefon'], PDO::PARAM_STR);
$zapytanie->bindValue(':na', $_POST['nazwisko'], PDO::PARAM_STR);
$zapytanie->execute();

header('Location: dodaj-pracownika.php');
?>