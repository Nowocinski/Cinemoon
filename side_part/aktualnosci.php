<div class="dane-konta2 pt-3 pb-3 text-center" style="background-color: #071418">
	<h2>Aktualności</h2>
	
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

$zapytanie = $polaczenie->prepare('SELECT temat, tresc, data FROM aktualnosci ORDER BY data DESC LIMIT 3');
$zapytanie->execute();

while($obj = $zapytanie->fetch(PDO::FETCH_OBJ))
{
$temat = substr($obj->tresc,0,600);
if(strlen($obj->tresc) > 599) $temat.='...';
echo<<<END
<div class="mb-3" style="word-wrap: break-word; padding-left: 45px; padding-right: 45px;">
	<h5 style="color: #A9A9A9;">{$obj->temat}</h5>
	<h6 style="text-align: center;">{$obj->data}</h6>
		<span style="color: gray;">{$temat}</span>
</div>
END;
}
?>
</div>