<?php

if(!isset($_POST['dzien']))
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

$zapytanie = $polaczenie->prepare("INSERT INTO harmonogram_prac VALUES ('', :id, :dzien, :czas_od, :czas_do, :opis, :status)");

$zapytanie->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
$zapytanie->bindValue(':dzien', $_POST['dzien'], PDO::PARAM_STR);
$zapytanie->bindValue(':czas_od', $_POST['czas_od'], PDO::PARAM_STR);
$zapytanie->bindValue(':czas_do', $_POST['czas_do'], PDO::PARAM_STR);
$zapytanie->bindValue(':opis', $_POST['opis'], PDO::PARAM_STR);
$zapytanie->bindValue(':status', 0, PDO::PARAM_INT);

$zapytanie->execute();

$_SESSION['powiadomienie'] = '<div class="row"><div class="col-lg-12"><div class="alert alert-dismissable alert-success"> <button data-dismiss="alert" class="close" type="button">&times;</button>
    Dodano nowe zdarzenie do harmonogramu prac
</div></div></div>';

header('Location: harmonogram.php');

?>