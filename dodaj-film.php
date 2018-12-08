<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

    if(!isset($_SESSION['typ_konta']) || $_SESSION['typ_konta']!= 'administratorIT')
    {
      header('Location: index.php');
      exit();
    }

    if(isset($_POST['tytul']))
    {
    $poprawna_walidacja = true;
    $tytul = htmlentities($_POST['tytul'], ENT_QUOTES, "UTF-8");
    $opis = $_POST['opis'];
    $producent = htmlentities($_POST['producent'], ENT_QUOTES, "UTF-8");
    $rezyser = htmlentities($_POST['rezyser'], ENT_QUOTES, "UTF-8");
    $czastrwanie = $_POST['czastrwania'];

    $iloscchecboxow = 0; $gatunek = '';
    if(isset($_POST['komedia'])) {$iloscchecboxow++; $gatunek .= 'Komedia';}
    if(isset($_POST['dramat'])) {$iloscchecboxow++; if(strlen($gatunek) > 0) $gatunek .= ', dramat'; else $gatunek .='Dramat';}
    if(isset($_POST['melodramat'])) {$iloscchecboxow++; if(strlen($gatunek) > 0) $gatunek .= ', melodramat'; else $gatunek .='Melodramat';}
    if(isset($_POST['western'])) {$iloscchecboxow++; if(strlen($gatunek) > 0) $gatunek .= ', western'; else $gatunek .='Western';}
    if(isset($_POST['horror'])) {$iloscchecboxow++; if(strlen($gatunek) > 0) $gatunek .= ', horror'; else $gatunek .='Horror';}
    if(isset($_POST['musical'])) {$iloscchecboxow++; if(strlen($gatunek) > 0) $gatunek .= ', musical'; else $gatunek .='Musical';}
    if(isset($_POST['thrille'])) {$iloscchecboxow++; if(strlen($gatunek) > 0) $gatunek .= ', thrille'; else $gatunek .='Thrille';}
    if(isset($_POST['kryminal'])) {$iloscchecboxow++; if(strlen($gatunek) > 0) $gatunek .= ', kryminał'; else $gatunek .='Kryminał';}
    if(isset($_POST['gangsterski'])) {$iloscchecboxow++; if(strlen($gatunek) > 0) $gatunek .= ', gangsterski'; else $gatunek .='Gangsterski';}
    if(isset($_POST['sf'])) {$iloscchecboxow++; if(strlen($gatunek) > 0) $gatunek .= ', science fiction'; else $gatunek .='Science fiction';}
    if(isset($_POST['fantasy'])) {$iloscchecboxow++; if(strlen($gatunek) > 0) $gatunek .= ', fantasy'; else $gatunek .='Fantasy';}
    if(isset($_POST['historyczny'])) {$iloscchecboxow++; if(strlen($gatunek) > 0) $gatunek .= ', historyczny'; else $gatunek .='Historyczny';}
    if(isset($_POST['komediaromantyczna'])) {if(strlen($gatunek) > 0) $gatunek .= ', komedia romantyczna'; else $gatunek .='Komedia romantyczna';}
    if(isset($_POST['psychologiczny'])) {if(strlen($gatunek) > 0) $gatunek .= ', psychologiczny'; else $gatunek .='Psychologiczny';}
    if(isset($_POST['szpiegowski'])) {if(strlen($gatunek) > 0) $gatunek .= ', szpiegowski'; else $gatunek .='Szpiegowski';}
    if(isset($_POST['familijny'])) {if(strlen($gatunek) > 0) $gatunek .= ', familijny'; else $gatunek .='Familijny';}
    if(isset($_POST['wojenny'])) {if(strlen($gatunek) > 0) $gatunek .= ', wojenny'; else $gatunek .='Wojenny';}
    if(isset($_POST['sportowy'])) {if(strlen($gatunek) > 0) $gatunek .= ', sportowy'; else $gatunek .='Sportowy';}
    if(isset($_POST['kostiumowy'])) {if(strlen($gatunek) > 0) $gatunek .= ', kostiumowy'; else $gatunek .='Kostiumowy';}
    if(isset($_POST['animowany'])) {if(strlen($gatunek) > 0) $gatunek .= ', animowany'; else $gatunek .='Animowany';}

    if($iloscchecboxow == 0)
    {
      $poprawna_walidacja = false;
      $_SESSION['blad_chacbox'] = '<span style="color: rad;">Przynajmniej jeden gatunek powinien zostać wybrany</span>';
    }

    //Pobieranie plakatu
    $file = $_FILES['file'];
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'gif');

    //Wyłączenie worningów i włączenie wyświetlania wyjątków
    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
      require_once "connect.php";
      $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

      if($polaczenie->connect_errno != 0)
              throw new Exception(mysqli_connect_errno());
      else {
              $polaczenie->query("SET NAMES utf8");
              $rezultat = $polaczenie->query("SELECT grafika FROM filmy WHERE grafika='$fileName' AND grafika!=''");
              if(!$rezultat)
                  throw new Exception($polaczenie->error);
              else {
                if($rezultat->num_rows > 0)
                {
                  $poprawna_walidacja = false;
                  $_SESSION['blad_plakat'] = '<span style="color: red;">W bazie istnieje już plik o tej samej nazwie</span>';
                }
              }
        $rezultat->free_result();
        $polaczenie->close();
      }

    } catch (Exception $e) {
      echo '<span style="color: red;">Błąd serwera. Spróbuj zarejestrować się później</span>';
      //echo '<br>Informacja deweloperska: '.$e;
    }


    if(in_array($fileActualExt, $allowed))
    {
      if($fileError === 0)
      {
        if($fileSize < 40000000)
        {
          //$fileNameNew = uniqid('', true).".".$fileActualExt;
          $fileDestination = 'side_part/filmy/'.$fileName;
          move_uploaded_file($fileTmpName, $fileDestination);
        }
        else
        {
          $poprawna_walidacja = false;
          $_SESSION['blad_plakat'] = '<span style="color: red;">Plik jest za duży</span>';
        }
      }
      else
      {
        $poprawna_walidacja = false;
        $_SESSION['blad_plakat'] = '<span style="color: red;">Błąd wysłania pliku</span>';
      }
    }

    else
    {
      $poprawna_walidacja = false;
      $_SESSION['blad_plakat'] = '<span style="color: red;">Nieobsługiwany typ pliku</span>';
    }

    //---------------------------------------------------------------------------------------------
        if($poprawna_walidacja)
        {
          try {
            require_once "connect.php";
            $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

            if($polaczenie->connect_errno != 0)
                    throw new Exception(mysqli_connect_errno());
            else {
              // Kodowanie polskich znaków
              $polaczenie->query("SET NAMES utf8");
              if($polaczenie->query("INSERT INTO filmy VALUES ('','$tytul','$opis','$gatunek','$czastrwanie','$producent','$rezyser','$fileName')"))
              {
                unset($poprawna_walidacja);
                unset($_POST['tytul']);

                $_SESSION['sukces'] = true;
              }
              else {
                throw new Exception(mysqli_connect_errno());
              }
              $polaczenie->close();
            }
          } catch (Exception $e) {
            echo '<span style="color: red;">Błąd serwera. Spróbuj zarejestrować się później</span>';
            //echo '<br>Informacja deweloperska: '.$e;
          }

        }
    //---------------------------------------------------------------------------------------------

    }

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nowy film</title>

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
                  <li><a href="adminIT-repertuar.php"><i class="fa fa-film"></i> Repertuar</a></li>
                  <li><a href="dodaj-seans.php"><i class="fa fa-tasks"></i> Nowy seans</a></li>
                  <li class="selected"><a href="dodaj-film.php"><i class="fa fa-video-camera"></i> Dodaj film</a></li>
                  <li><a href="dodaj-sale.php"><i class="fa fa-university"></i> Dodaj sale</a></li>
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
         <form action="dodaj-film.php" method="post" enctype="multipart/form-data">
        <div class="row text-center">
<?php
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
            <h2>Nowy film</h2>
        </div>
        <div class="mb-1">
            <label for="tytul" class="col-md-2">
                Tytuł:
            </label>
            <div class="col-md-9">
              <input type="text" class="form-control" name="tytul" placeholder="Nazwa filmu" style="color: black;" required>
            </div>
        </div>
        <div>
            <label for="sala" class="col-md-2">
                Opis:
            </label>
            <div class="col-md-9">
              <textarea class="form-control" rows="5" name="opis" placeholder="Opis filmu" style="color: black;" required></textarea>
              <p class="help-block">
                  Uwaga: W celu dodania akapitów prosimy umieścić odpowiednie fragmęty tekstu między znaczniki &lt;p&gt; i &lt;/p&gt;
              </p>
            </div>
        </div>
        <div class="mb-1">
            <label for="tytul" class="col-md-2">
                Gatunek:
            </label>
            <div class="checkbox">
            <div class="col-md-9">
                    <div><label><input type="checkbox" name="komedia">Komedia</label></div>
                    <div><label><input type="checkbox" name="dramat">Dramat</label></div>
                    <div><label><input type="checkbox" name="melodramat">Melodramat</label></div>
                    <div><label><input type="checkbox" name="western">Western</label></div>
                    <div><label><input type="checkbox" name="horror">Horror</label></div>
                    <div><label><input type="checkbox" name="musical">Musical</label></div>
                    <div><label><input type="checkbox" name="thrille">Thriller</label></div>
                    <div><label><input type="checkbox" name="kryminal">Kryminał</label></div>
                    <div><label><input type="checkbox" name="gangsterski">Gangsterski</label></div>
                    <div><label><input type="checkbox" name="sf">Science fiction</label></div>
                    <div><label><input type="checkbox" name="fantasy">Fantasy</label></div>
                    <div><label><input type="checkbox" name="historyczny">Historyczny</label></div>
                    <div><label><input type="checkbox" name="komediaromantyczna">Komedia romantyczna</label></div>
                    <div><label><input type="checkbox" name="psychologiczny">Psychologiczny</label></div>
                    <div><label><input type="checkbox" name="szpiegowski">Szpiegowski</label></div>
                    <div><label><input type="checkbox" name="familijny">Familijny</label></div>
                    <div><label><input type="checkbox" name="wojenny">Wojenny</label></div>
                    <div><label><input type="checkbox" name="sportowy">Sportowy</label></div>
                    <div><label><input type="checkbox" name="kostiumowy">Kostiumowy</label></div>
                    <div><label><input type="checkbox" name="animowany">Animowany</label></div>
                    <?php
                      if(isset($_SESSION['blad_chacbox']))
                      {
                        echo $_SESSION['blad_chacbox'];
                        unset($_SESSION['blad_chacbox']);
                      }
                    ?>
              </div>
            </div>
          </div>
        <div>
            <label for="emailaddress" class="col-md-2">
                Czas trwania:
            </label>
            <div class="col-md-9">
                <input type="number" class="form-control" placeholder="Czas filmu w minutach" min="1" name="czastrwania" style="color: black;" required>
            </div>
        </div>
        <div class="mb-1">
            <label for="tytul" class="col-md-2">
                Produkcja:
            </label>
            <div class="col-md-9">
              <input type="text" class="form-control" name="producent" placeholder="Producenci, którzy stworzyli film" required>
            </div>
        </div>
        <div class="mb-1">
            <label for="tytul" class="col-md-2">
                Reżyser:
            </label>
            <div class="col-md-9">
              <input type="text" class="form-control" name="rezyser" placeholder="Reżyser filmu" required>
            </div>
        </div>
        <div>
            <label for="uploadimage" class="col-md-2">
                Plakat:
            </label>
            <div class="col-md-10">
                <input type="file" name="file" required>
                <p class="help-block">
                    Obsługiwane formaty: jpeg, jpg, gif, png
                    <?php
                      if(isset($_SESSION['blad_plakat']))
                      {
                        echo '<br>'.$_SESSION['blad_plakat'];
                        unset($_SESSION['blad_plakat']);
                      }
                    ?>
                </p>
            </div>
        </div>
        <div>
            <div class="col-md-2">
            </div>
            <div class="col-md-10">
                <label>
                    <input type="checkbox" required> Tak, chcę dodać nowy film</label>
            </div>
        </div>
        <div>
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <button type="submit" class="btn btn-info">
                    Dodaj film
                </button>
            </div>
        </div>
      </form>
    </div>
    </div>

</body>
</html>
