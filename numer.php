<?php

if(!isset($_POST['numer']))
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

$zapytanie = $polaczenie->prepare('UPDATE konta SET nr_telefonu=:var1 WHERE id=:var2');
$zapytanie->bindValue(':var1', $_POST['numer'], PDO::PARAM_STR);
$zapytanie->bindValue(':var2', $_SESSION['id'], PDO::PARAM_INT);
$zapytanie->execute();

$_SESSION['nr_telefonu'] = $_POST['numer'];

$_SESSION['powiadomienie'] = '<div class="col-lg-9">
                    <div class="alert alert-dismissable alert-success">
                        <button data-dismiss="alert" class="close" type="button">&times;</button>
                        Pomyślnie zedytowano numer telefonu
                    </div>
                </div>';

header('Location: zarzadzanie-kontem.php');
?>