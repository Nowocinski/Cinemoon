<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

    if(!isset($_SESSION['typ_konta']) || $_SESSION['typ_konta']!= 'specjalistaDSObslugi')
    {
      header('Location: index.php');
      exit();
    }
	
	if(isset($_POST['temat']))
	{
		require_once 'connect.php';
	
		try
		{
			$polaczenie = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
		}
		catch(PDOException $e)
		{
			echo "Nie można nazwiązać połączenia z bazą danych";
		}
		
		$zapytanie = $polaczenie->prepare("INSERT INTO aktualnosci VALUES ('', :tm, :tr, :dt)");
		$zapytanie->bindValue(':tm', htmlentities($_POST['temat']), PDO::PARAM_STR);
		$zapytanie->bindValue(':tr', nl2br(htmlentities($_POST['tresc'])), PDO::PARAM_STR);
		$zapytanie->bindValue(':dt', date('Y-m-d H:i:s'), PDO::PARAM_STR);
		$zapytanie->execute();
	}
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
		
		if ( window.history.replaceState ) {
		  window.history.replaceState( null, null, window.location.href );
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
                    <li><a href="wiadomosci.php"><i class="fa fa-envelope" aria-hidden="true"></i> Wiadomości</a></li>
					<li class="selected"><a href="dodaj-aktualnosci.php"><i class="fa fa-newspaper-o" aria-hidden="true"></i> Dodaj aktualności</a></li>
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
                <h1><small>Dodanie aktualności</small></h1>
				<form action="#" method="post">
				  <div class="form-group">
					<label>Temat</label>
					<input placeholder="Maksymalnie 50 znaków" type="text" class="form-control" style="color: black;" name="temat" maxlength="50" required>
				  </div>
				  <div class="form-group">
					<label>Treść</label>
					<textarea placeholder="Podaj treść wiadomości" class="form-control" style="height: 150px; color: black;"  name="tresc" required></textarea>
				  </div>
				  <div class="form-group">
					<label><input type="checkbox" required> Tak, chcę dodać wpis do sekcji aktualności</label>
				  </div>
				<button type="submit" class="btn btn-primary">Dodaj wpis</button>
				</form>
            </div>
        </div>
    </div>
    <!-- /#wrapper -->
</body>
</html>
