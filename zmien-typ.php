<?php
	if(!isset($_POST['typ']))
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
	
	if($_POST['typ'] == 'normalny')
		$typ = 'studencki';
	else
		$typ = 'normalny';

	$zapytanie = $polaczenie->prepare('UPDATE konta SET typ_konta=:typ_konta WHERE id=:id');
	$zapytanie->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
	$zapytanie->bindValue(':typ_konta', $typ, PDO::PARAM_STR);
	$zapytanie->execute();

	$_SESSION['blad'] = '<div class="col-12 p-2 text-center text-success">Typ konta został zmieniony</div>';
	$_SESSION['typ_konta'] = $typ;

	header('Location: ustawienia-konta.php');
?>