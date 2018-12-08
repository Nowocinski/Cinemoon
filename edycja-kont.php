<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

    if(!isset($_SESSION['typ_konta']) || $_SESSION['typ_konta']!= 'administratorIT')
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
	
	//Do pracownika
	$zapytanie = $polaczenie->prepare("SELECT * FROM pracownicy");
	$zapytanie->execute();
	
	//Do klienta
	$zapytanie2 = $polaczenie->prepare("SELECT * FROM klienci");
	$zapytanie2->execute();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edycja kont</title>

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
		function edytujPrac(id, imie, nazwisko, email, nr_telefonu, typ_konta, miejscowosc, adres)
		{
			var start = '<form id="fP" method="post" action="edycja-prac.php">';
			var str1 = '<label>Imię</label><input type="text" class="form-control" value='+imie+' style="color: black;" name="imie" required>';
			var str2 = '<label>Nazwisko</label><input type="text" class="form-control" value='+nazwisko+' style="color: black;" name="nazwisko" required>';
			var str3 = '<label>Adres e-mail</label><input type="text" class="form-control" value='+email+' style="color: black;" name="email" required>';
			var str4 = '<label>Numer telefonu</label><input type="text" class="form-control" value='+nr_telefonu+' style="color: black;" name="tele" required>';
			var str5 = '<label>Miejscowość</label><input type="text" class="form-control" value='+miejscowosc+' style="color: black;" name="miejscowosc" required>';
			var str6 = '<label>Adres</label><input type="text" class="form-control" value="'+adres+'" style="color: black;" name="adres" required>';
			var str7 = '<label>Typ konta</label><select class="form-control" style="color: black;" name="typ">';
			
			if(typ_konta == 'administratorIT')
				var str8 = '<option value="administratorIT" selected>administratorIT</option>';
			else
				var str8 = '<option value="administratorIT">administratorIT</option>';
			
			if(typ_konta == 'pracownik')
				var str9 = '<option value="pracownik" selected>pracownik</option>';
			else
				var str9 = '<option value="pracownik">pracownik</option>';
			
			if(typ_konta == 'menadzerPracownikow')
				var str10 = '<option value="menadzerPracownikow" selected>menadzerPracownikow</option>';
			else
				var str10 = '<option value="menadzerPracownikow">menadzerPracownikow</option>';
			
			var str11 = '</select>';
			var koniec = '</form>';
			
			document.getElementById("dPracownika").innerHTML = start + str1 + str2 + str3 + str4 + str5 + str6 + str7 + str8 + str9 + str10 + str11 + koniec;
			document.getElementById("podmiento").innerHTML = '<button type="submit" class="btn btn-primary" form="fP" name="id" value="'+id+'">Edytuj</button><button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>';
		}
	</script>
</head>
<body>




<!-- Pracownik -->
<div id="ePrac" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edycja danych konta pracownika</h4>
      </div>
      <div id="dPracownika" class="modal-body">
        <!-- Do podmiany -->
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
                <a class="navbar-brand" href="adminIT-info.php">Panel administratora IT</a>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul id="active" class="nav navbar-nav side-nav">
                  <li><a href="adminIT-info.php"><i class="fa fa-area-chart"></i> Strona startowa</a></li>
                  <li><a href="adminIT-repertuar.php"><i class="fa fa-film"></i> Repertuar</a></li>
                  <li><a href="dodaj-seans.php"><i class="fa fa-tasks"></i> Nowy seans</a></li>
                  <li><a href="dodaj-film.php"><i class="fa fa-video-camera"></i> Dodaj film</a></li>
                  <li><a href="dodaj-sale.php"><i class="fa fa-university"></i> Dodaj sale</a></li>
				  <li class="selected"><a href="edycja-kont.php"><i class="fa fa-id-card" aria-hidden="true"></i> Edycja kont</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right navbar-user">
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
                    <h1><small>Konto administratora IT</small></h1>
                </div>
            </div>
            <div class="row">

				<div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Konta pracowników</h3>
                        </div>
                        <div class="panel-body">
							<div class="table-responsive">
							  <table class="table">
								<tr>
									<th>ID</th>
									<th>Imię</th>
									<th>Nazwisko</th>
									<th>Typ konta</th>
									<th>Numer telefonu</th>
									<th>Dane konta</th>
									<th>Hasło</th>
								</tr>
<?php
while($obj = $zapytanie->fetch(PDO::FETCH_OBJ))
{
echo<<<END
								<tr>
									<td>{$obj->id_pracownika}</td>
									<td>{$obj->imie}</td>
									<td>{$obj->nazwisko}</td>
									<td>{$obj->typ_konta}</td>
									<td>{$obj->nr_telefonu}</td>
									<td><button type="button" class="btn btn-primary" onclick="edytujPrac({$obj->id_pracownika},'{$obj->imie}','{$obj->nazwisko}','{$obj->email}',{$obj->nr_telefonu},'{$obj->typ_konta}','{$obj->miejscowosc}','{$obj->adres}')" data-toggle="modal" data-target="#ePrac">Edytuj</button></td>
									<td><button type="button" class="btn btn-warning">Edytuj</button></td>
								</tr>
END;
}
?>
							  </table>
							</div>
						</div>
                </div>
            </div>
			
				<div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Konta klientów</h3>
                        </div>
                        <div class="panel-body">
							<div class="table-responsive">
							  <table class="table">
								<tr>
									<th>ID</th>
									<th>Imię</th>
									<th>Nazwisko</th>
									<th>Typ konta</th>
									<th>Numer telefonu</th>
									<th>Dane konta</th>
									<th>Hasło</th>
								</tr>
<?php
while($obj = $zapytanie2->fetch(PDO::FETCH_OBJ))
{
echo<<<END
								<tr>
									<td>{$obj->id_klienta}</td>
									<td>{$obj->imie}</td>
									<td>{$obj->nazwisko}</td>
									<td>{$obj->typ}</td>
									<td>
END;
	if($obj->nr_telefonu == '') echo '<span style="color: gray">Nie podano</span>';
	else echo $obj->nr_telefonu;
echo<<<END
									</td>
									<td><button type="submit" class="btn btn-primary">Edytuj</button></td>
									<td><button type="button" class="btn btn-warning">Edytuj</button></td>
								</tr>
END;
}
?>
							  </table>
							</div>
						</div>
                </div>
            </div>
           </div>
        </div>
    </div>
</body>
</html>
