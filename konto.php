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
?>
<div class="konto">
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
                
                <table>
                    <tr>
                        <th>Film</th>
                        <th>Termin</th>
                        <th>Sala</th>
                        <th>Rząd</th>
                        <th>Miejsce</th>
                    </tr>
                    
                    <tr>
                        <th><span style="font-weight: 400;">Film</span></th>
                        <th>Termin</th>
                        <th>Sala</th>
                        <th>Rząd</th>
                        <th>Miejsce</th>
                    </tr>
                </table>
            </section>
        </article>
        
        <article class="dane-konta">
            <header>
                <b>POPRZEDNIE REZERWACJE</b>
            </header>
            <section>
                
                <table>
                    <tr>
                        <th>Film</th>
                        <th>Termin</th>
                        <th>Sala</th>
                        <th>Rząd</th>
                        <th>Miejsce</th>
                    </tr>
                </table>
            </section>
        </article>
    </div>
</div>
<?php
    include 'side_part/dol.php'
?>