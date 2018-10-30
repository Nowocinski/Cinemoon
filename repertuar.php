<?php
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    $title = "Repertuar";

    include "side_part/gora.php";
    include "side_part/nav.php";
    //echo '<div class="konto">';
    echo '<div class="container">';
    echo '<div class="row">';
    //--------------------------------------------------------------------------
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
            $rezultat = $polaczenie->query("SELECT * FROM filmy INNER JOIN repertuar ON filmy.id_filmu=repertuar.id_filmu GROUP BY filmy.id_filmu ORDER BY filmy.id_filmu DESC");
            if(!$rezultat)
                throw new Exception($polaczenie->error);
            
            else
            {
               while($wiersz = $rezultat->fetch_assoc())
               {
                   $tytul = $wiersz['tytul'];
                   echo '<div class="container">'; /* Contener na zdjęcie i opis wilmu */
                   echo '<div class="row">'; /* Row */
                   echo '<div class="col-2">'; /* col-2 otwarcie */
                   echo '<img src="side_part/filmy/'.$wiersz['grafika'].'" height="150" width="100">';
                   echo '</div>'; /* col-2 zamknięcie */
                   echo '<div class="col-10 dane-konta">'; /* col-10 otwarcie */
                   echo '<ul><li><b>Tytuł:</b> '.$wiersz['tytul'].'</li>';
                   echo '<li><b>Czas:</b> '.$wiersz['min_trwania'].' min</li>';
                   echo '<li><b>Reżyser:</b> '.$wiersz['rezyser'].'</li>';
                   echo '<li><b>Produkcja:</b> '.$wiersz['producent'].'</li>';
                   echo '<li><b>Gatunek:</b> '.$wiersz['gatunek'].'</li></ul>';
                   echo '</div>'; /* Zamknięcie col-10 otwarcie */
                   echo '</div>'; /* Zamknięcie - Row na zdjęcie i opis wilmu */
                   echo '</div>'; /* Zamknięcie - Contener na zdjęcie i opis wilmu */
                   
                   $rezultat2 = $polaczenie->query("SELECT * FROM repertuar INNER JOIN sale ON repertuar.id_sali=sale.id_sali INNER JOIN filmy ON repertuar.id_filmu=filmy.id_filmu WHERE filmy.tytul='$tytul' AND repertuar.czas_rozpoczecia > CAST(CONCAT(CURDATE(),' ',CURTIME()) as DATETIME) ORDER BY repertuar.czas_rozpoczecia ASC");
                   
                   if(!$rezultat2)
                        throw new Exception($polaczenie->error);
                   
                   else
                   {
                       while($wiersz2 = $rezultat2->fetch_assoc())
                       {
                           echo '<div class="container">'; /* Otwarcie contenera */
                           echo '<div class="row">'; /* Otwarcie diva na liste repertuarów */
                           echo '<div class="col-12 dane-konta">'; /* Otwarcie diva na liste repertuarów */
                           
                           echo '<div class="container">';
                           echo '<div class="row">';
                           
                           echo '<div class="col-3"><b>Nr sali: </b>'.$wiersz2['nr_sali'].'</div>';
                           echo '<div class="col-3"><b>Termin: </b>'.$wiersz2['czas_rozpoczecia'].'</div>';
                           echo '<div class="col-3"><b>Cena biletu: </b>'.$wiersz2['cena_biletu'].' zł</div>';
                           
                           // !!!!!!!!!! Na później: Zmień przycisk cena na napis "Wszystkie miejsca zajęte"
                           echo '<div class="col-3"><button type="button" class="btn-warning btn-md">Zapisz się</button></div>';
                           
                           echo '</div>';
                           echo '</div>';
                           
                           echo '</div>'; /* zamkniecie col-12 */
                           echo '</div>'; /* zamkniecie row */
                           echo '</div>'; /* zamkniecie Containera */
                       }
                   }
               }
            }
            
            $polaczenie->close();
        }
    }

    catch(Exception $e)
    {
        echo '<span style="color: red">Błąd serwera. Spróbuj zarejestrować się później</span>';
        echo '<br>Informacja deweloperska: '.$e;
    }
    echo '</div>'; /* Zamknięcie diva row */
    echo '</div>'; /* Zamknięcie diva container */
    //echo '</div>'; /* Zamknięcie div konto */
    include "side_part/dol.php";

?>