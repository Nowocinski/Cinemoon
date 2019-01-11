<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

    if(!isset($_SESSION['typ_konta']) || $_SESSION['typ_konta']!= 'administratorIT')
    {
      header('Location: index.php');
      exit();
    }

    //---------------------------------------------------------------------------------------------
        if(isset($_POST['cena']))
        {
          try {
            require_once "connect.php";
            
			$polaczenie = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
			
			$zapytanie = $polaczenie->prepare("INSERT INTO repertuar VALUES ('', :sa, :fi, :da, :ce)");
			$zapytanie->bindValue(':sa', $_POST['sala'], PDO::PARAM_INT);
			$zapytanie->bindValue(':fi', $_POST['film'], PDO::PARAM_INT);
			$zapytanie->bindValue(':da', $_POST['data'].' '.$_POST['czas'], PDO::PARAM_STR);
			$zapytanie->bindValue(':ce', $_POST['cena'], PDO::PARAM_INT);
			$zapytanie->execute();

			$_SESSION['sukces'] = true;

          } catch (Exception $e) {
            $_SESSION['blad_rezerwacji'] = true;
          }

        }
    //---------------------------------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nowy seans</title>

    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/local.css" />

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

      <style>

        div {
            padding-bottom:20px;
        }

    </style>
	
	<script>
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
                <a class="navbar-brand" href="adminIT-info.php">Powróć do strony startowej</a>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                  <li><a href="adminIT-info.php"><i class="fa fa-area-chart"></i> Strona startowa</a></li>
                  <li><a href="adminIT-repertuar.php"><i class="fa fa-film"></i> Repertuar</a></li>
                  <li class="selected"><a href="dodaj-seans.php"><i class="fa fa-tasks"></i> Nowy seans</a></li>
                  <li><a href="dodaj-film.php"><i class="fa fa-video-camera"></i> Dodaj film</a></li>
                  <li><a href="dodaj-sale.php"><i class="fa fa-university"></i> Dodaj sale</a></li>
				  <li><a href="edycja-kont.php"><i class="fa fa-id-card" aria-hidden="true"></i> Zarządzanie listą kont</a></li>
				  <li><a href="dodaj-pracownika.php"><i class="fa fa-handshake-o" aria-hidden="true"></i> Dodaj pracownika</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right navbar-user">
                     <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['imie'].' '.$_SESSION['nazwisko'];?><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="zarzadzanie-kontem.php"><i class="fa fa-gear"></i> Ustawienia konta</a></li>
                            <li class="divider"></li>
                            <li><a href="wyloguj.php"><i class="fa fa-power-off"></i> Wyloguj się</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
<form action="dodaj-seans.php" method="post" id="forma">
       <div>
        <div class="row text-center">
          <?php
            if(isset($_SESSION['blad_rezerwacji']))
            {
echo<<<END
     <div class="col-lg-12">
        <div class="alert alert-dismissable alert-danger">
            <button data-dismiss="alert" class="close" type="button">&times;</button>
            W podanym czasie sala jest zajęta
        </div>
      </div>
END;
              unset($_SESSION['blad_rezerwacji']);
            }
            if(isset($_SESSION['sukces']))
            {
echo<<<END
    <div class="col-lg-12">
        <div class="alert alert-dismissable alert-success">
            <button data-dismiss="alert" class="close" type="button">&times;</button>
            Pomyślnie dodano nowy seans
        </div>
    </div>
END;
              unset($_SESSION['sukces']);
            }
          ?>
            <h2>Nowy seans</h2>
        </div>
        <div class="mb-1">
            <label for="tytul" class="col-md-2">
                Tytuł filmu:
            </label>
            <div class="col-md-9">
                  <select name="film" class="form-control" class="form-control" style="color: black;">
<?php
//Wyłączenie worningów i włączenie wyświetlania wyjątków
mysqli_report(MYSQLI_REPORT_STRICT);
try
{
  require_once "connect.php";
  $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

  if($polaczenie->connect_errno != 0)
          throw new Exception(mysqli_connect_errno());
  else
  {
    $polaczenie->query("SET NAMES utf8");
    $rezultat = $polaczenie->query("SELECT tytul, id_filmu FROM filmy");
    if(!$rezultat)
        throw new Exception($polaczenie->error);
    else {
      while($wiersz = $rezultat->fetch_assoc())
        echo '<option value="'.$wiersz['id_filmu'].'">'.$wiersz['tytul'].'</option>';
    }

    $rezultat->free_result();
    $polaczenie->close();
  }
}
catch (Exception $e)
{
  echo '<span style="color: red;">Błąd serwera. Spróbuj zarejestrować się później</span>';
  echo '<br>Informacja deweloperska: '.$e;
}

?>
                  </select>
            </div>
        </div>
        <div>
            <label for="sala" class="col-md-2">
                Numer sali:
            </label>
            <div class="col-md-9">
                <select name="sala" class="form-control" class="form-control" style="color: black;">
                  <?php
                  //Wyłączenie worningów i włączenie wyświetlania wyjątków
                  mysqli_report(MYSQLI_REPORT_STRICT);
                  try
                  {
                    require_once "connect.php";
                    $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

                    if($polaczenie->connect_errno != 0)
                            throw new Exception(mysqli_connect_errno());
                    else
                    {
                      $polaczenie->query("SET NAMES utf8");
                      $rezultat = $polaczenie->query("SELECT nr_sali, id_sali FROM sale");
                      if(!$rezultat)
                          throw new Exception($polaczenie->error);
                      else {
                        while($wiersz = $rezultat->fetch_assoc())
                          echo '<option value="'.$wiersz['id_sali'].'">'.$wiersz['nr_sali'].'</option>';
                      }

                      $rezultat->free_result();
                      $polaczenie->close();
                    }
                  }
                  catch (Exception $e)
                  {
                    echo '<span style="color: red;">Błąd serwera. Spróbuj zarejestrować się później</span>';
                    echo '<br>Informacja deweloperska: '.$e;
                  }

                  ?>
                </select>
            </div>
        </div>
        <div>
            <label for="emailaddress" class="col-md-2">
                Data:
            </label>
            <div class="col-md-9">
                <input type="date" class="form-control" name="data" style="color: black;" min="<?php echo date("Y-m-d", strtotime("tomorrow")); ?>" required>
                <p class="help-block">
                    Uwaga: Data seansu musi być późniejsza od daty dzisiejszej
                </p>
            </div>
        </div>
        <div>
            <label for="password" class="col-md-2">
                Czas:
            </label>
            <div class="col-md-9">
                <input type="time" class="form-control" name="czas" style="color: black;" required>
            </div>
        </div>
        <div>
            <label for="country" class="col-md-2">
                Cena biletu:
            </label>
            <div class="col-md-9">
                <input type="number" class="form-control" name="cena" placeholder="Cena biletu w zł" step="0.01" min="0" style="color: black;" required>
            </div>
        </div>
        <div>
            <div class="col-md-2">
            </div>
            <div class="col-md-10">
                <label>
                    <input type="checkbox" required> Tak, chcę dodać nowy seans</label>
            </div>
        </div>
        <div>
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <button type="submit" class="btn btn-info">
                    Dodaj seans
                </button>
            </div>
        </div>
    </div>
  </form>
    </div>

</body>
</html>
