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

	$zapytanie = $polaczenie->prepare('UPDATE adres SET adres=:adres WHERE id=:id');
	$zapytanie->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
	$zapytanie->bindValue(':adres', htmlentities($_POST['adres']), PDO::PARAM_STR);
	$zapytanie->execute();

	$_SESSION['blad'] = '<div class="col-12 p-2 text-center text-success">Adres został zmieniony</div>';
	$_SESSION['adres'] = $_POST['adres'];

	header('Location: ustawienia-konta.php');
?>