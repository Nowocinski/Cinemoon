<?php

if(!isset($_POST['miejscowosc']))
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

$zapytanie = $polaczenie->prepare('UPDATE pracownicy SET miejscowosc=:var1 WHERE id_pracownika=:var2');
$zapytanie->bindValue(':var1', $_POST['miejscowosc'], PDO::PARAM_STR);
$zapytanie->bindValue(':var2', $_SESSION['id_pracownika'], PDO::PARAM_INT);
$zapytanie->execute();

$_SESSION['miejscowosc'] = $_POST['miejscowosc'];

header('Location: zarzadzanie-kontem.php');
?>