<?php

if(!isset($_POST['haslo_stare']))
{
    header('Location: index.php');
    exit();
}

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'connect.php';

$haslo_hash = password_hash($_POST['haslo_nowe'], PASSWORD_DEFAULT);

try
{
	$polaczenie = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
}
catch(PDOException $e)
{
	echo "Nie można nazwiązać połączenia z bazą danych";
}

$zapytanie = $polaczenie->prepare('SELECT haslo FROM konta WHERE id=:id');
$zapytanie->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
$zapytanie->execute();

$obj = $zapytanie->fetch(PDO::FETCH_OBJ);

//Weryfikacja starego hasła
if(!password_verify($_POST['haslo_stare'], $obj->haslo))
{
	$_SESSION['powiadomienie'] = '<div class="col-lg-9">
                    <div class="alert alert-dismissable alert-danger">
                        <button data-dismiss="alert" class="close" type="button">&times;</button>
                        Stare hasło nie jest identyczne z poprzednim hasłem
                    </div>
                </div>';
	header('Location: zarzadzanie-kontem.php');
	exit();
}

if($_POST['haslo_nowe']!=$_POST['haslo_powtorzone'])
{
	$_SESSION['powiadomienie'] = '<div class="col-lg-9">
                    <div class="alert alert-dismissable alert-danger">
                        <button data-dismiss="alert" class="close" type="button">&times;</button>
                        Powtórzone hasło nie jest identyczne z nowym hasłem
                    </div>
                </div>';
	header('Location: zarzadzanie-kontem.php');
	exit();
}

else
{
$zapytanie = $polaczenie->prepare('UPDATE konta SET haslo=:var1 WHERE id=:var2');
$zapytanie->bindValue(':var1', $haslo_hash, PDO::PARAM_STR);
$zapytanie->bindValue(':var2', $_SESSION['id'], PDO::PARAM_INT);
$zapytanie->execute();

$_SESSION['powiadomienie'] = '<div class="col-lg-9">
                    <div class="alert alert-dismissable alert-success">
                        <button data-dismiss="alert" class="close" type="button">&times;</button>
                        Pomyślnie zedytowano hasło
                    </div>
                </div>';

header('Location: zarzadzanie-kontem.php');
}
?>