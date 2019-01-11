<?php
	if(!isset($_POST['numer_pracy']))
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

	$zapytanie = $polaczenie->prepare('UPDATE harmonogram_prac SET status = 1 WHERE id=:id');
	$zapytanie->bindValue(':id', $_POST['numer_pracy'], PDO::PARAM_INT);
	$zapytanie->execute();
	
	header('Location: pracownik.php');
?>