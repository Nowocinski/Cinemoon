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

	$zapytanie = $polaczenie->prepare('UPDATE konta SET imie=:imie WHERE id=:id');
	$zapytanie->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
	$zapytanie->bindValue(':imie', htmlentities($_POST['imie']), PDO::PARAM_STR);
	$zapytanie->execute();

	$_SESSION['blad'] = '<div class="col-12 p-2 text-center text-success">Imię zostało zmienione</div>';
	$_SESSION['imie'] = $_POST['imie'];

	header('Location: ustawienia-konta.php');
?>