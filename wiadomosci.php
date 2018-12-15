<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

    if(!isset($_SESSION['typ_konta']) || $_SESSION['typ_konta']!= 'specjalistaDSObslugi')
    {
      header('Location: index.php');
      exit();
    }
	
	require_once 'connect.php';
	
	try
	{
		$polaczenie = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
	}
	catch(PDOException $e)
	{
		echo "Nie można nazwiązać połączenia z bazą danych";
	}
	
	$zapytanie = $polaczenie->prepare('SELECT * FROM wiadomosci ORDER BY id_wiadomosci ASC');
	$zapytanie->execute();
	
	$zapytanie2 = $polaczenie->prepare('SELECT id FROM usuniniete_konta');
	$zapytanie2->execute();

	$ile = $zapytanie2->rowCount();

	$zapytanie3 = $polaczenie->prepare('SELECT id FROM usuniniete_konta WHERE powod_usuniecia=:pu');
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konto specjalisty ds. obsługi</title>

    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/local.css" />

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

    <!-- you need to include the shieldui css and js assets in order for the charts to work -->
    <link rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/light-bootstrap/all.min.css" />
    <link id="gridcss" rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/dark-bootstrap/all.min.css" />

    <script type="text/javascript" src="http://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>
    <script type="text/javascript" src="http://www.prepbootstrap.com/Content/js/gridData.js"></script>
	<script>
		function wyswietl(sekcja, tresc)
		{
			document.getElementById('div'+sekcja).innerHTML = '<td colspan="8">'+tresc+'</td>';
		}
	</script>
</head>
<body>
    <div id="wrapper">
          <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="specjalista-ds-obslugi.php">Panel specjalisty ds. obsługi</a>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul id="active" class="nav navbar-nav side-nav">
                    <li><a href="specjalista-ds-obslugi.php"><i class="fa fa-area-chart"></i> Strona startowa</a></li>
                    <li class="selected"><a href="wiadomosci.php"><i class="fa fa-envelope" aria-hidden="true"></i> Wiadomości</a></li>
					<li><a href="dodaj-aktualnosci.php"><i class="fa fa-newspaper-o" aria-hidden="true"></i> Dodaj aktualności</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right navbar-user">
                    <li class="dropdown messages-dropdown">
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo ' '.$_SESSION['imie'].' '.$_SESSION['nazwisko'].' '; ?><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="zarzadzanie-kontem.php"><i class="fa fa-gear"></i> Ustawienia konta</a></li>
                            <li class="divider"></li>
                            <li><a href="wyloguj.php"><i class="fa fa-power-off"></i> Wyloguj się </a></li>

                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1><small>Konto specjalisty ds. obsługi</small></h1>
                </div>
            </div>
            <div class="row">
				<div class="col-lg-12">
					<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Wiadkomości</h3>
							</div>
							<div class="panel-body">
<?php
if($zapytanie->rowCount() != 0)
{
echo<<<END
								<div class="table-responsive">
								  <table class="table text-center">
									<thead>
										<tr>
											<th class="text-center">ID</th>
											<th class="text-center">Imię</th>
											<th class="text-center">Nazwisko</th>
											<th class="text-center">E-mail</th>
											<th class="text-center">Temat</th>
											<th class="text-center">Data wysłania</th>
											<th class="text-center">Treść</th>
											<th class="text-center">Usunięcie</th>
										</tr>
									</thead>
									<tbody>
END;
while($obj = $zapytanie->fetch(PDO::FETCH_OBJ))
{

$tresc = nl2br( $obj->tresc );

echo<<<END
<tr>
	<td>{$obj->id_wiadomosci}</td>
	<td>{$obj->imie}</td>
	<td>{$obj->nazwisko}</td>
	<td>{$obj->email}</td>
	<td>{$obj->temat}</td>
	<td>{$obj->data_wyslania}</td>
	<td><button class="btn btn-primary" onclick="wyswietl('{$obj->id_wiadomosci}','{$tresc}')" href="#div{$obj->id_wiadomosci}" data-toggle="collapse">Szczegóły</button></td>
	<td><form action="usun-wiadomosc.php" method="post"><button class="btn btn-danger" name="id" value="{$obj->id_wiadomosci}">Usuń</button></form></td>
</tr>
<tr id="div{$obj->id_wiadomosci}">

</tr>
END;
}
echo<<<END
</tbody>
								  </table>
								</div>
END;
}
else
{
	echo '<span style="color: gray;">Brak wiadomości</span>';
}
?>			
							</div>
					</div>
				</div>


<div class="col-lg-12">
	<div class="panel panel-warning">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Usunięte konta</h3>
		</div>
		<div class="panel-body">
<!---------------------------------------------------------------------------------------------->
<?php
	$zapytanie3->bindValue(':pu', 'Nieintuicyjność działania konta', PDO::PARAM_STR);
	$zapytanie3->execute();
?>
<div class="progress">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow=<?php echo '"'.$zapytanie3->rowCount().'"';?>
  aria-valuemin="0" aria-valuemax=<?php echo '"'.$ile.'" '; ?> style="width:<?php echo $zapytanie3->rowCount()*100/$ile;?>%">
    Nieintuicyjność
  </div>
<?php
	$zapytanie3->bindValue(':pu', 'Brak wspracia ze strony administracji', PDO::PARAM_STR);
	$zapytanie3->execute();
?>
  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow=<?php echo '"'.$zapytanie3->rowCount().'"';?>
  aria-valuemin="0" aria-valuemax=<?php echo '"'.$ile.'" '; ?> style="width:<?php echo $zapytanie3->rowCount()*100/$ile;?>%">
    Brak wspracia
  </div>
<?php
	$zapytanie3->bindValue(':pu', 'Duża ilość błędów podczas użytkowania konta', PDO::PARAM_STR);
	$zapytanie3->execute();
?>
  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow=<?php echo '"'.$zapytanie3->rowCount().'"';?> aria-valuemin="0" aria-valuemax=<?php echo '"'.$ile.'" '; ?> style="width:<?php echo $zapytanie3->rowCount()*100/$ile;?>%">
    Duża ilość błędów
  </div>
<?php
	$zapytanie3->bindValue(':pu', 'Ciężko powiedzieć', PDO::PARAM_STR);
	$zapytanie3->execute();
?>
  <div class="progress-bar progress-bar-danger" aria-valuenow=<?php echo '"'.$zapytanie3->rowCount().'"';?>
  aria-valuemin="0" aria-valuemax=<?php echo '"'.$ile.'" '; ?> style="width:<?php echo $zapytanie3->rowCount()*100/$ile;?>%">
    Ciężko powiedzieć
  </div>
</div>
 <b>
  <p>Łączna ilość usuniętych kont:  <?php echo $ile; ?></p>
<?php
	$zapytanie4 = $polaczenie->prepare('SELECT id FROM usuniniete_konta WHERE data between date_sub(now(),INTERVAL 1 MONTH) AND now()');
	$zapytanie4->execute();
?>
  <p>Ilość usuniętych kont w ciągu miesiąca: <?php echo $zapytanie4->rowCount(); ?></p>
<?php
	$zapytanie4 = $polaczenie->prepare('SELECT id FROM usuniniete_konta WHERE data between date_sub(now(),INTERVAL 1 WEEK) AND now()');
	$zapytanie4->execute();
?>
  <p>Ilość usuniętych kont w ciągu tygodnia: <?php echo $zapytanie4->rowCount(); ?></p>
 </b>
<!--------------------------------------------------------------------------------------------------->
		</div>
	</div>
</div>
        </div>
    </div>
    <!-- /#wrapper -->
</body>
</html>
