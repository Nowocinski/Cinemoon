<?php

if(!isset($_POST['nazwisko']))
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

$zapytanie = $polaczenie->prepare('UPDATE konta SET nazwisko=:var1 WHERE id=:var2');
$zapytanie->bindValue(':var1', $_POST['nazwisko'], PDO::PARAM_STR);
$zapytanie->bindValue(':var2', $_SESSION['id'], PDO::PARAM_INT);
$zapytanie->execute();

$_SESSION['powiadomienie'] = '<div class="col-lg-9">
                    <div class="alert alert-dismissable alert-success">
                        <button data-dismiss="alert" class="close" type="button">&times;</button>
                        Pomyślnie zmieniono nazwisko
                    </div>
                </div>';
$_SESSION['nazwisko'] = $_POST['nazwisko'];

header('Location: zarzadzanie-kontem.php');
?>