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
	
	$zapytanie = $polaczenie->prepare('SELECT * FROM aktualnosci');
	$zapytanie->execute();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie aktualnościami</title>

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
		if ( window.history.replaceState ) {
		  window.history.replaceState( null, null, window.location.href );
		}
		
		function podaj(id)
		{
			document.getElementById("podmiento").innerHTML = '<form action="skrypt_usuwanie_aktualnosci.php" method="post"><button class="btn btn-danger" type="submit" name="id" value="'+id+'">Tak, usuń</button><button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button></form>';
		}
	</script>
</head>
<body>

<!-- Usunięcie aktualności ze strony -->
<div id="aktu" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-danger">Usuwanie aktualności</h4>
      </div>
      <div class="modal-body">
		Kliknięcie usuń spowoduje usunięcie tego wpisu. Czy na pewno chcesz tego dokonać?
      </div>
      <div class="modal-footer">
        <div id="podmiento"></div>
      </div>
    </div>
  </div>
</div>

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
                    <li class="selected"><a href="zarzadanie-aktualnosciami.php"><i class="fa fa-area-chart"></i> Zarządzanie aktualnościami</a></li>
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
				<div class="col-md-12 text-center">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Lista dodanych aktualności</h3>
                        </div>
                        <div class="panel-body">
						<div class="table-responsive">
						  <table class="table text">
							<thead>
								<tr>
									<th style="text-align: center;">ID</th>
									<th style="text-align: center;">Temat</th>
									<th style="text-align: center;">Data</th>
									<th style="text-align: center;">Edycja</th>
									<th style="text-align: center;">Usunięcie</th>
								</tr>
							</thead>
							</tbody>
<!------------------------------------------------------------------------------------------------------------------->
<!-- Tabel z aktualnościami -->
<?php
while($obj = $zapytanie->fetch(PDO::FETCH_OBJ))
{
echo<<<END
	<tr>
		<td>{$obj->id}</td>
		<td>{$obj->temat}</td>
		<td>{$obj->data}</td>
		<td>
			<form action="edycja-aktualnosci.php" method="post">
				<button type="submit" class="btn btn-warning" name="id" value="{$obj->id}">Edytuj</button>
			</form>
		</td>
		<td>
			<button type="button" class="btn btn-danger" onclick="podaj({$obj->id})" data-toggle="modal" data-target="#aktu">Usuń</button>
		</td>
	</tr>
END;
}
?>
<!------------------------------------------------------------------------------------------------------------------->
							</tbody>
							</table>
						</div>
						</div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <!-- /#wrapper -->
</body>
</html>
