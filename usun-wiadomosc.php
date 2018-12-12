<?php

if(!isset($_POST['id']))
{
	header('Location: index.php');
	exit();
}

echo $_POST['id'];

require_once 'connect.php';
	
try
{
	$polaczenie = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
}
catch(PDOException $e)
{
	echo "Nie można nazwiązać połączenia z bazą danych";
}
	
$zapytanie = $polaczenie->prepare('DELETE FROM wiadomosci WHERE id_wiadomosci=:id');
$zapytanie->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
$zapytanie->execute();

header('Location: wiadomosci.php')

?>