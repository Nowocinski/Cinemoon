<?php
	if(!isset($_GET['id']))
	{
		header('Location: index.php');
		exit();
	}

	$title = "Cinemoon";

    include "side_part/gora.php";
    include "side_part/nav.php";
	
	require_once 'connect.php';

	try
	{
		$polaczenie = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
	}
	catch(PDOException $e)
	{
		echo "Nie można nazwiązać połączenia z bazą danych";
	}

	$zapytanie = $polaczenie->prepare('SELECT * FROM aktualnosci WHERE id=:id');
	$zapytanie->bindValue(':id', $_GET['id'], PDO::PARAM_STR);
	$zapytanie->execute();
	
	$obj = $zapytanie->fetch(PDO::FETCH_OBJ);
	
echo<<<END
<div class="m-3 pb-4 text-center dane-konta" style="word-wrap: break-word; padding-left: 90px; padding-right: 90px;">
	<h1>{$obj->temat}</h1>
	<h6 style="color: gray">{$obj->data}</h6>
		{$obj->tresc}
</div>
END;

	include "side_part/footer.php";
    include "side_part/dol.php";
?>