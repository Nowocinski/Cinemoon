<?php

if(!isset($_POST['imie']))
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

$zapytanie = $polaczenie->prepare('UPDATE konta SET imie=:var1 WHERE id=:var2');
$zapytanie->bindValue(':var1', $_POST['imie'], PDO::PARAM_STR);
$zapytanie->bindValue(':var2', $_SESSION['id'], PDO::PARAM_INT);
$zapytanie->execute();

$_SESSION['imie'] = $_POST['imie'];
$_SESSION['powiadomienie'] = '<div class="col-lg-9">
                    <div class="alert alert-dismissable alert-success">
                        <button data-dismiss="alert" class="close" type="button">&times;</button>
                        Pomyślnie zmieniono imię
                    </div>
                </div>';

header('Location: zarzadzanie-kontem.php');
?>