<?php
	include "side_part/przekierowanie-pracownikow.php";

    if (session_status() == PHP_SESSION_NONE)
        session_start();

    $title = "Repertuar";

    include "side_part/gora.php";
    include "side_part/nav.php";
    echo '<div class="container mt-3 text-center">';
    echo '<div class="row">';
	
	$dzien = date('Y-m-d');
echo<<<END
<div class="col-12 text-right">
	<form action="repertuar.php" post="get">
		<div class="form-group">
			<label style="color: white;">Znajdź seans na konkretny dzień: </label>
			<input type="date" value="{$dzien}" name="dzien" min="{$dzien}">
			<button style="color: black;">Szukaj</button>
		</div>
	</form>
</div>
END;
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
			if(!isset($_GET['dzien']))
			{
            $rezultat = $polaczenie->query("SELECT * FROM filmy INNER JOIN repertuar ON filmy.id_filmu=repertuar.id_filmu WHERE repertuar.czas_rozpoczecia >= CAST(CONCAT(CURDATE(),' ',CURTIME()) as DATETIME) GROUP BY filmy.id_filmu ORDER BY repertuar.czas_rozpoczecia ASC");
			}
			else
			{
				$rezultat = $polaczenie->query("SELECT * FROM filmy INNER JOIN repertuar ON filmy.id_filmu=repertuar.id_filmu WHERE CONVERT(repertuar.czas_rozpoczecia, DATE)=CONVERT('".$_GET['dzien']."', DATE) GROUP BY filmy.id_filmu ORDER BY repertuar.czas_rozpoczecia ASC");
			}
            if(!$rezultat)
                throw new Exception($polaczenie->error);
            else
            {
			   if(mysqli_num_rows($rezultat) == 0)
			   {
echo<<<END
<div class="container">
	<div class="row">
		<div class="col-12 dane-konta">
		Nie znaleziono żadnego seansu o podanym czasie
		</div>
	</div>
</div>
END;
			   }
			   else
			   {
               while($wiersz = $rezultat->fetch_assoc())
               {
                   $tytul = $wiersz['tytul'];
                   echo '<div class="container">'; /* Contener na zdjęcie i opis wilmu */
                   echo '<div class="row">'; /* Row */
                   echo '<div class="col-12 col-md-2 mb-3">'; /* col-2 otwarcie */
                   echo '<img src="side_part/filmy/'.$wiersz['grafika'].'" class="img-fluid mt-3">';
                   echo '</div>'; /* col-2 zamknięcie */
                   echo '<div class="col-12 col-md-10 dane-konta">'; /* col-10 otwarcie */
                   echo '<ul style="text-align: center;"><li style="font-weight: 500; font-size: 30px;">'.$wiersz['tytul'].'</li>';
                   echo '<li><b>Czas trwania</b><br>'.$wiersz['min_trwania'].' min</li>';
                   echo '<li><b>Reżyser</b><br>'.$wiersz['rezyser'].'</li>';
                   echo '<li><b>Produkcja</b><br>'.$wiersz['producent'].'</li>';
                   echo '<li><b>Gatunek</b><br>'.$wiersz['gatunek'].'</li></ul>';
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

                           echo '<div class="col-12 col-sm-6 col-md-3 text-center mt-2"><b>Nr sali: </b>'.$wiersz2['nr_sali'].'</div>';
                           echo '<div class="col-12 col-sm-6 col-md-3 text-center mt-2"><b>Termin: </b>'.$wiersz2['czas_rozpoczecia'].'</div>';
                           echo '<div class="col-12 col-sm-6 col-md-3 text-center mt-2"><b>Cena biletu: </b>'.$wiersz2['cena_biletu'].' zł</div>';

                           $ilosc_miejsc_w_sali = $wiersz2['ilosc_rzedow'] * $wiersz2['ilosc_miejsc'];
                           $id_repertuaru = $wiersz2['id_repertuaru'];
        $rezultat3 = $polaczenie->query("SELECT * FROM rezerwacje WHERE id_repertuaru='$id_repertuaru'");
        $ilosc_osob_ktore_poszly_na_repertuar = $rezultat2->num_rows;

        if(!$rezultat3)
            throw new Exception($polaczenie->error);
echo<<<END
        <div class="col-12 col-sm-6 col-md-3 text-center">
        <form action="wybor-miejsca.php" method="get">
                <button type="submit" class="btn btn-warning btn-md" name="id_repertuaru" value="$id_repertuaru">
                    Zapisz się
                </button>
            </form>
        </div>
END;
                           echo '</div>';
                           echo '</div>';

                           echo '</div>'; /* zamkniecie col-12 */
                           echo '</div>'; /* zamkniecie row */
                           echo '</div>'; /* zamkniecie Containera */
                       }
                   }
               }}
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
    include "side_part/dol.php";

?>
