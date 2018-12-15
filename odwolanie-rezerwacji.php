<?php
    if(!isset($_POST['przycisk']))
    {
      header('Location: index.php');
      exit();
    }

    require_once "connect.php";

	try
	{
	   $polaczenie = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
	}
	catch(PDOException $e)
	{
		echo "Nie można nazwiązać połączenia z bazą danych";
	}

	$zapytanie = $polaczenie->prepare("DELETE FROM rezerwacje WHERE {$_POST['przycisk']}");
	$zapytanie->execute();
	
	header('Location: konto.php')
?>
