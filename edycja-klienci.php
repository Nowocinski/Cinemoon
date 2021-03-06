<?php

if(!isset($_POST['imie']))
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

$zapytanie = $polaczenie->prepare('UPDATE klienci SET imie=:im, nazwisko=:na, email=:em, nr_telefonu=:te, typ=:ty WHERE id_klienta=:id');

$zapytanie->bindValue(':im', $_POST['imie'], PDO::PARAM_STR);
$zapytanie->bindValue(':na', $_POST['nazwisko'], PDO::PARAM_STR);
$zapytanie->bindValue(':em', $_POST['email'], PDO::PARAM_STR);
$zapytanie->bindValue(':te', $_POST['tele'], PDO::PARAM_INT);
//$zapytanie->bindValue(':mi', $_POST['miejscowosc'], PDO::PARAM_STR);
//$zapytanie->bindValue(':ad', $_POST['adres'], PDO::PARAM_STR);
$zapytanie->bindValue(':ty', $_POST['typ'], PDO::PARAM_STR);
$zapytanie->bindValue(':id', $_POST['id'], PDO::PARAM_STR);

$zapytanie->execute();

header('Location: edycja-kont.php');

?>