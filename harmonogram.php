<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

    if(!isset($_SESSION['typ_konta']) || $_SESSION['typ_konta']!= 'menadzerPracownikow')
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
	
	$zapytanie = $polaczenie->prepare("SELECT * FROM pracownicy");
	$zapytanie->execute();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menadżer pracowników</title>

    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/local.css" />

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/light-bootstrap/all.min.css" />
    <link id="gridcss" rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/dark-bootstrap/all.min.css" />

    <script type="text/javascript" src="http://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>
    <script type="text/javascript" src="http://www.prepbootstrap.com/Content/js/gridData.js"></script>
	<script>
		function przeslanie(num)
		{
			document.getElementById("form-usuwania").innerHTML = '<button name="element" type="submit" class="btn btn-danger" value="' + num + '">Tak, chce usunąć</button><button type="button" class="btn btn-default" data-dismiss="modal">Nie chce</button>';
		}
		
		function przeslanie2(num, rok, miesiac, dzien, min, sek, min_do, sek_do, opis)
		{
			//document.write(opis);
			var data = rok + '-' + miesiac + '-' + dzien;
			var od = min + ':' + sek;
			var _do = min_do + ':' + sek_do;

			var str1 = '<label>Dzień</label><input type="date" min="<?php echo date("Y-m-d", strtotime("tomorrow")); ?>" class="form-control" name="dzien" value="'+data+'" style="color: black;" required>'
			var str2 = '<label>Od kiedy</label><input type="time" class="form-control" style="color: black;" name="czas_od" value="'+od+'" required>';
			var str3 = '<label>Do kiedy</label><input type="time" class="form-control" style="color: black;" name="czas_do" value="'+_do+'" required>';
			var str4 = '<label>Opis</label><input type="text" class="form-control" style="color: black;" name="opis" value="'+opis+'" required>';

			document.getElementById("form-edycji").innerHTML = str1 + str2 + str3 + str4;

			document.getElementById("dopodmiany").innerHTML = '<button type="submit" class="btn btn-primary" form="form-edycji" name="id" value="'+num+'">Edytuj</button><button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>';
		}
		
		function wyslij(num)
		{
			document.getElementById("podmiento").innerHTML = '<button type="submit" class="btn btn-warning" form="dodaj" name="id" value="'+num+'">Dodaj</button><button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>';
		}
	</script>
</head>
<body>

<!-- Modal do usuwania -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Usuwanie zdarzenie</h4>
      </div>
      <div class="modal-body">
        <p>Czy na pewno chcesz usunąć to wydarzenie z harmonogramu?</p>
      </div>
      <div class="modal-footer">
		<form id="form-usuwania" action="usun-z-harmonogramu.php" method="post"></form>
        <!--button type="button" class="btn btn-default" data-dismiss="modal">Nie chce</button-->
      </div>
    </div>

  </div>
</div>

<!-- Modal do edycji -->
<div id="modelEdycji" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edycja harmonogramu</h4>
      </div>
      <div class="modal-body">
        <form id="form-edycji" action="edycja-harmonogramu.php" method="post"></form>
      </div>
      <div class="modal-footer">
		<div id="dopodmiany"></div>
        <!--button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button-->
      </div>
    </div>

  </div>
</div>

<!-- Formularz dodawania zdarzenia -->
<div id="formularz" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Dodaj zdarzenie</h4>
      </div>
      <div class="modal-body">
	  <form id="dodaj" action="dodaj-do-harmonogramu.php" method="post">
			<label>Dzień</label>
			<input type="date" min="<?php echo date("Y-m-d", strtotime("tomorrow")); ?>" class="form-control" name="dzien" style="color: black;" required>
			<label>Od kiedy</label>
			<input type="time" class="form-control" style="color: black;" name="czas_od" required>
			<label>Do kiedy</label>
			<input type="time" class="form-control" style="color: black;" name="czas_do" required>
			<label>Opis</label>
			<input type="text" class="form-control" style="color: black;" name="opis" required>
		</form>
      </div>
      <div class="modal-footer">
        <div id="podmiento"></div>
        <!--button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button-->
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
                <a class="navbar-brand" href="menadzer-pracownikow.php">Panel menadżera pracowników</a>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul id="active" class="nav navbar-nav side-nav">
                    <li><a href="menadzer-pracownikow.php"><i class="fa fa-area-chart"></i> Strona startowa</a></li>
                    <li class="selected"><a href="harmonogram.php"><i class="fa fa-briefcase" aria-hidden="true"></i> Harmonogram pracowników</a></li>
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
                    <h1><small>Konto menadżera pracowników</small></h1>
                </div>
            </div>
            <div class="row">

				<div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Harmonogram prac</h3>
                        </div>
                        <div class="panel-body">
<?php
while($obj = $zapytanie->fetch(PDO::FETCH_OBJ))
{
$zapytanie2 = $polaczenie->prepare("SELECT * FROM harmonogram_prac WHERE id_prac=:id AND CAST(CONCAT(dzien,' ',czas_od) as DATETIME) >= CAST(CONCAT(CURDATE(),' ',CURTIME()) as DATETIME) ORDER BY dzien ASC");
$zapytanie2->bindValue(':id', $obj->id_pracownika, PDO::PARAM_INT);
$zapytanie2->execute();

	echo '<table class="table">';
	echo '<caption style="font-size: 20px;">'.$obj->imie.' '.$obj->nazwisko.' <span style="color: white; font-size: 15px;">('.$obj->typ_konta.') <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" onclick="wyslij('.$obj->id_pracownika.')" data-target="#formularz">Dodaj zdarzenie</button></span><caption>';
if($zapytanie2->rowCount() == 0)
	echo '<tr><td style="color: gray;">Ta osoba nie ma przydzielonych żadnych obowiązków</td></tr>';
else
{
	echo '<thead><tr style="width: 10%; text-align: center;"><th style="width: 10%; text-align: center;">Dzień</th><th style="width: 10%; text-align: center;">Od kiedy</th><th style="width: 10%; text-align: center;">Do kiedy</th><th style="width: 40%; text-align: center;">Opis</th><th style="width: 10%; text-align: center;">Edycja</th><th style="width: 10%; text-align: center;">Usunięcie</th><th style="width: 30%; text-align: center;">Status</th></tr></thead>';
	while($obj2 = $zapytanie2->fetch(PDO::FETCH_OBJ))
	{
		if($obj2->status == 0) $status = 'Do wykonania';
		else $status = 'Wykonano';

		echo '<tr><td style="width: 10%; text-align: center;">'.$obj2->dzien.'</td><td style="width: 10%; text-align: center;">'.$obj2->czas_od.'</td><td style="width: 10%; text-align: center;">'.$obj2->czas_do.'</td><td style="width: 40%; text-align: center;">'.$obj2->info_o_pracy.'</td><td style="width: 10%; text-align: center;"><button type="button" data-toggle="modal" data-target="#modelEdycji" class="btn btn-primary" onclick="przeslanie2('.$obj2->id.',\''.$obj2->dzien[0].$obj2->dzien[1].$obj2->dzien[2].$obj2->dzien[3].'\',\''.$obj2->dzien[5].$obj2->dzien[6].'\',\''.$obj2->dzien[8].$obj2->dzien[9].'\',\''.$obj2->czas_od[0].$obj2->czas_od[1].'\',\''.$obj2->czas_od[3].$obj2->czas_od[4].'\',\''.$obj2->czas_do[0].$obj2->czas_do[1].'\',\''.$obj2->czas_do[3].$obj2->czas_do[4].'\',\''.$obj2->info_o_pracy.'\')">Edytuj</button></td><td style="width: 10%; text-align: center;"><button onclick="przeslanie('.$obj2->id.')" data-target="#myModal" data-toggle="modal" name="usun" type="button" class="btn btn-danger">Usuń</button></td><td style="width: 30%; text-align: center;">'.$status.'</td></tr>';
	}
}

	echo '</table>';
}
?>
						</div>
                </div>
            </div>
           </div>
        </div>
    </div>
</body>
</html>
