<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

    if(!isset($_SESSION['typ_konta']) || $_SESSION['typ_konta']!= 'administratorIT')
    {
      header('Location: index.php');
      exit();
    }

    if(isset($_POST['oznaczeniesali']))
    {
      $oznaczeniesali = htmlentities($_POST['oznaczeniesali'], ENT_QUOTES, "UTF-8");;
      $iloscrzedow = $_POST['iloscrzedow'];
      $iloscmiejsc = $_POST['iloscmiejsc'];

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
          // Kodowanie polskich znaków
          $polaczenie->query("SET NAMES utf8");
          $rezultat = $polaczenie->query("SELECT nr_sali FROM sale WHERE nr_sali='$oznaczeniesali' AND nr_sali!=''");
          if(!$rezultat)
              throw new Exception($polaczenie->error);
          else
          {
            if($rezultat->num_rows > 0)
            {
                $poprawna_walidacja = false;
                $_SESSION['blad_oznaczeniasali'] = '<span style="color: red;">Sala o podanej nazwie jest już w bazie</span>';
            }
            else
            {
              if($polaczenie->query("INSERT INTO sale VALUES ('','$iloscrzedow','$iloscmiejsc','$oznaczeniesali')"))
              {
                $_SESSION['sukces'] = '<span style="color: green;">Pomyślnie dodano nową sale</span>';
                unset($_POST['oznaczeniesali']);
                unset($_POST['iloscrzedow']);
                unset($_POST['iloscmiejsc']);
              }
            }
          }
          $rezultat->free_result();
          $polaczenie->close();
        }
      }
      catch(Exception $e)
      {
        echo '<span style="color: red;">Błąd serwera. Spróbuj zarejestrować się później</span>';
        //echo '<br>Informacja deweloperska: '.$e;
      }
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
      <form action="dodaj-sale.php" method="post">
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
                  <li><a href="bootstrap-grid.html"><i class="fa fa-film"></i> Repertuar</a></li>
                  <li><a href="dodaj-seans.php"><i class="fa fa-tasks"></i> Nowy seans</a></li>
                  <li><a href="dodaj-film.php"><i class="fa fa-video-camera"></i> Dodaj film</a></li>
                  <li class="selected"><a href="dodaj-sale.php"><i class="fa fa-university"></i> Dodaj sale</a></li>
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

       <div>
        <div class="row text-center">
          <?php
            if(isset($_SESSION['sukces']))
            {
              echo '<h5>'.$_SESSION['sukces'].'</h5>';
              unset($_SESSION['sukces']);
            }
          ?>
            <h2>Nowa sala</h2>
        </div>
        <div class="mb-1">
            <label for="tytul" class="col-md-2">
                Oznaczene sali:
            </label>
            <div class="col-md-9">
              <input type="text" class="form-control" name="oznaczeniesali" placeholder="Numer/Kod sali" style="color: black;" required>
              <?php
              if(isset($_SESSION['blad_oznaczeniasali']))
              {
                echo $_SESSION['blad_oznaczeniasali'];
                unset($_SESSION['blad_oznaczeniasali']);
              }
              ?>
            </div>
        </div>
        <div class="mb-1">
            <label for="tytul" class="col-md-2">
                Ilość rzędów:
            </label>
            <div class="col-md-9">
              <input type="number" class="form-control" name="iloscrzedow" placeholder="Rzędy" min="1" style="color: black;" required>
            </div>
        </div>
        <div class="mb-1">
            <label for="tytul" class="col-md-2">
                Ilość miejsc:
            </label>
            <div class="col-md-9">
              <input type="number" class="form-control" name="iloscmiejsc" placeholder="Miejsca" min="1" style="color: black;" required>
            </div>
        </div>
        <div>
            <div class="col-md-2">
            </div>
            <div class="col-md-10">
                <label>
                    <input type="checkbox" required> Tak, chcę dodać nową sale</label>
            </div>
        </div>
        <div>
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <button type="submit" class="btn btn-info">
                    Dodaj sale
                </button>
            </div>
        </div>
    </div>
    </form>
    </div>

</body>
</html>
