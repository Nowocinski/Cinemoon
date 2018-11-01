<?php
    if(!isset($_POST['miejsce']))
    {
        header('Location: index.php');
        exit(0);
    }

    if (session_status() == PHP_SESSION_NONE)
        session_start();

    require_once "connect.php";

    $title = "Potwierdzenie";
    include "side_part/gora.php";
    include "side_part/nav.php";

echo<<<END
<div class="container-fluid">
    <div class="dane-konta mt-3">
        <div class="row text-center">
END;
    $przechwycenie = $_POST['miejsce'];
    
    $i=0; $id_repertuaru = '';
    while($przechwycenie[$i] != ' ')
    {
        $id_repertuaru .= $przechwycenie[$i];
        $i++;
    }

    ++$i; $rzad='';
    while($przechwycenie[$i] != ' ')
    {
        $rzad .= $przechwycenie[$i];
        $i++;
    }

    ++$i; $miejsce='';
    while($i < strlen($przechwycenie))
    {
        $miejsce .= $przechwycenie[$i];
        $i++;
    }

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
            $rezultat = $polaczenie->query("SELECT filmy.tytul FROM repertuar INNER JOIN filmy ON repertuar.id_filmu=filmy.id_filmu WHERE id_repertuaru='$id_repertuaru'");
            
            if(!$rezultat)
                throw new Exception($polaczenie->error);
            else
            {
echo<<<END
    <div class="col-12">
        <h1>
END;
                $wiersz = $rezultat->fetch_assoc();
                echo $wiersz['tytul'];
echo<<<END
        </h1>
    </div>
END;
            }
            
            $rezultat2 = $polaczenie->query("SELECT sale.nr_sali, sale.ilosc_rzedow, sale.ilosc_miejsc FROM sale INNER JOIN repertuar ON sale.id_sali=repertuar.id_sali WHERE repertuar.id_repertuaru='$id_repertuaru'");
            
            if(!$rezultat2)
                throw new Exception($polaczenie->error);
            else
            {
                $wiersz = $rezultat2->fetch_assoc();
                $nr_sali = $wiersz['nr_sali'];
                $ilosc_rzedow = $wiersz['ilosc_rzedow'];
                $ilosc_miejsc = $wiersz['ilosc_miejsc'];
                $ile_miejsc = $wiersz['ilosc_rzedow']*$wiersz['ilosc_miejsc'];
                echo '<div class="col-4"><span style="font-weight: 500;"><b>Numer sali</b><br>'.$nr_sali.'</span></div>';
                echo '<div class="col-4"><span style="font-weight: 500;"><b>Ilość miejsc w sali</b><br>'.$ile_miejsc.'</span></div>';
            }
            
            $rezultat3 = $polaczenie->query("SELECT * FROM repertuar WHERE id_repertuaru='$id_repertuaru'");
            if(!$rezultat3)
                throw new Exception($polaczenie->error);
            else
            {
                $wiersz = $rezultat3->fetch_assoc();
                $czas_rozpoczecia = $wiersz['czas_rozpoczecia'];
                $cena_biletu = $wiersz['cena_biletu'];
                echo '<div class="col-4"><span style="font-weight: 500;"><b>Termin</b><br>'.$czas_rozpoczecia.'</span></div>';
            }
            
            /* Sprawdzanie typu konta */
            if(isset($_SESSION['zalogowany']))
            {
                $mnoznik = 1;
                $id_klienta = $_SESSION['id_klienta'];
                $rezultat4 = $polaczenie->query("SELECT typ FROM klienci WHERE id_klienta='$id_klienta'");
                if(!$rezultat4)
                    throw new Exception($polaczenie->error);


                else
                {
                    $wiersz = $rezultat4->fetch_assoc();

                    if($wiersz['typ'] == "studencki")
                        $mnoznik = 0.5;
                }
            }
//---------------------------------------------------------------------------------------------------------------------------
echo<<<END
<div class="container-fluid text mt-5">
        <div class="row">
            <div class="col-1"></div>
            <div class="table-responsive col-10">
              <table class="table">
                <tr><th>Sale</th><th>Miejsce</th><th>Rząd</th><th>Cena</th><tr>
                <tr><th>$nr_sali</th><th>$miejsce</th><th>$rzad</th><th>
END;
            if(isset($_SESSION['zalogowany']))
            {
                if($wiersz['typ'] == 'studencki')
                {
                    echo '<span style="color:red;text-decoration:line-through">'.$cena_biletu.' zł</span>';
                    echo '<p>('.$cena_biletu*$mnoznik.'zł, zniżka 50%)</p>';
                }
                
                if($wiersz['typ'] == 'normalny')
                {
                    echo $cena_biletu.' zł';
                    echo '<p>(Brak zniżki)</p>';
                }
            }
            
            else
            {
                echo $cena_biletu.' zł';
            }
echo<<<END
                </th><tr>
              </table>
            </div>
END;
if(isset($_SESSION['zalogowany']))
{
echo<<<END
            <div class="col-12"></div>
            <div class="col-12">
                <p><span style="color: gray;">Wszystko jest gotowe. Możesz potwierdzić rezerwacje.</sapn></p>
                <a href="zarezerwuj.php" class="btn btn-danger btn-md m-1">Potwierdzam</button>
                <a href="index.php" class="btn btn-warning btn-md m-1">Rezygnuję</a>
            </div>
END;
}
else
{
echo<<<END
            <div class="col-12"></div>
            <div class="col-12">
                <p><span style="color: gray;">Do dokonania rejestracji wymagane jest posiadanie konta</span></p>
                <a href="rejestracja.php" class="btn btn-danger btn-md m-1">Zarejestruj się</a>
            </div>
END;
}
echo<<<END
        </div>
</div>
END;
//---------------------------------------------------------------------------------------------------------------------------
            $polaczenie->close();
        }
    }
    catch(Exception $e)
    {
        echo '<span style="color: red">Błąd serwera. Spróbuj zarejestrować się później</span>';
        echo '<br>Informacja deweloperska: '.$e;
    }

echo<<<END
        </div>
    </div>
</div>
END;

    include "side_part/dol.php";
    //--------------------------------------------------------------------------

?>