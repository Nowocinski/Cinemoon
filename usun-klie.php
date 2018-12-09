<?php

if(!isset($_POST['id']))
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

$zapytanie = $polaczenie->prepare('DELETE FROM klienci WHERE id_klienta=:var1');
$zapytanie->bindValue(':var1', $_POST['id'], PDO::PARAM_INT);
$zapytanie->execute();

$zapytanie = $polaczenie->prepare('DELETE FROM rezerwacje WHERE id_klienta=:var1');
$zapytanie->bindValue(':var1', $_POST['id'], PDO::PARAM_INT);
$zapytanie->execute();

require_once('edycja-kont.php');

?>