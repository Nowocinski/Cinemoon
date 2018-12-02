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

    <!-- you need to include the shieldui css and js assets in order for the charts to work -->
    <link rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/light-bootstrap/all.min.css" />
    <link id="gridcss" rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/dark-bootstrap/all.min.css" />

    <script type="text/javascript" src="http://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>
    <script type="text/javascript" src="http://www.prepbootstrap.com/Content/js/gridData.js"></script>
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
                <a class="navbar-brand" href="menadzer-pracownikow.php">Panel menadżera pracowników</a>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul id="active" class="nav navbar-nav side-nav">
                    <li><a href="menadzer-pracownikow.php"><i class="fa fa-area-chart"></i> Strona startowa</a></li>
                    <li class="selected"><a href="harmonogram.php"><i class="fa fa-briefcase" aria-hidden="true"></i> Harmonogram pracowników</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right navbar-user">
                    <li class="dropdown messages-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> Powiadomienia <span class="badge">2</span> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">2 New Messages</li>
                            <li class="message-preview">
                                <a href="#">
                                    <span class="avatar"><i class="fa fa-bell"></i></span>
                                    <span class="message">Security alert</span>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li class="message-preview">
                                <a href="#">
                                    <span class="avatar"><i class="fa fa-bell"></i></span>
                                    <span class="message">Security alert</span>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="#">Go to Inbox <span class="badge">2</span></a></li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo ' '.$_SESSION['imie'].' '.$_SESSION['nazwisko'].' '; ?><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
                            <li><a href="#"><i class="fa fa-gear"></i> Settings</a></li>
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
	echo '<caption style="font-size: 20px;">'.$obj->imie.' '.$obj->nazwisko.' <span style="color: white; font-size: 15px;">('.$obj->typ_konta.')</span><caption>';
if($zapytanie2->rowCount() == 0)
	echo '<tr><td style="color: gray;">Ta osoba nie ma przydzielonych żadnych obowiązków</td></tr>';
else
{
	echo '<thead><tr style="width: 10%; text-align: center;"><th style="width: 10%; text-align: center;">Dzień</th><th style="width: 10%; text-align: center;">Od kiedy</th><th style="width: 10%; text-align: center;">Do kiedy</th><th style="width: 50%; text-align: center;">Opis</th><th style="width: 10%; text-align: center;">Edycja</th><th style="width: 10%; text-align: center;">Usunięcie</th></tr></thead>';
	while($obj2 = $zapytanie2->fetch(PDO::FETCH_OBJ))
	{
		echo '<tr><td style="width: 10%; text-align: center;">'.$obj2->dzien.'</td><td style="width: 10%; text-align: center;">'.$obj2->czas_od.'</td><td style="width: 10%; text-align: center;">'.$obj2->czas_do.'</td><td style="width: 50%; text-align: center;">'.$obj2->info_o_pracy.'</td><td style="width: 10%; text-align: center;"><button type="button" class="btn btn-primary">Edytuj</button></td><td style="width: 10%; text-align: center;"><button type="button" class="btn btn-danger">Usuń</button></td></tr>';
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
