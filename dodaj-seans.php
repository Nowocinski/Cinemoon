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
                  <li><a href="bootstrap-grid.html"><i class="fa fa-film"></i> Repertuar</a></li>
                  <li class="selected"><a href="dodaj-seans.php"><i class="fa fa-tasks"></i> Nowy seans</a></li>
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

       <div>
        <div class="row text-center">
            <h2>Nowy seans</h2>
        </div>
        <div class="mb-1">
            <label for="tytul" class="col-md-2">
                Tytuł filmu:
            </label>
            <div class="col-md-9">
                  <select name="film" class="form-control" id="film" class="form-control">
                    <option>Tytuł filmu 1</option>
                    <option>Tytuł filmu 2</option>
                    <option>Tytuł filmu 3</option>
                    <option>Tytuł filmu 4</option>
                  </select>
            </div>
        </div>
        <div>
            <label for="sala" class="col-md-2">
                Numer sali:
            </label>
            <div class="col-md-9">
                <select name="sala" class="form-control" class="form-control">
                  <option>Numer sali 1</option>
                  <option>Numer sali 2</option>
                  <option>Numer sali 3</option>
                  <option>Numer sali 4</option>
                </select>
            </div>
        </div>
        <div>
            <label for="emailaddress" class="col-md-2">
                Data:
            </label>
            <div class="col-md-9">
                <input type="date" class="form-control" id="data">
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
                <input type="time" class="form-control" id="czas">
            </div>
        </div>
        <div>
            <label for="country" class="col-md-2">
                Cena biletu:
            </label>
            <div class="col-md-9">
                <input type="number" class="form-control" id="emailaddress" placeholder="Cena biletu w zł" step="0.01">
            </div>
        </div>
        <div>
            <div class="col-md-2">
            </div>
            <div class="col-md-10">
                <label>
                    <input type="checkbox"> Tak, chcę dodać nowy seans</label>
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
    </div>

</body>
</html>
