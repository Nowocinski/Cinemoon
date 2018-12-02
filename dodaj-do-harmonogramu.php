<?php

if(!isset($_POST['dzien']))
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

$zapytanie = $polaczenie->prepare("INSERT INTO harmonogram_prac VALUES ('', :id, :dzien, :czas_od, :czas_do, :opis)");

$zapytanie->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
$zapytanie->bindValue(':dzien', $_POST['dzien'], PDO::PARAM_STR);
$zapytanie->bindValue(':czas_od', $_POST['czas_od'], PDO::PARAM_STR);
$zapytanie->bindValue(':czas_do', $_POST['czas_do'], PDO::PARAM_STR);
$zapytanie->bindValue(':opis', $_POST['opis'], PDO::PARAM_STR);

$zapytanie->execute();

header('Location: harmonogram.php');

?>