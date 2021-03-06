<?php
    session_start();
    if(!isset($_SESSION['zalogowany']))
    {
        header('Location: logowanie.php');
        exit();
    }

    if(isset($_SESSION['zwd_imie'])) unset($_SESSION['zwd_imie']);
    if(isset($_SESSION['zwd_nazwisko'])) unset($_SESSION['zwd_nazwisko']);
    if(isset($_SESSION['zwd_email'])) unset($_SESSION['zwd_email']);
    if(isset($_SESSION['zwd_haslo1'])) unset($_SESSION['zwd_haslo1']);
    if(isset($_SESSION['zwd_haslo2'])) unset($_SESSION['zwd_haslo2']);
    if(isset($_SESSION['zwd_nr_telefonu'])) unset($_SESSION['zwd_nr_telefonu']);

    if(isset($_SESSION['blad_imie'])) unset($_SESSION['blad_imie']);
    if(isset($_SESSION['blad_nazwisko'])) unset($_SESSION['blad_nazwisko']);
    if(isset($_SESSION['blad_email'])) unset($_SESSION['blad_email']);
    if(isset($_SESSION['blad_haslo1'])) unset($_SESSION['blad_haslo1']);
    if(isset($_SESSION['blad_haslo2'])) unset($_SESSION['blad_haslo2']);
    if(isset($_SESSION['blad_nr_telefonu'])) unset($_SESSION['blad_nr_telefonu']);

    $title = 'Konto';

    include "side_part/gora.php";
    include "side_part/nav.php";

    if(isset($_SESSION['id']))
        $id_klienta = $_SESSION['id'];
    else
    {
      //Wyłączenie worningów i włączenie wyświetlania wyjątków
      mysqli_report(MYSQLI_REPORT_STRICT);

      try
      {
          $email = $_SESSION['email'];
          require_once "connect.php";
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
              else
              {
                  // Kodowanie polskich znaków
                  $polaczenie->query("SET NAMES utf8");

                  $rezultat = $polaczenie->query("SELECT id FROM konta WHERE email='$email' AND email!=''");

                  if(!$rezultat)
                      throw new Exception($polaczenie->error);
                  else
                  {
                      $wiersz = $rezultat->fetch_assoc();
                      $_SESSION['id'] = $wiersz['id'];
                      $id_klienta = $wiersz['id'];
                  }


                  $rezultat->free_result();
                  $polaczenie->close();
              }
          }
      }

      catch(Exception $e)
      {
          echo '<span style="color: red;">Błąd serwera. Spróbuj zarejestrować się później</span>';
          //echo '<br>Informacja deweloperska: '.$e;
      }
    }
?>

<script>
	var str = '';
	var ile_rezerwacji = 0;

	function odwolaj()
	{
		if(ile_rezerwacji==0)
		{
			document.getElementById("doPodmiany").innerHTML = '<p>Nie wybrano żadnej rezerwacji do odwołania</p>';
		
			document.getElementById("przyciskiDoPodmiany").innerHTML = '<button type="button" class="btn btn-default" data-dismiss="modal">Wyjdź</button>';
		}
		
		else
		{
			document.getElementById("doPodmiany").innerHTML = '<form id="formularz" method="post" action="odwolanie-rezerwacji.php"><p>Czy chcesz odwołać wybrane rezerwacje?</p><label><input type="checkbox" required> Tak, chcę odwołać wybrane rezerwacje</label></form>';
		
			document.getElementById("przyciskiDoPodmiany").innerHTML = '<button type="submit" class="btn btn-warning" form="formularz" name="przycisk" value="'+str+'">Odwołaj</button><button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>';
		}
	}
	
	function podmien(element, num)
	{
		if(element.checked)
		{
			ile_rezerwacji++;
			
			if(str=='')
				str += "id_rezerwacji='" + num.toString() + "'";
			else
				str += " OR id_rezerwacji='" + num.toString() + "'";
		}
		
		else
		{
			ile_rezerwacji--;
			str = str.replace("'" + num.toString() + "'", "''");
		}
	}
</script>

<!-- Odwołanie rezerwacji -->
<div id="okno" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
		<h4 class="modal-title text-warning">Odwołanie rezerwacji</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div id="doPodmiany" class="modal-body">
      </div>
      <div class="modal-footer">
        <div id="przyciskiDoPodmiany"></div>
      </div>
    </div>
  </div>
</div>

    <div class="container">
      <div class="row">
<?php
        if(isset($_SESSION['pierwszewejscie']))
        {
            echo '<article class="dane-konta2 text-center mt-3 col-12" style="color: yellow;"><h5><p>Dziękujemy, '.$_SESSION['imie'].', za zarejestrowanie się na naszej witrynie.</p><p>Wszystkie informację o rezerwacjach jak i ustawieniach konta znajdują się w tym miejsce</p></h5></article>';
            unset($_SESSION['pierwszewejscie']);
        }
?>


<div class="dane-konta mt-3 col-12 col-lg-2 text-center">
        <article class="mb-3">
            <header>
                <h5 class="text-center">DANE KONTA</h5>
            </header>
			<section style="word-wrap: break-word">
                <b>Imię</b><br><?php echo $_SESSION['imie']; ?><br>
                <b>Nazwisko</b><br><?php echo $_SESSION['nazwisko']; ?><br>
                <b>E-mail</b><br><?php echo $_SESSION['email']; ?><br>

                <b>Numer telefonu</b><br><?php if($_SESSION['nr_telefonu'] != '') echo $_SESSION['nr_telefonu']; else echo '<span style="color: gray">(nie podano)</span><br>';?>
				<b>Miejscowość</b><br><?php if($_SESSION['miejscowosc'] != '') echo $_SESSION['miejscowosc'].'<br>'; else echo '<span style="color: gray">(nie podano)</span><br>';?>
				<b>Adres</b><br><?php if($_SESSION['adres'] != '') echo $_SESSION['adres']; else echo '<span style="color: gray">(nie podano)</span><br>';?>
			</section>
        </article>
</div>
		<div class="col-12 col-lg-10">
        <article class="dane-konta col-12 mt-3">
            <header>
                <b>TWOJE REZERWACJE</b>
            </header>
            <section>
<?php
            //Wyłączenie worningów i włączenie wyświetlania wyjątków
            mysqli_report(MYSQLI_REPORT_STRICT);
            require_once "connect.php";

            try
            {
                $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

                if($polaczenie->connect_errno != 0)
                    throw new Exception(mysqli_connect_errno());

                else
                {
                    // Kodowanie polskich znaków
                    $polaczenie->query("SET NAMES utf8");
                    $rezultat = $polaczenie->query("SELECT * FROM rezerwacje INNER JOIN konta ON rezerwacje.id_klienta=konta.id INNER JOIN repertuar ON rezerwacje.id_repertuaru=repertuar.id_repertuaru INNER JOIN filmy ON repertuar.id_filmu=filmy.id_filmu INNER JOIN sale ON repertuar.id_sali=sale.id_sali WHERE rezerwacje.id_klienta='$id_klienta' AND repertuar.czas_rozpoczecia > CAST(CONCAT(CURDATE(),' ',CURTIME()) as DATETIME) ORDER BY repertuar.czas_rozpoczecia ASC");

                    if(!$rezultat)
                        throw new Exception($polaczenie->error);

                    else
                    {
                        $ilosc_filmow = $rezultat->num_rows;

                        if($ilosc_filmow == 0)
                        {
                            echo '<span style="color: gray">Brak rezerwacji na tym koncie</span>';
                        }

                        else
                        {
                            echo '<span style="font-weight: 500;">Ile rezerwacji: '.$ilosc_filmow.'</span>';
                            echo '<div class="table-responsive"><table class="table" style="text-align: center;"><tr><th style="width: 300px;">Film</th><th style="width: 200px;">Termin</th><th>Sala</th><th>Rząd</th><th>Miejsce</th><th>Cena</th><th><button class="btn btn-warning" onclick="odwolaj()" data-toggle="modal" data-target="#okno">Odwołaj</button></th><tr>';
                            while($wiersz = $rezultat->fetch_assoc())
                            {
                                echo '<tr><th><span style="font-weight: 400;">'.$wiersz['tytul'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['czas_rozpoczecia'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['nr_sali'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['rzad'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['miejsce'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['koszt'].' zł</span></th>';
                                //echo 'id rezerwacji: '.$wiersz['id_rezerwacji'];
                                echo '<th><div class="checkbox"><input onchange="podmien(this,'.$wiersz['id_rezerwacji'].')" type="checkbox" name="chackbox"></div></th></tr>';
                            }
                            echo '</table></div>';
                        }
                    }
                    $polaczenie->close();
                }
            }

            catch(Eeception $e)
            {
                echo '<span style="color: red">Błąd serwera. Spróbuj zarejestrować się później</span>';
                //echo '<br>Informacja deweloperska: '.$e;
            }
?>
            </section>
        </article>

        <article class="dane-konta col-12">
            <header>
                <b>POPRZEDNIE REZERWACJE</b>
            </header>
            <section>
<?php
            //Wyłączenie worningów i włączenie wyświetlania wyjątków
            mysqli_report(MYSQLI_REPORT_STRICT);
            require_once "connect.php";

            try
            {
                $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

                if($polaczenie->connect_errno != 0)
                    throw new Exception(mysqli_connect_errno());

                else
                {
                    // Kodowanie polskich znaków
                    $polaczenie->query("SET NAMES utf8");
                    $rezultat = $polaczenie->query("SELECT * FROM rezerwacje INNER JOIN konta ON rezerwacje.id_klienta=konta.id INNER JOIN repertuar ON rezerwacje.id_repertuaru=repertuar.id_repertuaru INNER JOIN filmy ON repertuar.id_filmu=filmy.id_filmu INNER JOIN sale ON repertuar.id_sali=sale.id_sali WHERE rezerwacje.id_klienta='$id_klienta' AND repertuar.czas_rozpoczecia < CAST(CONCAT(CURDATE(),' ',CURTIME()) as DATETIME) ORDER BY repertuar.czas_rozpoczecia ASC");

                    if(!$rezultat)
                        throw new Exception($polaczenie->error);

                    else
                    {
                        $ilosc_filmow = $rezultat->num_rows;

                        if($ilosc_filmow == 0)
                        {
                            echo '<span style="color: gray">Brak wcześniejszych rezerwacji na tym koncie</span>';
                        }

                        else
                        {
                            echo '<span style="font-weight: 500;">Ile rezerwacji: '.$ilosc_filmow.'</span>';
                            echo '<div class="table-responsive"><table class="table" style="text-align: center;"><tr><th style="width: 300px; text-align: center;">Film</th><th style="width: 200px; text-align: center;">Termin</th><th style="text-align: center;">Sala</th><th style="text-align: center;">Rząd</th><th style="text-align: center;">Miejsce</th><th style="text-align: center;">Cena</th><tr>';
                            while($wiersz = $rezultat->fetch_assoc())
                            {
                                echo '<tr><th style="font-weight: 400; text-align: center;">'.$wiersz['tytul'].'</th>';
                                echo '<th style="font-weight: 400;">'.$wiersz['czas_rozpoczecia'].'</th>';
                                echo '<th style="font-weight: 400;">'.$wiersz['nr_sali'].'</th>';
                                echo '<th style="font-weight: 400;">'.$wiersz['rzad'].'</th>';
                                echo '<th style="font-weight: 400;">'.$wiersz['miejsce'].'</th>';
                                echo '<th style="font-weight: 400;">'.$wiersz['koszt'].' zł</th>';
                            }
                            echo '</table></div>';
                        }
                    }
                    $polaczenie->close();
                }
            }

            catch(Eeception $e)
            {
                echo '<span style="color: red">Błąd serwera. Spróbuj zarejestrować się później</span>';
                echo '<br>Informacja deweloperska: '.$e;
            }
?>
            </section>
        </article>
		</div>
      </div>
    </div>

<?php
    include 'side_part/dol.php'
?>
