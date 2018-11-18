<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

    if(!isset($_SESSION['typ_konta']) || $_SESSION['typ_konta']!= 'pracownik')
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
	
	$zapytanie = $polaczenie->prepare('SELECT dzien, czas_od, czas_do, info_o_pracy, DAYOFWEEK(dzien) as dtyg FROM harmonogram_prac WHERE id_prac=:id AND dzien >= CURDATE() ORDER BY dzien ASC');
	$zapytanie->bindValue(':id', $_SESSION['id_pracownika'], PDO::PARAM_INT);
	$zapytanie->execute();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konto pracownika</title>

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
                <a class="navbar-brand" href="adminIT-info.php">Panel pracownika</a>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul id="active" class="nav navbar-nav side-nav">
                    <li class="selected"><a href="adminIT-info.php"><i class="fa fa-area-chart"></i> Harmonogram prac</a></li>
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
                    <h1><small>Konto pracownika Cinemoon</small></h1>
                </div>
            </div>
            <div class="row">

				<div class="col-lg-9">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Harmonogram prac</h3>
                        </div>
                        <div class="panel-body">
<?php
$dzien_tygodnia = '';
$dzien = '';
$pierwszy = true;
while($obj = $zapytanie->fetch(PDO::FETCH_OBJ))
{
	if($dzien != $obj->dzien)
	{
		$dzien = $obj->dzien;
		
		if($pierwszy == true)
		{
echo<<<END
		</tbody>
	</table>
END;
		}
		
		if($obj->dtyg == 1) $dzien_tygodnia = 'Niedziela';
		elseif($obj->dtyg == 2) $dzien_tygodnia = 'Poniedziałek';
		elseif($obj->dtyg == 3) $dzien_tygodnia = 'Wtorek';
		elseif($obj->dtyg == 4) $dzien_tygodnia = 'Środa';
		elseif($obj->dtyg == 5) $dzien_tygodnia = 'Czwartek';
		elseif($obj->dtyg == 6) $dzien_tygodnia = 'Piątek';
		elseif($obj->dtyg == 7) $dzien_tygodnia = 'Sobota';
		
echo<<<END
	<table class="table">
		<caption style="font-size: 20px;">{$dzien_tygodnia} <span style="color: white; font-size: 15px;">({$obj->dzien})</span></caption>
		<thead>
			<tr>
				<th style="width:25%">Od kiedy</th>
				<th style="width:25%">Do kiedy</th>
				<th style="width:50%">Opis</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{$obj->czas_od}</td>
				<td>{$obj->czas_do}</td>
				<td>{$obj->info_o_pracy}</td>
			</tr>
END;
	}
	
	else
	{
echo<<<END
			<tr>
				<td>{$obj->czas_od}</td>
				<td>{$obj->czas_do}</td>
				<td>{$obj->info_o_pracy}</td>
			</tr>
END;
	}
}
?>
		</tbody></table>
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
                                        Pracownik
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
