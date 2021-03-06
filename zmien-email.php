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

	$zapytanie = $polaczenie->prepare('SELECT haslo FROM konta WHERE id=:id');
	$zapytanie->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
	$zapytanie->execute();

	$obj = $zapytanie->fetch(PDO::FETCH_OBJ);

	if(!password_verify($_POST['haslo'], $obj->haslo))
	{
		$_SESSION['blad'] = '<div class="col-12 p-2 text-center text-danger">Podano nieprawidłowe hasło</div>';
		header('Location: ustawienia-konta.php');
		exit();
	}

	$zapytanie = $polaczenie->prepare('SELECT id FROM konta WHERE email=:email');
	$zapytanie->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
	$zapytanie->execute();
	
	if($zapytanie->rowCount() > 0)
	{
		$_SESSION['blad'] = '<div class="col-12 p-2 text-center text-danger">Ten adres e-mail jest już przypisany do innego konta</div>';
		header('Location: ustawienia-konta.php');
		exit();
	}

	if(!preg_match('/^[a-zA-Z0-9\.\_\-]+@[a-zA-Z0-9]+.(com|pl|org)$/',$_POST['email']))
    {
        $poprawna_walidacja = false;
        $_SESSION['blad'] = '<div class="col-12 p-2 text-center text-danger">Niepoprawna składnia adresu e-mail</div>';
		header('Location: ustawienia-konta.php');
		exit();
    }
	
	else
	{
		$zapytanie = $polaczenie->prepare('UPDATE konta SET email=:email WHERE id=:id');
		$zapytanie->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
		$zapytanie->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
		$zapytanie->execute();
		
		$_SESSION['blad'] = '<div class="col-12 p-2 text-center text-success">Adres e-mail został zmieniony</div>';
		$_SESSION['email'] = $_POST['email'];

		header('Location: ustawienia-konta.php');
	}
?>