<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

    if(!isset($_SESSION['typ_konta']) || $_SESSION['typ_konta']!= 'administratorIT')
    {
      header('Location: index.php');
      exit();
    }

    if(!isset($_POST['edycja']) && !isset($_SESSION['edycja']))
    {
      header('Location: adminIT-info.php');
      exit();
    }

    //---------------------------------------------------------------------------------------------
    else
    {
    //Wyłączenie worningów i włączenie wyświetlania wyjątków
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
		if(isset($_POST['edycja']))
		{
			$edycja = $_POST['edycja'];
			$_SESSION['edycja'] = $_POST['edycja'];
		}
		
		if(isset($_SESSION['edycja']) && !isset($_POST['edycja']))
		{
			$edycja = $_SESSION['edycja'];
		}
		
        require_once "connect.php";

        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if($polaczenie->connect_errno != 0)
                throw new Exception(mysqli_connect_errno());
        else
        {
          // Kodowanie polskich znaków
          $polaczenie->query("SET NAMES utf8");
          $rezultat = $polaczenie->query("SELECT sale.id_sali ,filmy.tytul, sale.nr_sali, repertuar.czas_rozpoczecia, repertuar.cena_biletu FROM repertuar INNER JOIN filmy ON repertuar.id_filmu=filmy.id_filmu INNER JOIN sale ON repertuar.id_sali=sale.id_sali WHERE repertuar.id_repertuaru='$edycja'");
          if(!$rezultat)
              throw new Exception($polaczenie->error);
          else
          {
            $wiersz = $rezultat->fetch_assoc();
            $_SESSION['formularz_tytul'] = $wiersz['tytul'];
            $_SESSION['formularz_nr_sali'] = $wiersz['nr_sali'];
			$_SESSION['formularz_czas_rozpoczecia'] = substr($wiersz['czas_rozpoczecia'], 0, 10);
			$_SESSION['formularz_czas'] = substr($wiersz['czas_rozpoczecia'], 11, 8);
            $_SESSION['formularz_cena_biletu'] = $wiersz['cena_biletu'];
          }
          $rezultat->free_result();
          $polaczenie->close();
        }
    }
	
    catch(Exception $e)
    {
        echo '<span style="color: red">Błąd serwera. Spróbuj zarejestrować się później</span>';
        echo '<br>Informacja deweloperska: '.$e;
    }
  }
  
  if(isset($_POST['cena']))
  {
	$data = $_POST['data'];
	$czas = $_POST['czas'];
	$film = $_POST['film'];
	  
	try
	{
		mysqli_report(MYSQLI_REPORT_STRICT);
		require_once "connect.php";

        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if($polaczenie->connect_errno != 0)
            throw new Exception(mysqli_connect_errno());
		else {
			  $sala = $_POST['sala'];
              // Kodowanie polskich znaków
              $polaczenie->query("SET NAMES utf8");
              $rezultat = $polaczenie->query("SELECT repertuar.czas_rozpoczecia, filmy.min_trwania FROM repertuar INNER JOIN sale ON repertuar.id_sali=sale.id_sali INNER JOIN filmy ON repertuar.id_filmu=filmy.id_filmu WHERE sale.id_sali='$sala' AND repertuar.czas_rozpoczecia > CAST(CONCAT(CURDATE(),' ',CURTIME()) as DATETIME)");

              if(!$rezultat)
                  throw new Exception($polaczenie->error);
              else {
                while($wiersz = mysqli_fetch_assoc($rezultat))
                {
                  $datapremiery = $wiersz['czas_rozpoczecia'];
                  $jakdlugotrwa = $wiersz['min_trwania'];
                  $bufor = (string)floor($wiersz['min_trwania']/60);
                  $bufor .= ':'.(string)$wiersz['min_trwania']%60;
				  
                  $rezultat2 = $polaczenie->query("SELECT id_repertuaru FROM repertuar WHERE id_sali='$sala' AND id_repertuaru!='$edycja' AND CAST(CONCAT('$data',' ','$czas') as DATETIME) >= CAST('$datapremiery' as DATETIME) AND CAST(CONCAT('$data',' ','$czas') as DATETIME) <= CAST(CAST('$datapremiery' AS DATETIME) + CAST('$bufor' AS TIME) AS DATETIME) AND repertuar.czas_rozpoczecia > CAST(CONCAT(CURDATE(),' ',CURTIME()) as DATETIME)");
                  if(!$rezultat)
                      throw new Exception($polaczenie->error);
                  else
                  {
					$wiersz = $rezultat2->fetch_assoc();
                    if($rezultat2->num_rows > 0)
                    {
                      $_SESSION['blad_rezerwacji'] = true;
                    }
                    else
                    {
					  $cena = $_POST['cena'];
                      $data_i_czas = $data.' '.$czas;
                      if($polaczenie->query("UPDATE repertuar SET id_sali='$sala', id_filmu='$film', czas_rozpoczecia='$data_i_czas', cena_biletu='$cena' WHERE id_repertuaru='$edycja'"))
                      {
                        unset($_SESSION['blad_rezerwacji']);
                        unset($_POST['film']);
                        $_SESSION['sukces_edycji'] = true;
						header('Location: adminIT-repertuar.php');
                      }
					  
                      else
                        throw new Exception($polaczenie->error);
                    }
					$rezultat2->free_result();
                  }
                }
              }
              $rezultat->free_result();
              $polaczenie->close();
            }
	}
	
	catch(Exception $f)
	{
		echo '<span style="color: red">Błąd serwera. Spróbuj zarejestrować się później</span>';
        echo '<br>Informacja deweloperska: '.$f;
	}
  }
    //---------------------------------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edycja seansu</title>

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
                  <li class="selected"><a href="adminIT-repertuar.php"><i class="fa fa-film"></i> Repertuar</a></li>
                  <li><a href="dodaj-seans.php"><i class="fa fa-tasks"></i> Nowy seans</a></li>
                  <li><a href="dodaj-film.php"><i class="fa fa-video-camera"></i> Dodaj film</a></li>
                  <li><a href="dodaj-sale.php"><i class="fa fa-university"></i> Dodaj sale</a></li>
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
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['imie'].' '.$_SESSION['nazwisko'];?><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
                            <li><a href="#"><i class="fa fa-gear"></i> Settings</a></li>
                            <li class="divider"></li>
                            <li><a href="wyloguj.php"><i class="fa fa-power-off"></i> Wyloguj się</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
<form action="edytuj-seans.php" method="post" id="forma">
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
            <h2>Edycja seansu</h2>
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
	  {
		echo '<option value="'.$wiersz['id_filmu'].'"';
		if(isset($_SESSION['formularz_tytul']) && $_SESSION['formularz_tytul'] == $wiersz['tytul'])
		{
			echo ' selected="selected"';
			unset($_SESSION['formularz_tytul']);
		}
		echo '>'.$wiersz['tytul'].'</option>';
	  }
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
						{
							echo '<option value="'.$wiersz['id_sali'].'"';
							if(isset($_SESSION['formularz_nr_sali']) && $_SESSION['formularz_nr_sali'] == $wiersz['nr_sali'])
							{
								echo ' selected="selected"';
								unset($_SESSION['formularz_nr_sali']);
							}
							echo '>'.$wiersz['nr_sali'].'</option>';
						}
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
                <input type="date" class="form-control" name="data" style="color: black;" min="<?php echo date("Y-m-d", strtotime("tomorrow")); ?>" value="<?php echo $_SESSION['formularz_czas_rozpoczecia']; unset($_SESSION['formularz_czas_rozpoczecia']); ?>" required>
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
                <input type="time" class="form-control" name="czas" style="color: black;" value="<?php echo $_SESSION['formularz_czas']; unset($_SESSION['formularz_czas']); ?>" required>
            </div>
        </div>
        <div>
            <label for="country" class="col-md-2">
                Cena biletu:
            </label>
            <div class="col-md-9">
                <input type="number" class="form-control" name="cena" placeholder="Cena biletu w zł" step="0.01" min="0" style="color: black;" value="<?php echo $_SESSION['formularz_cena_biletu']; unset($_SESSION['formularz_cena_biletu']); ?>"required>
            </div>
        </div>
        <div>
            <div class="col-md-2">
            </div>
            <div class="col-md-10">
                <label>
                    <input type="checkbox" required> Tak, chcę zedytować seans</label>
            </div>
        </div>
        <div>
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <button type="submit" class="btn btn-info">
                    Edytuj seans
                </button>
            </div>
        </div>
    </div>
  </form>
    </div>

</body>
</html>
