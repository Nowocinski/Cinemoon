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

	$zapytanie = $polaczenie->prepare('DELETE FROM klienci WHERE id_klienta=:id');
	$zapytanie->bindValue(':id', $_SESSION['id_klienta'], PDO::PARAM_INT);
	$zapytanie->execute();
	
	$zapytanie = $polaczenie->prepare('DELETE FROM rezerwacje WHERE id_klienta=:id');
	$zapytanie->bindValue(':id', $_SESSION['id_klienta'], PDO::PARAM_INT);
	$zapytanie->execute();

	$_SESSION['imie'] = $_POST['imie'];

	$_SESSION['konto_zostalo_usuniete'] = true;
	header('Location: powod-usuniecia.php');
?>