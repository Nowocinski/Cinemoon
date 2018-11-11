<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

    if(!isset($_SESSION['typ_konta']) || $_SESSION['typ_konta']!= 'administratorIT')
    {
      header('Location: index.php');
      exit();
    }

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

    echo $gatunek;

    if($iloscchecboxow == 0)
      $_SESSION['blad_chacbox'] = '<span style="color: rad;">Przynajmniej jeden gatunek powinien zostać wybrany</span>';

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
                <!--ul class="nav navbar-nav side-nav">
                  <li><a href="adminIT-info.php"><i class="fa fa-area-chart"></i> Strona startowa</a></li>
                  <li><a href="bootstrap-grid.html"><i class="fa fa-film"></i> Repertuar</a></li>
                  <li><a href="dodaj-seans.php"><i class="fa fa-tasks"></i> Nowy seans</a></li>
                  <li class="selected"><a href="dodaj-film.php"><i class="fa fa-video-camera"></i> Dodaj film</a></li>
                  <li><a href="dodaj-sale.php"><i class="fa fa-university"></i> Dodaj sale</a></li>
                </ul-->
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
         <form action="dodaj-film.php" method="post">
        <div class="row text-center">
            <h2>Nowy film</h2>
        </div>
        <div class="mb-1">
            <label for="tytul" class="col-md-2">
                Tytuł:
            </label>
            <div class="col-md-9">
              <input type="text" class="form-control" name="tytul" placeholder="Nazwa filmu" required>
            </div>
        </div>
        <div>
            <label for="sala" class="col-md-2">
                Opis:
            </label>
            <div class="col-md-9">
              <textarea class="form-control" rows="5" name="opis" placeholder="Opis filmu" required></textarea>
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
                <input type="number" class="form-control" placeholder="Czas filmu w minutach" min="1" required>
                <p class="help-block">
                    Uwaga: Data seansu musi być poźniejsza od daty dzisiejszej
                </p>
            </div>
        </div>
        <div class="mb-1">
            <label for="tytul" class="col-md-2">
                Produkcja:
            </label>
            <div class="col-md-9">
              <input type="text" class="form-control" id="firstname" placeholder="Producenci, którzy stworzyli film" required>
            </div>
        </div>
        <div class="mb-1">
            <label for="tytul" class="col-md-2">
                Reżyser:
            </label>
            <div class="col-md-9">
              <input type="text" class="form-control" id="firstname" placeholder="Reżyser filmu" required>
            </div>
        </div>
        <div>
            <label for="uploadimage" class="col-md-2">
                Plakat:
            </label>
            <div class="col-md-10">
                <input type="file" name="uploadimage" id="uploadimage" required>
                <p class="help-block">
                    Obsługiwane formaty: jpeg, jpg, gif, png
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
