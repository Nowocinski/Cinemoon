<?php
	include "side_part/przekierowanie-pracownikow.php";

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
                $gatunek = $wiersz['gatunek'];
                $godz = ($wiersz['min_trwania'] - $wiersz['min_trwania']%60)/60;
                $min = ($wiersz['min_trwania'] - $godz*60);
                $opis = $wiersz['opis'];
                $rezyser = $wiersz['rezyser'];
                $producent = $wiersz['producent'];
            }
        }
        $polaczenie->close();
    }

    catch(Exception $e)
    {
        echo '<span style="color: red">Błąd serwera. Spróbuj zarejestrować się później</span>';
        //echo '<br>Informacja deweloperska: '.$e;
    }

    require_once "side_part/gora.php";
    require_once "side_part/nav.php";
?>
<artical>
    <section>
        <div class="container">
            <div class="dane-konta3">
                <div class="row">
                <?php
echo<<<END
                <div class="col-sm-3 col-12">
                    <img class="obraz" src="side_part/filmy/$grafika">
                </div>
                <div class="col-sm-9 col-12">
                    <span style="font-weight: 500; font-size: 30px;">$title</span><br>
                    $gatunek | $rezyser | $producent<br>
                    <span style="color: gray;">
END;
                    if($godz > 0) echo $godz.' godz. ';
                    if($min > 0) echo $min.' min.';
echo<<<END
                    </span><br>
                    <span style="margin: 20px;">$opis</span><br>
                </div>
END;
                ?>
                </div>
            </div>
        </div>
    </section>
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
                        $polaczenie->query("SET NAMES utf8");
                        $rezultat = $polaczenie->query("SELECT * FROM repertuar INNER JOIN sale ON repertuar.id_sali=sale.id_sali INNER JOIN filmy ON repertuar.id_filmu=filmy.id_filmu WHERE filmy.tytul='$title' AND repertuar.czas_rozpoczecia > CAST(CONCAT(CURDATE(),' ',CURTIME()) as DATETIME) ORDER BY repertuar.czas_rozpoczecia ASC");
                        if(!$rezultat)
                            throw new Exception($polaczenie->error);
                        else
                        {
                            $ilosc_filmow = $rezultat->num_rows;
                            //echo 'Ilość wystąpień filmu w repertuarze: '.$ilosc_filmow;

                            if($ilosc_filmow > 0)
                            {
echo<<<END
     <section>
        <div class="container">
            <div class="dane-konta3 mb-3">
            <span style="font-weight: 500; font-size: 30px;">REZERWACJE</span>
            <div class="table-responsive text-center">
                <table class="table">
                    <tr><th>Numer sali</th><th>Termin</th><th>Cena biletu</th><th>Zapisy</th><tr>
END;

                                while($wiersz = $rezultat->fetch_assoc())
                                {
                                    $nr_sali = $wiersz['nr_sali'];
                                    $czas_rozpoczecia = $wiersz['czas_rozpoczecia'];
                                    $cena_biletu = $wiersz['cena_biletu'];
                                    $ilosc_miejsc_w_sali = $wiersz['ilosc_rzedow'] * $wiersz['ilosc_miejsc'];
                                    $id_repertuaru = $wiersz['id_repertuaru'];
//------------------------------------------------------------------------------------------------------------------------
        $rezultatX = $polaczenie->query("SELECT * FROM rezerwacje WHERE id_repertuaru='$id_repertuaru'");
        $ilosc_osob_ktore_poszly_na_repertuar = $rezultatX->num_rows;

        if(!$rezultatX)
            throw new Exception($polaczenie->error);
//------------------------------------------------------------------------------------------------------------------------
echo<<<END
                    <tr><th>$nr_sali</th><th>$czas_rozpoczecia</th><th>$cena_biletu zł</th>
                    <th>
END;
                    //if($ilosc_osob_ktore_poszly_na_repertuar < $ilosc_miejsc_w_sali)
                    //{
                        echo '<form action="wybor-miejsca.php" method="post">';
                        echo '<button formtarget="_blank" name="id_repertuaru" value="'.$id_repertuaru.'" type="submit" class="btn btn-warning btn-md">Zapisz się</button>';
                        echo '</form>';
                        //echo 'ilosc_osob_ktore_poszly_na_repertuar: '.$ilosc_osob_ktore_poszly_na_repertuar.'<br>ilosc_miejsc_w_sali: '.$ilosc_miejsc_w_sali;
                        //echo '<br>Id_repertuaru: '.$id_repertuaru;
                    //}
                    /*else
                    {
                        echo '<button type="button" class="btn btn-danger btn-md">Bilety wyprzedane</button>';
                        echo 'ilosc_osob_ktore_poszly_na_repertuar: '.$ilosc_osob_ktore_poszly_na_repertuar.'<br>ilosc_miejsc_w_sali: '.$ilosc_miejsc_w_sali;
                        echo '<br>Id_repertuaru: '.$id_repertuaru;
                    }*/
echo<<<END
                    </th><tr>
END;
                                }
echo<<<END
                </table>
              </div>
            </div>
        </div>
    </section>
END;
                            }
                        }
                    }

                    $polaczenie->close();
                }

                catch(Exception $e)
                {
                    echo '<span style="color: red">Błąd serwera. Spróbuj zarejestrować się później</span>';
                    echo '<br>Informacja deweloperska: '.$e;
                }
            ?>
</artical>
<?php
    require_once "side_part/dol.php";
?>
