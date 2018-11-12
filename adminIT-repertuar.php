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
    <title>Konto administratora IT</title>

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
                <a class="navbar-brand" href="adminIT-info.php">Panel administratora IT</a>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul id="active" class="nav navbar-nav side-nav">
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
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Aktualny repertuar </h3>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-grid1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#wrapper -->

    <script type="text/javascript">
        jQuery(function ($) {
            var performance = [12, 43, 34, 22, 12, 33, 4, 17, 22, 34, 54, 67],
                visits = [123, 323, 443],
                traffic = [
<?php
require_once "connect.php";

//Wyłączenie worningów i włączenie wyświetlania wyjątków
mysqli_report(MYSQLI_REPORT_STRICT);
try
{
  $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

  if($polaczenie->connect_errno != 0)
      throw new Exception(mysqli_connect_errno());
  else
  {
    $polaczenie->query("SET NAMES utf8");
    $rezultat = $polaczenie->query("SELECT repertuar.id_repertuaru, filmy.tytul, sale.nr_sali, repertuar.czas_rozpoczecia, repertuar.cena_biletu FROM repertuar INNER JOIN filmy ON repertuar.id_filmu=filmy.id_filmu INNER JOIN sale ON repertuar.id_sali=sale.id_sali WHERE repertuar.czas_rozpoczecia > CAST(CONCAT(CURDATE(),' ',CURTIME()) as DATETIME)");

    if(!$rezultat)
        throw new Exception($polaczenie->error);
    else {
      while($wiersz = $rezultat->fetch_assoc())
      {
        $id_repertuaru = $wiersz['id_repertuaru'];
        $tytul = $wiersz['tytul'];
        $nr_sali = $wiersz['nr_sali'];
        $czas_rozpoczecia = $wiersz['czas_rozpoczecia'];
        $cena_biletu = $wiersz['cena_biletu'];
echo<<<END
        {Film: "$id_repertuaru",
          Amount: "$tytul",
          Percent: "$nr_sali",
          Target: "$czas_rozpoczecia",
          Cena: "$cena_biletu",
          Edycja: "<form action='edytuj-seans.php' method='post'><button class='btn-warning btn' name='edycja' value='$id_repertuaru'>Edytuj</button></form>",
          Usuniecie: "<form action='usun-seans.php' method='post'><button class='btn-danger btn' name='usun' value='$id_repertuaru'>Usuń</button></form>"},
END;
      }
      $rezultat->free_result();
      $polaczenie->close();
    }
  }
}
catch (Exception $e)
{
  echo '<span style="color: red">Błąd serwera. Spróbuj zarejestrować się później</span>';
  echo '<br>Informacja deweloperska: '.$e;
}

?>
];


            $("#shieldui-chart1").shieldChart({
                theme: "dark",

                primaryHeader: {
                    text: "Visitors"
                },
                exportOptions: {
                    image: false,
                    print: false
                },
                dataSeries: [{
                    seriesType: "area",
                    collectionAlias: "Q Data",
                    data: performance
                }]
            });

            $("#shieldui-chart2").shieldChart({
                theme: "dark",
                primaryHeader: {
                    text: "Traffic Per week"
                },
                exportOptions: {
                    image: false,
                    print: false
                },
                dataSeries: [{
                    seriesType: "pie",
                    collectionAlias: "traffic",
                    data: visits
                }]
            });

            $("#shieldui-grid1").shieldGrid({
                dataSource: {
                    data: traffic
                },
                sorting: {
                    multiple: true
                },
                rowHover: false,
                paging: false,
                columns: [
                { field: "Film", width: "170px", title: "ID", format: "{0}"},
                { field: "Amount", title: "Film" },
                { field: "Percent", title: "Sala" },
                { field: "Target", title: "Czas rozpoczęcia", format: "{0}" },
                { field: "Cena", title: "Cena biletu", format: "{0} zł" },
                { field: "Edycja", title: "Edycja" },
                { field: "Usuniecie", title: "Usunięcie" }
                ]
            });
        });
    </script>
</body>
</html>
