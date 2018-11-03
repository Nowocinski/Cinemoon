<?php
    require_once "connect.php";
?>
<article class="filmy2 text-center">
    <p>NAJNOWSZE FILMY</p>
    <div class="container-fluid">
        <div class="row">
<?php
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
                    $rezultat = $polaczenie->query("SELECT grafika, tytul FROM filmy ORDER BY id_filmu DESC");
                    if(!$rezultat)
                        throw new Exception($polaczenie->error);
                    
                    $ilosc_filmow = $rezultat->num_rows;
                    if($ilosc_filmow < 0)
                        throw new Exception("Brak filmów w bazie");
                    else
                    {
                        //echo '<form action="nasze-filmy.php" method="post">';
                        $i = 0;
                        /* Tworzenie tablicy asocjacyjnej (skojarzeniowej) */
                        while($wiersz = $rezultat->fetch_assoc())
                        {
                            if($i>=8) break;
                            //Plakaty mają na około tą dziwaczną obwódkę od przycisku, fujj :-/
                            echo '<div class="col-sm-3"><form action="nasze-filmy.php" method="post"><figure><button type="submit" class="btn btn-link" name="film" value='.$wiersz['grafika'].'>';
                            echo '';
                            echo '<img src="side_part/filmy/'.$wiersz['grafika'].'" alt="'.$wiersz['grafika'].' height="400" width="200">';
                            echo '</button>';

                            echo '<figcaption>'.$wiersz['tytul'].'</figcaption></figure></form></div>';
                            $i++;
                        }
                        //echo '</form>';
                    }
                    $polaczenie->close();
                }
            }
            
            catch(Exception $e)
            {
                echo '<span style="color: red;">Błąd serwera. Spróbuj zarejestrować się później</span>';
                //echo '<br>Informacja deweloperska: '.$e;
            }
?>
        </div>
    </div>
</article>