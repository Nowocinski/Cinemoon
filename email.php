<?php

if(!isset($_POST['email']))
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

if(!preg_match('/^[a-zA-Z0-9\.\_\-]+@[a-zA-Z0-9]+.(com|pl|org)$/',$_POST['email']))
{
	$_SESSION['powiadomienie'] = '<div class="col-lg-9">
                    <div class="alert alert-dismissable alert-danger">
                        <button data-dismiss="alert" class="close" type="button">&times;</button>
                        Nieprawidłowa składnia adresu e-mail
                    </div>
                </div>';
	header('Location: zarzadzanie-kontem.php');
	exit();
}

$zapytanie = $polaczenie->prepare('SELECT id FROM konta WHERE email=:email AND id!=:id');
$zapytanie->bindValue(':email', $_SESSION['email'], PDO::PARAM_STR);
$zapytanie->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
$zapytanie->execute();

if($zapytanie->rowCount() > 0)
{
	$_SESSION['powiadomienie'] = '<div class="col-lg-9">
                    <div class="alert alert-dismissable alert-danger">
                        <button data-dismiss="alert" class="close" type="button">&times;</button>
                        Ten adres e-mail jest już przypisany do innego konta
                    </div>
                </div>';
	header('Location: zarzadzanie-kontem.php');
	exit();
}

else
{
$zapytanie = $polaczenie->prepare('UPDATE konta SET email=:var1 WHERE id=:var2');
$zapytanie->bindValue(':var1', $_POST['email'], PDO::PARAM_STR);
$zapytanie->bindValue(':var2', $_SESSION['id'], PDO::PARAM_INT);
$zapytanie->execute();

$_SESSION['email'] = $_POST['email'];
$_SESSION['powiadomienie'] = '<div class="col-lg-9">
                    <div class="alert alert-dismissable alert-success">
                        <button data-dismiss="alert" class="close" type="button">&times;</button>
                        Pomyślnie zmieniono adres e-mail
                    </div>
                </div>';

header('Location: zarzadzanie-kontem.php');
}
?>