<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

    if(!isset($_SESSION['typ_konta']))
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
	$zapytanie->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
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

    <link rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/light-bootstrap/all.min.css" />
    <link id="gridcss" rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/dark-bootstrap/all.min.css" />

    <script type="text/javascript" src="http://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>
    <script type="text/javascript" src="http://www.prepbootstrap.com/Content/js/gridData.js"></script>
</head>
<body>
<!-- Zmiana hasła -->
<div id="zmienHaslo" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-warning">Zmiana hasła</h4>
      </div>
      <div class="modal-body">
        <form action="haslo.php" method="post" id="zHaslo">
			<label>Podaj stare hasło</label>
			<input type="password" class="form-control" minlength="6" maxlength="30" placeholder="Stare hasło" name="haslo_stare" style="color: black;" required>
			
			<label>Podaj nowe hasło</label>
			<input type="password" class="form-control" minlength="6" maxlength="30" placeholder="Nowe hasło" name="haslo_nowe" style="color: black;" required>
			
			<label>Powtórz nowe hasło</label>
			<input type="password" class="form-control" minlength="6" maxlength="30" placeholder="Powtórzeone nowe hasło" name="haslo_powtorzone" style="color: black;" required>
			
			<label><input type="checkbox" name="potwierdzenie" required> Tak, chcę zmienić hasło</label><br>
		</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning" form="zHaslo">Zmień</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
      </div>
    </div>
  </div>
</div>

<!-- Zmiana imienia -->
<div id="zmienImie" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-warning">Zmiana imienia</h4>
      </div>
      <div class="modal-body">
        <form action="imie.php" method="post" id="zImie">
			<label>Podaj nowe imię</label>
			<input type="text" class="form-control" placeholder="Nowe imię" name="imie" style="color: black;" required>
			<label><input type="checkbox" name="potwierdzenie" required> Tak, chcę zmienić imię</label><br>
		</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning" form="zImie">Zmień</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
      </div>
    </div>
  </div>
</div>


<!-- Zmiana nazwiska -->
<div id="zmienNazwisko" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-warning">Zmiana nazwisko</h4>
      </div>
      <div class="modal-body">
        <form action="nazwisko.php" method="post" id="zNazwisko">
			<label>Podaj nowe nazwisko</label>
			<input type="text" class="form-control" placeholder="Nowe nazwisko" name="nazwisko" style="color: black;" required>
			<label><input type="checkbox" name="potwierdzenie" required> Tak, chcę zmienić nazwisko</label><br>
		</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning" form="zNazwisko">Zmień</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
      </div>
    </div>
  </div>
</div>

<!-- Zmiana miejscowości -->
<div id="zmienMiejscowosc" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-warning">Zmiana miejscowość</h4>
      </div>
      <div class="modal-body">
        <form action="miejscowosc.php" method="post" id="zMiejscowosc">
			<label>Podaj nową miejscowość</label>
			<input type="text" class="form-control" placeholder="Nowa miejscowość" name="miejscowosc" style="color: black;" required>
			<label><input type="checkbox" name="potwierdzenie" required> Tak, chcę zmienić miejscowość</label><br>
		</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning" form="zMiejscowosc">Zmień</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
      </div>
    </div>
  </div>
</div>

<!-- Zmiana adresu -->
<div id="zmienAdres" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-warning">Zmiana adresu</h4>
      </div>
      <div class="modal-body">
        <form action="adres.php" method="post" id="zAdres">
			<label>Podaj nowy adres</label>
			<input type="text" class="form-control" placeholder="Nowy adres" name="adres" style="color: black;" required>
			<label><input type="checkbox" name="potwierdzenie" required> Tak, chcę zmienić adres</label><br>
		</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning" form="zAdres">Zmień</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
      </div>
    </div>
  </div>
</div>

<!-- Zmiana adresu e-mail -->
<div id="zmienEmail" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-warning">Zmiana adresu e-mail</h4>
      </div>
      <div class="modal-body">
        <form action="email.php" method="post" id="zEmail">
			<label>Podaj nowy adres e-mail</label>
			<input type="email" class="form-control" placeholder="Nowy adres e-mail" name="email" style="color: black;" required>
			<label><input type="checkbox" name="potwierdzenie" required> Tak, chcę zmienić adres e-mail</label><br>
		</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning" form="zEmail">Zmień</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
      </div>
    </div>
  </div>
</div>

<!-- Zmiana numeru -->
<div id="zmienNumer" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-warning">Zmiana numer</h4>
      </div>
      <div class="modal-body">
        <form action="numer.php" method="post" id="zNumer">
			<label>Podaj nowy numer telefonu</label>
			<input type="number" step="1" class="form-control" placeholder="Nowy numer telefonu" name="numer" style="color: black;" min="100000000" max="999999999" required>
			<label><input type="checkbox" name="potwierdzenie" required> Tak, chcę zmienić numer telefonu</label><br>
		</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning" form="zNumer">Zmień</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
      </div>
    </div>
  </div>
</div>

<!-- Usunięcie konta -->
<div id="usuniecieKonta" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-danger">Usunięcie konta</h4>
      </div>
      <div class="modal-body">
        <form action="usuwanie.php" method="post" id="uKonto">
			<p>
				Usunięcie konta spowoduje bezpowrotne utracenie wszystkich zgromadzonych danych takich jak:
				<ul>
					<li>harmonogram prac</li>
					<li>dane osobowe</li>
					<li>login</li>
					<li>hasło</li>
				</ul>
				Jesteś pewien, że chcesz tego dokonać?
			</p>
			<label><input type="checkbox" name="potwierdzenie" value="tak" required> Tak, chcę usunąć konto<br></label>
		</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger" form="uKonto">Usuń konto</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
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
                <a class="navbar-brand" href="pracownicy.php">Zarządzanie danymi konta</a>
            </div>
			<div class="collapse navbar-collapse navbar-ex1-collapse">
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
                    <h1><small>Konto pracownika Cinemoon</small></h1>
                </div>
            </div>
            <div class="row">
<?php

if(isset($_SESSION['powiadomienie']))
{
	echo $_SESSION['powiadomienie'];
	unset($_SESSION['powiadomienie']);
}

?>
				<div class="col-lg-9 text-center">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Twoje dane</h3>
                        </div>
                        <div class="panel-body">
							<div class="table-responsive">
							  <table class="table">
									<tr>
										<th class="text-center">Imię</th>
										<td><?= $_SESSION['imie']; ?></td>
										<td><button type="submit" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#zmienImie">Edytuj</button></td>
									</tr>
									<tr class="text-center">
										<th class="text-center">Nazwisko</th>
										<td><?= $_SESSION['nazwisko']; ?></td>
										<td><button type="submit" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#zmienNazwisko">Edytuj</button></td>
									</tr>
									<tr>
										<th class="text-center">Typ konta</th>
										<td><?= $_SESSION['typ_konta']; ?></td>
										<td></td>
									</tr>
									<tr>
										<th class="text-center">Numer telefonu</th>
										<td><?= $_SESSION['nr_telefonu']; ?></td>
										<td><button type="submit" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#zmienNumer">Edytuj</button></td>
									</tr>
									<tr>
										<th class="text-center">Miejscowość</th>
										<td><?= $_SESSION['miejscowosc']; ?></td>
										<td><button type="submit" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#zmienMiejscowosc">Edytuj</button></td>
									</tr>
									<tr>
										<th class="text-center">Adres</th>
										<td><?= $_SESSION['adres']; ?></td>
										<td><button type="submit" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#zmienAdres">Edytuj</button></td>
									</tr>
									<tr>
										<th class="text-center">Adres e-mail</th>
										<td><?= $_SESSION['email']; ?></td>
										<td><button type="submit" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#zmienEmail">Edytuj</button></td>
									</tr>
									<tr>
										<th class="text-center">Hasło</th>
										<td>****</td>
										<td><button type="submit" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#zmienHaslo">Edytuj</button></td>
									</tr>
							  </table>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-lg-9 text-center">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Usunięcie konta</h3>
                        </div>
                        <div class="panel-body">
							<div class="table-responsive">
							  <table class="table">
									<tr>
										<th class="text-center">Ta opcja spowoduje usunięcie konta</th>
										<td><button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#usuniecieKonta">Usuń konto</button></td>
									</tr>
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

