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

$zapytanie = $polaczenie->prepare("UPDATE harmonogram_prac SET dzien=:day, czas_od=:time_a, czas_do=:time_b, info_o_pracy=:describe WHERE id=:num");
$zapytanie->bindValue(':day', $_POST['dzien'], PDO::PARAM_STR);
$zapytanie->bindValue(':time_a', $_POST['czas_od'], PDO::PARAM_STR);
$zapytanie->bindValue(':time_b', $_POST['czas_do'], PDO::PARAM_STR);
$zapytanie->bindValue(':describe', $_POST['opis'], PDO::PARAM_STR);
$zapytanie->bindValue(':num', $_POST['id'], PDO::PARAM_INT);

$zapytanie->execute();

$_SESSION['powiadomienie'] = '<div class="row"><div class="col-lg-12"><div class="alert alert-dismissable alert-success"> <button data-dismiss="alert" class="close" type="button">&times;</button>
    Zedytowano zdarzenie w harmonogramie prac
</div></div></div>';

header('Location: harmonogram.php');

?>