<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

    if(!isset($_SESSION['typ_konta']) || $_SESSION['typ_konta']!= 'administratorIT')
    {
      header('Location: index.php');
      exit();
    }

	

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nowa sala</title>

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
      <form action="dodawanie-pracownika.php" method="post">
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
                  <li><a href="dodaj-seans.php"><i class="fa fa-tasks"></i> Nowy seans</a></li>
                  <li><a href="dodaj-film.php"><i class="fa fa-video-camera"></i> Dodaj film</a></li>
                  <li><a href="dodaj-sale.php"><i class="fa fa-university"></i> Dodaj sale</a></li>
				  <li><a href="edycja-kont.php"><i class="fa fa-id-card" aria-hidden="true"></i> Edycja kont</a></li>
				  <li class="selected"><a href="dodaj-pracownika.php"><i class="fa fa-handshake-o" aria-hidden="true"></i> Dodaj pracownika</a></li>
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

       <div>
        <div class="row text-center">
<?php
          if(isset($_SESSION['blad_oznaczeniasali']))
          {
echo<<<END
   <div class="col-lg-12">
      <div class="alert alert-dismissable alert-danger">
          <button data-dismiss="alert" class="close" type="button">&times;</button>
          Sala o podanej nazwie jest już w bazie
      </div>
    </div>
END;
            unset($_SESSION['blad_oznaczeniasali']);
          }
          if(isset($_SESSION['sukces']))
          {
echo<<<END
  <div class="col-lg-12">
      <div class="alert alert-dismissable alert-success">
          <button data-dismiss="alert" class="close" type="button">&times;</button>
          Pomyślnie dodano nową sale
      </div>
  </div>
END;
            unset($_SESSION['sukces']);
          }
?>
            <h2>Nowy pracownik</h2>
        </div>
        <div class="mb-1">
            <label for="tytul" class="col-md-2">
                Imię:
            </label>
            <div class="col-md-9">
              <input type="text" class="form-control" name="imie" minlength="2" maxlength="30" placeholder="Podaj imię" style="color: black;" required>
            </div>
        </div>
        <div class="mb-1">
            <label for="tytul" class="col-md-2">
                Nazwisko:
            </label>
            <div class="col-md-9">
              <input type="text" class="form-control" name="nazwisko" placeholder="Podaj Nazwisko" minlength="2" maxlength="30" style="color: black;" required>
            </div>
        </div>
        <div class="mb-1">
		<label for="tytul" class="col-md-2">
                Typ konta:
        </label>
		<div class="col-md-9">
            <select class="form-control" style="color: black;" name="typ">
				<option value="pracownik">pracownik</option>
				<option value="administratorIT">administratorIT</option>
				<option value="menadzerPracownikow">menadzerPracownikow</option>
			</select>
		</div>
        </div>
		<div class="mb-1">
            <label for="tytul" class="col-md-2">
                Adres e-mail:
            </label>
            <div class="col-md-9">
              <input type="email" class="form-control" name="email" placeholder="Podaj adres e-mail" style="color: black;" required>
            </div>
        </div>
		<div class="mb-1">
            <label for="tytul" class="col-md-2">
                Hasło:
            </label>
            <div class="col-md-9">
              <input type="password" class="form-control" name="haslo" placeholder="Podaj hasło" style="color: black;" required>
            </div>
        </div>
		<div class="mb-1">
            <label for="tytul" class="col-md-2">
                Miejscowość:
            </label>
            <div class="col-md-9">
              <input type="text" class="form-control" name="miejscowosc" placeholder="Podaj miejscowosc" style="color: black;" required>
            </div>
        </div>
		<div class="mb-1">
            <label for="tytul" class="col-md-2">
                Adres:
            </label>
            <div class="col-md-9">
              <input type="text" class="form-control" name="adres" placeholder="Podaj adres" style="color: black;" required>
            </div>
        </div>
		<div class="mb-1">
            <label for="tytul" class="col-md-2">
                Numer telofonu:
            </label>
            <div class="col-md-9">
              <input type="number" class="form-control" name="telefon" placeholder="Podaj numer telefonu" min="100000000" max="999999999" style="color: black;" required>
            </div>
        </div>
        <div>
            <div class="col-md-2">
            </div>
            <div class="col-md-10">
                <label>
                    <input type="checkbox" required> Tak, chcę dodać nowego pracownika</label>
            </div>
        </div>
        <div>
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <button type="submit" class="btn btn-info">
                    Dodaj pracownika
                </button>
            </div>
        </div>
    </div>
    </form>
    </div>

</body>
</html>
