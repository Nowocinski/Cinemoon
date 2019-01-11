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
	
	$zapytanie = $polaczenie->prepare('SELECT id, czas_od, czas_do, info_o_pracy, status FROM harmonogram_prac WHERE id_prac=:id AND YEARWEEK(dzien)=YEARWEEK(NOW()) AND DAYOFWEEK(dzien)=:dtyg ORDER BY dzien ASC');
	$zapytanie->bindValue(':id', $_SESSION['id_pracownika'], PDO::PARAM_INT);
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
                    <li class="selected"><a href="menadzer-pracownikow.php"><i class="fa fa-area-chart"></i> Strona startowa</a></li>
                    <li><a href="harmonogram.php"><i class="fa fa-briefcase" aria-hidden="true"></i> Harmonogram pracowników</a></li>
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

				<div class="col-lg-9">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Harmonogram prac</h3>
                        </div>
                        <div class="panel-body">
<!------------------------------------------------------------------------------------------------------------------->
<!-- Harmonogram prac -->
<?php
$dnitygodnia = array("Niedziela", "Poniedziałek", "Wtorek", "Środa", "Czwartek" , "Piątek", "Sobota");

for($i=1; $i<8; $i++)
{
echo<<<END
						<div class="table-responsive">
						  <table class="table">
							<caption style="font-size: 20px; text-align: center;">{$dnitygodnia[$i-1]}</caption>
END;

$zapytanie->bindValue(':dtyg', $i, PDO::PARAM_INT);
$zapytanie->execute();

if($zapytanie->rowCount() == 0)
	echo '<thead><tr><td><span style="color: gray;">Nie masz aktualnie przypisanej żadnej pracy na ten dzień</span></td></tr></thead>';
else
{
echo<<<END
	<thead>
		<tr>
			<th style="width:15%">Od kiedy</th>
			<th style="width:15%">Do kiedy</th>
			<th style="width:45%">Opis</th>
			<th style="width:35%">Status</th>
		</tr>
	</thead>
END;

while($obj = $zapytanie->fetch(PDO::FETCH_OBJ))
{
echo<<<END
	<tbody>
		<tr>
			<td>{$obj->czas_od}</td>
			<td>{$obj->czas_do}</td>
			<td>{$obj->info_o_pracy}</td>
			<td>
END;

if($obj->status == 1)
echo<<<END
					Wykonano
				</td>
			</tr>
END;

else
echo<<<END
					<form method="post" action="skrypt_wykonanie_pracy.php">
						<button type="submit" class="btn btn-danger" name="numer_pracy" value="{$obj->id}">
							Dodaj wykonanie
						</button>
					</form>
				</td>
			</tr>
END;
}

echo<<<END
		</tr>
	</tbody>
END;
}

echo<<<END
		</table>
	</div>
END;
}
?>
<!------------------------------------------------------------------------------------------------------------------->
						</div>
                </div>
            </div>
				
                <div class="col-md-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-rss"></i> Twoje dane</h3>
                        </div>
                        <div class="panel-body feed">
                            <section class="feed-item">
                                <div class="icon pull-left">
                                    <i class="fa fa-id-card-o"></i>
                                </div>
                                <div class="feed-item-body">
                                    <div class="text">
                                        <?php echo $_SESSION['imie'].' '.$_SESSION['nazwisko']; ?>
                                    </div>
                                </div>
                            </section>
                            <section class="feed-item">
                                <div class="icon pull-left">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="feed-item-body">
                                    <div class="text">
                                        Menadżer pracowników
                                    </div>
                                </div>
                            </section>
                            <section class="feed-item">
                                <div class="icon pull-left">
                                    <i class="fa fa-envelope-o"></i>
                                </div>
                                <div class="feed-item-body">
                                    <div class="text">
                                        <?php echo $_SESSION['email'];?>
                                    </div>
                                </div>
                            </section>
                            <section class="feed-item">
                                <div class="icon pull-left">
                                    <i class="fa fa-building-o"></i>
                                </div>
                                <div class="feed-item-body">
                                    <div class="text">
                                        <?php echo $_SESSION['miejscowosc']; ?>
                                    </div>
                                </div>
                            </section>
                            <section class="feed-item">
                                <div class="icon pull-left">
                                    <i class="fa fa-map-marker"></i>
                                </div>
                                <div class="feed-item-body">
                                    <div class="text">
                                        <?php echo $_SESSION['adres']; ?>
                                    </div>
                                </div>
                            </section>
                            <section class="feed-item">
                                <div class="icon pull-left">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <div class="feed-item-body">
                                    <div class="text">
                                        <?php echo $_SESSION['nr_telefonu'];?>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
