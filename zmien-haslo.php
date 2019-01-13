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

	if(strlen($_POST['haslo2']) < 6 || strlen($_POST['haslo2']) > 30)
    {
        $_SESSION['blad'] = '<div class="col-12 p-2 text-center text-danger">Niepoprawna długość hasła</div>';
		header('Location: ustawienia-konta.php');
		exit();
    }
	
	if($_POST['haslo1'] != $_POST['haslo2'])
    {
        $_SESSION['blad'] = '<div class="col-12 p-2 text-center text-danger">Hasła nie pasują do siebie</div>';
		header('Location: ustawienia-konta.php');
		exit();
    }
	
	else
	{
		$zapytanie = $polaczenie->prepare('UPDATE konta SET haslo=:haslo WHERE id=:id');
		$zapytanie->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
		$zapytanie->bindValue(':haslo', password_hash($_POST['haslo1'], PASSWORD_DEFAULT), PDO::PARAM_STR);
		$zapytanie->execute();
		
		$_SESSION['blad'] = '<div class="col-12 p-2 text-center text-success">Hasło zostało zmienione</div>';

		header('Location: ustawienia-konta.php');
	}
?>