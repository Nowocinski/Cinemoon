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

	$zapytanie = $polaczenie->prepare('SELECT id_klienta FROM klienci WHERE nr_telefonu=:telefon');
	$zapytanie->bindValue(':telefon', $_POST['telefon'], PDO::PARAM_STR);
	$zapytanie->execute();
	
	if($zapytanie->rowCount() > 0)
	{
		$_SESSION['blad'] = '<div class="col-12 p-2 text-center text-danger">Ten numer telefonu jest już przypisany do innego konta</div>';
		header('Location: ustawienia-konta.php');
		exit();
	}
	else
	{
		$zapytanie = $polaczenie->prepare('UPDATE klienci SET nr_telefonu=:telefon WHERE id_klienta=:id');
		$zapytanie->bindValue(':id', $_SESSION['id_klienta'], PDO::PARAM_INT);
		$zapytanie->bindValue(':telefon', $_POST['telefon'], PDO::PARAM_STR);
		$zapytanie->execute();
		
		$_SESSION['nr_telefonu'] = $_POST['telefon'];
		
		$_SESSION['blad'] = '<div class="col-12 p-2 text-center text-success">Numer telefonu został zmieniony</div>';
		header('Location: ustawienia-konta.php');
	}
?>