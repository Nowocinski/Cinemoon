<?php
    session_start();
    if(!isset($_SESSION['zalogowany']))
    {
        header('Location: logowanie.php');
        exit();
    }

    if(isset($_SESSION['pierwszewejscie']))
    {
        echo 'Dziękujemy za zalogowanie się.<br>Oto Twoje konto<br>';
        unset($_SESSION['pierwszewejscie']);
    }

    if(isset($_SESSION['zwd_imie'])) unset($_SESSIN['zwd_imie']);
    if(isset($_SESSION['zwd_nazwisko'])) unset($_SESSIN['zwd_nazwisko']);
    if(isset($_SESSION['zwd_email'])) unset($_SESSIN['zwd_email']);
    if(isset($_SESSION['zwd_haslo1'])) unset($_SESSIN['zwd_haslo1']);
    if(isset($_SESSION['zwd_haslo2'])) unset($_SESSIN['zwd_haslo2']);
    if(isset($_SESSION['zwd_nr_telefonu'])) unset($_SESSIN['zwd_nr_telefonu']);

    if(isset($_SESSION['blad_imie'])) unset($_SESSIN['blad_imie']);
    if(isset($_SESSION['blad_nazwisko'])) unset($_SESSIN['blad_nazwisko']);
    if(isset($_SESSION['blad_email'])) unset($_SESSIN['blad_email']);
    if(isset($_SESSION['blad_haslo1'])) unset($_SESSIN['blad_haslo1']);
    if(isset($_SESSION['blad_haslo2'])) unset($_SESSIN['blad_haslo2']);
    if(isset($_SESSION['blad_nr_telefonu'])) unset($_SESSIN['blad_nr_telefonu']);

    $title = 'Konto';

    include "side_part/gora.php";
    include "side_part/nav.php";
    $id_klienta = $_SESSION['id_klienta'];
?>

    <div class="container">
        [<a href="wyloguj.php">Wyloguj</a>]<br>

        <article class="dane-konta">
            <header>
                <b>DANE KONTA</b>
            </header>
            <ul>
                <li><b>Imie:</b> <?php echo $_SESSION['imie'] ?></li>
                <li><b>Nazwisko:</b> <?php echo $_SESSION['nazwisko'] ?></li>
                <li><b>E-mail:</b> <?php echo $_SESSION['email'] ?></li>
                
                <li><b>Numer telefonu:</b> <?php if($_SESSION['nr_telefonu'] != '') echo $_SESSION['nr_telefonu']; else echo '<span style="color: gray">(nie podano)</span><br>';?></li>
            </ul>
        </article>
        <article class="dane-konta">
            <header>
                <b>USTAWIENIA KONTA</b>
            </header>
            <ul>
                <a href="#">Zmień imię</a>
            </ul>
            <ul>
                <a href="#">Zmień nazwisko</a>
            </ul>
            <ul>
                <a href="#">Zmień adres e-mail</a>
            </ul>
            <ul>
                <a href="#">Zmień adres hasło</a>
            </ul>
            <ul>
                <a href="#">Zmień numer telefonu</a>
            </ul>
            <ul>
                <a href="#">Usuń konto</a>
            </ul>
        </article>
        
        <article class="dane-konta">
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
                    $rezultat = $polaczenie->query("SELECT * FROM rezerwacje INNER JOIN klienci ON rezerwacje.id_klienta=klienci.id_klienta INNER JOIN repertuar ON rezerwacje.id_repertuaru=repertuar.id_repertuaru INNER JOIN filmy ON repertuar.id_filmu=filmy.id_filmu INNER JOIN sale ON repertuar.id_sali=sale.id_sali WHERE rezerwacje.id_klienta='$id_klienta' AND repertuar.czas_rozpoczecia > CAST(CONCAT(CURDATE(),' ',CURTIME()) as DATETIME) ORDER BY repertuar.czas_rozpoczecia ASC");
                    
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
                            echo '<div class="table"><table class="table"><tr><th>Film</th><th>Termin</th><th>Sala</th><th>Rząd</th><th>Miejsce</th><th>Cena</th><tr>';
                            while($wiersz = $rezultat->fetch_assoc())
                            {
                                echo '<tr><th><span style="font-weight: 400;">'.$wiersz['tytul'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['czas_rozpoczecia'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['nr_sali'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['rzad'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['miejsce'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['koszt'].' zł</span></th>';
                                echo '<th><span style="font-weight: 400;"><a href="odwolaj-rezerwacje.php" class="btn btn-warning" role="button">Odwołaj</a></form></span></th></tr>';
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
        
        <article class="dane-konta">
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
                    $rezultat = $polaczenie->query("SELECT * FROM rezerwacje INNER JOIN klienci ON rezerwacje.id_klienta=klienci.id_klienta INNER JOIN repertuar ON rezerwacje.id_repertuaru=repertuar.id_repertuaru INNER JOIN filmy ON repertuar.id_filmu=filmy.id_filmu INNER JOIN sale ON repertuar.id_sali=sale.id_sali WHERE rezerwacje.id_klienta='$id_klienta' AND repertuar.czas_rozpoczecia < CAST(CONCAT(CURDATE(),' ',CURTIME()) as DATETIME) ORDER BY repertuar.czas_rozpoczecia ASC");
                    
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
                            echo '<div class="table"><table class="table"><tr><th>Film</th><th>Termin</th><th>Sala</th><th>Rząd</th><th>Miejsce</th><th>Cena</th><tr>';
                            while($wiersz = $rezultat->fetch_assoc())
                            {
                                echo '<tr><th><span style="font-weight: 400;">'.$wiersz['tytul'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['czas_rozpoczecia'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['nr_sali'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['rzad'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['miejsce'].'</span></th>';
                                echo '<th><span style="font-weight: 400;">'.$wiersz['koszt'].' zł</span></th>';
                                echo '<th><span style="font-weight: 400;"><a href="odwolaj-rezerwacje.php" class="btn btn-warning" role="button">Odwołaj</a></form></span></th></tr>';
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

<?php
    include 'side_part/dol.php'
?>