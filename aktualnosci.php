<?php
	include "side_part/przekierowanie-pracownikow.php";

    $title = "Aktualności";

    include "side_part/gora.php";
    include "side_part/nav.php";
?>

<div class="dane-konta2 pt-3 pb-3 text-center" style="background-color: #071418">
	<h2>Wszystkie aktalności</h2>
	
<?php
require_once 'connect.php';

try
{
	$polaczenie = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
}
catch(PDOException $e)
{
	echo "Nie można nazwiązać połączenia z bazą danych";
}

$zapytanie = $polaczenie->prepare('SELECT * FROM aktualnosci ORDER BY data DESC');
$zapytanie->execute();

while($obj = $zapytanie->fetch(PDO::FETCH_OBJ))
{
$temat = substr($obj->tresc,0,600);
if(strlen($obj->tresc) > 599) $temat.='...';
echo<<<END
<div class="mb-3 p-3" style="word-wrap: break-word; padding-left: 45px; padding-right: 45px; border: 2px solid gray; border-radius: 5px;">
	<h5 style="color: #A9A9A9;"><a href="aktualnosc.php?id={$obj->id}" style="text-decoration: none;">{$obj->temat}</a></h5>
	<h6><a href="aktualnosc.php?id={$obj->id}" style="text-decoration: none; color: white;">{$obj->data}</a></h6>
		<span><a href="aktualnosc.php?id={$obj->id}" style="text-decoration: none; color: gray;">{$temat}</a></span>
</div>
END;
}
?>
</div>

<?php
    include "side_part/footer.php";
    include "side_part/dol.php";
?>