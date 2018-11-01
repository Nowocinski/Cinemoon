<?php
    if(!isset($_POST['id_repertuaru']))
    {
        header('Location: index.php');
        exit();
    }
    
    $id_repertuaru = $_POST['id_repertuaru'];

    $title = "Wybór miejsca";
    include "side_part/gora.php";
    //include "side_part/nav.php";

    session_start();
    require_once "connect.php";

echo<<<END
<div class="container-fluid">
    <div class="dane-konta mt-3">
        <div class="row text-center">
END;


    //Wyłączenie worningów i włączenie wyświetlania wyjątków
    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
            
        if($polaczenie->connect_errno != 0)
            throw new Exception(mysqli_connect_errno());
        else
        {
            /* Wyjęcie tytułu do nagłowka */
//---------------------------------------------------------------------------------------------------------------------------------
            $polaczenie->query("SET NAMES utf8");
            $rezultat = $polaczenie->query("SELECT filmy.tytul FROM repertuar INNER JOIN filmy ON repertuar.id_filmu=filmy.id_filmu WHERE id_repertuaru='$id_repertuaru'");
            
            if(!$rezultat)
                throw new Exception($polaczenie->error);
            else
            {
                $wiersz = $rezultat->fetch_assoc();
                $tytul = $wiersz['tytul'];
echo<<<END
            <div class="col-12"><span style="text-align: center;"><h1>$tytul</h1></span></div>
END;
            }
            /* Wyjęcie numeru sali */
//---------------------------------------------------------------------------------------------------------------------------------
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
            /* Wyjęcie daty */
//---------------------------------------------------------------------------------------------------------------------------------
            $rezultat3 = $polaczenie->query("SELECT * FROM repertuar WHERE id_repertuaru='$id_repertuaru'");
            if(!$rezultat3)
                throw new Exception($polaczenie->error);
            else
            {
                $wiersz = $rezultat3->fetch_assoc();
                $czas_rozpoczecia = $wiersz['czas_rozpoczecia'];
                echo '<div class="col-4"><span style="font-weight: 500;"><b>Termin</b><br>'.$czas_rozpoczecia.'</span></div>';
            }
            
//---------------------------------------------------------------------------------------------------------------------------------
            
echo<<<END
<div class="col-12 "><div style="color: white; background-color: blue; width: 30%; min-width: 300px; margin-left: auto; margin-right: auto;">EKRAN</div></div>
<div class="col-12 mt-3">
<form action="potwierdzenie.php">
END;
      for($i=1; $i<=$ilosc_rzedow; $i++)
      {
          echo $i;
          
          for($j=1; $j<=$ilosc_miejsc; $j++)
          {
//---------------------------------------------------------------------------------------------------------------------------------
$rezultat4 = $polaczenie->query("SELECT miejsce FROM rezerwacje WHERE id_repertuaru='$id_repertuaru' AND miejsce='$j' AND rzad='$i'");
              $czy_zajete = $rezultat4->num_rows;
if(!$rezultat4)
    throw new Exception($polaczenie->error);
//---------------------------------------------------------------------------------------------------------------------------------
              if($czy_zajete == 0)
                  echo '<button type="submit" class="btn btn-success btn-md m-1 name="miejsce" value="'.$i.' '.$j.'">'.$j.'</button>';
              else
                  echo '<button type="button" class="btn btn-danger btn-md m-1">'.$j.'</button>';
          }
          echo '<br>';
      }

echo<<<END
</form>
    <div class="col-12">
        <button type="button" class="btn btn-success btn-md m-1 ">9</button> - miejsce wolne
    </div>
    <div class="col-12">
        <button type="button" class="btn btn-danger btn-md m-1 ">9</button> - miejsce wolne
    </div>
END;

//---------------------------------------------------------------------------------------------------------------------------------
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