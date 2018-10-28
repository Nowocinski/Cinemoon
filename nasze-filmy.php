<?php
    if(!isset($_POST['film']))
    {
        header('Location: index.php');
        exit();
    }

    if (session_status() == PHP_SESSION_NONE)
        session_start();

    require_once "connect.php";
    $grafika = $_POST['film'];

    //Wyłączenie worningów i włączenie wyświetlania wyjątków
    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if($polaczenie->connect_errno != 0)
                throw new Exception(mysqli_connect_errno());
        
        else
        {
            // Kodowanie polskich znaków
            $polaczenie->query("SET NAMES utf8");
            $rezultat = $polaczenie->query("SELECT * FROM filmy WHERE grafika='$grafika'");
            if(!$rezultat)
                throw new Exception($polaczenie->error);
            
            $ilosc_filmow = $rezultat->num_rows;
            if($ilosc_filmow < 0)
                throw new Exception("Brak filmu w bazie");
            else
            {
                $wiersz = mysqli_fetch_assoc($rezultat);
                $title = $wiersz['tytul'];
            }
        }
        $polaczenie->close();
    }

    catch(Exception $e)
    {
        echo '<span style="color: red">Błąd serwera. Spróbuj zarejestrować się później</span>';
        echo '<br>Informacja deweloperska: '.$e;
    }

    require_once "side_part/gora.php";
    //require_once "side_part/nav.php";
?>
<artical>
    <div class="container">
        <div class="row">
            
        </div>
    </div>
</artical>
<?php
    require_once "side_part/dol.php";
?>