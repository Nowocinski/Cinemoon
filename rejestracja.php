<?php
	include "side_part/przekierowanie-pracownikow.php";

    if (session_status() == PHP_SESSION_NONE)
        session_start();
	
	if(isset($_SESSION['zalogowany']))
    {
        header('Location: konto.php');
        exit();
    }

    if(isset($_POST['email']))
    {
        $poprawna_walidacja = true;
        $imie = $_POST['imie'];
        $nazwisko = $_POST['nazwisko'];
        $email = $_POST['email'];
        $numertelefonu = $_POST['numertelefonu'];
        $typ = $_POST['typ'];

        //Walidacja imienia
        if(strlen($_POST['imie']) < 3 || strlen($_POST['imie']) > 20)
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_imie'] = '<span style="color: red;">Imię musi składać się od 3 do 20 znaków</span>';
        }

        if(!preg_match('/^[a-zA-Z\ą\ć\ę\ł\ń\ó\ś\ź\ż\Ą\Ć\Ę\Ł\Ń\Ó\Ś\Ź\Ż]+$/',$_POST['imie']))
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_imie'] = '<span style="color: red;">Imię nie może składać się ze znaków specjalnych, spacji ani liczb</span>';
        }
//----------------------------------------------------------------------------------------------------------------------------------
        //Walidacja nazwiska
        if(strlen($_POST['nazwisko']) < 2 || strlen($_POST['nazwisko']) > 30)
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_nazwisko'] = '<span style="color: red;">Nazwisko musi składać się od 2 do 30 znaków</span>';
        }

        if(!preg_match('/^[a-zA-Z\ą\ć\ę\ł\ń\ó\ś\ź\ż\Ą\Ć\Ę\Ł\Ń\Ó\Ś\Ź\Ż]+$/',$_POST['nazwisko']))
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_nazwisko'] = '<span style="color: red;">Nazwisko nie może składać się ze znaków specjalnych, spacji ani liczb</span>';
        }
//----------------------------------------------------------------------------------------------------------------------------------
        //Walidacja e-maila
        if(!preg_match('/^[a-zA-Z0-9\.\_\-]+@[a-zA-Z0-9\.\_\-]+[a-z]+$/',$_POST['email']))
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_email'] = '<span style="color: red;">Niepoprawny adres e-mail</span>';
        }
//----------------------------------------------------------------------------------------------------------------------------------
        //Walidacja hasła1
        if(strlen($_POST['haslo1']) < 6 || strlen($_POST['haslo1']) > 30)
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_haslo1'] = '<span style="color: red;">Hasło musi składać się od 6 do 30 znaków</span>';
        }

        if($_POST['haslo1'] != $_POST['haslo2'] || $_POST['haslo1'] == '' || $_POST['haslo2'] == '')
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_haslo2'] = '<span style="color: red;">Hasła nie pasują do siebie</span>';
        }

        $zahasowane_haslo = password_hash($_POST['haslo1'], PASSWORD_DEFAULT);
//----------------------------------------------------------------------------------------------------------------------------------
        //Walidacja numeru telefonu
        if(!preg_match('/^[0-9]{9}$/', $_POST['numertelefonu']) && strlen($_POST['numertelefonu']) != 0)
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_numertelefonu'] = '<span style="color: red;">Numer telefonu musi składać się z 9 cyfr bez znaków specjalnych oraz spacji</span>';
        }
//----------------------------------------------------------------------------------------------------------------------------------
        //Walidacja regulaminu
        if(!isset($_POST['regulamin']))
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_regulamin'] = '<span style="color: red;"><br>Nie zaakceptowano regulaminu</span>';
        }
//----------------------------------------------------------------------------------------------------------------------------------
        //Walidacja reCAPTCHA
        $sekret = '6Lc9CnYUAAAAAA_OnZwj8N_I7bElYFivqNxvmLHT';

        $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);

        $odpowiedz = json_decode($sprawdz);

        if(!($odpowiedz->success))
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_recaptcha'] = '<span style="color: red;"><br>Brak weryfikacji reCAPTCHA</span>';
        }
//----------------------------------------------------------------------------------------------------------------------------------
        //Sprawdzenie poprawności danych z bazą

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

                $rezultat = $polaczenie->query("SELECT id_klienta FROM klienci WHERE email='$email' AND email!=''");
                $rezultatP = $polaczenie->query("SELECT id_pracownika FROM pracownicy WHERE email='$email' AND email!=''");

                if(!$rezultat)
                    throw new Exception($polaczenie->error);
                else
                {
                    if($rezultat->num_rows > 0 || $rezultatP->num_rows > 0)
                    {
                        $poprawna_walidacja = false;
                        $_SESSION['blad_email'] = '<span style="color: red;">Podany e-mail jest już przypisany do innego konta</span>';
                    }
                }

                $rezultat2 = $polaczenie->query("SELECT id_klienta FROM klienci WHERE nr_telefonu='$numertelefonu' AND nr_telefonu!=''");
                $rezultat2P = $polaczenie->query("SELECT id_pracownika FROM pracownicy WHERE nr_telefonu='$numertelefonu' AND nr_telefonu!=''");

                if(!$rezultat2)
                    throw new Exception($polaczenie->error);
                else
                {
                    if($rezultat2->num_rows > 0 || $rezultat2P->num_rows > 0)
                    {
                        $poprawna_walidacja = false;
                        $_SESSION['blad_numertelefonu'] = '<span style="color: red;">Podany numer jest już przypisany do innego konta</span>';
                    }
                }

                if($poprawna_walidacja)
                {
                    if($polaczenie->query("INSERT INTO klienci VALUES (NULL, '$imie', '$nazwisko', '$email', '$zahasowane_haslo', '$numertelefonu', '$typ')"))
                    {
                        $_SESSION['zalogowany'] = true;
						$_SESSION['pierwszewejscie'] = true;

                        $_SESSION['imie'] = $imie;
                        $_SESSION['nazwisko'] = $nazwisko;
                        $_SESSION['email'] = $email;
                        $_SESSION['nr_telefonu'] = $numertelefonu;
                        $_SESSION['typ'] = $typ;

                        header('Location: konto.php');
                        exit();
                    }

                    else
                    throw new Exception($polaczenie->error);
                }
                $rezultat->free_result();
                $rezultat2->free_result();
                $polaczenie->close();
            }
        }

        catch(Exception $e)
        {
            echo '<span style="color: red;">Błąd serwera. Spróbuj zarejestrować się później</span>';
            //echo '<br>Informacja deweloperska: '.$e;
        }

    }
//----------------------------------------------------------------------------------------------------------------------------------
    $title = "Rejestracja";

    include "side_part/gora.php";
    include "side_part/nav.php";


?>
<div class="dane-konta2">
    <header>
        <h1>REJESTRACJA</h1>
        <section>
            Masz konto? <a href="logowanie.php">Zaloguj się</a>
        </section>
    </header>

    <form class="form" action="rejestracja.php" method="post">
        <div class="form-group">
            <label>Imię</label>
            <input type="text" class="form-control" placeholder="Twoje imię" name="imie" />
            <?php
                if(isset($_SESSION['blad_imie']))
                {
                    echo $_SESSION['blad_imie'];
                    unset($_SESSION['blad_imie']);
                }
            ?>
        </div>

        <div class="form-group">
            <label for="emailField">Nazwisko</label>
            <input type="text" class="form-control" placeholder="Twój nazwisko" name="nazwisko"/>
            <?php
                if(isset($_SESSION['blad_nazwisko']))
                {
                    echo $_SESSION['blad_nazwisko'];
                    unset($_SESSION['blad_nazwisko']);
                }
            ?>
        </div>

        <div class="form-group">
            <label for="emailField">E-mail</label>
            <input type="text" class="form-control" placeholder="Twój adres e-mail" name="email"/>
            <?php
                if(isset($_SESSION['blad_email']))
                {
                    echo $_SESSION['blad_email'];
                    unset($_SESSION['blad_email']);
                }
            ?>
        </div>

        <div class="form-group">
            <label for="phoneField">Haslo</label>
            <input type="password" class="form-control" placeholder="Twoje hasło" name="haslo1"/>
            <?php
                if(isset($_SESSION['blad_haslo1']))
                {
                    echo $_SESSION['blad_haslo1'];
                    unset($_SESSION['blad_haslo1']);
                }
            ?>
        </div>

        <div class="form-group">
            <label for="phoneField">Powtórz haslo</label>
            <input type="password" class="form-control" placeholder="Twoje hasło" name="haslo2"/>
            <?php
                if(isset($_SESSION['blad_haslo2']))
                {
                    echo $_SESSION['blad_haslo2'];
                    unset($_SESSION['blad_haslo2']);
                }
            ?>
        </div>

        <div class="form-group">
            <label for="phoneField">Numer telefon (opcjonalnie)</label>
            <input type="text" class="form-control" placeholder="Twój numer telefonu" name="numertelefonu"/>
            <?php
                if(isset($_SESSION['blad_numertelefonu']))
                {
                    echo $_SESSION['blad_numertelefonu'];
                    unset($_SESSION['blad_numertelefonu']);
                }
            ?>
        </div>

        <div class="form-group">
            <label for="phoneField">Typ konta</label>
            <select name="typ">
                <option>normalny</option>
                <option>studencki</option>
            </select>
        </div>

        <div class="form-group">
            <label><input type="checkbox" name="regulamin"> Przeczytałem i akceptuję regulamin</label>
            <?php
                if(isset($_SESSION['blad_regulamin']))
                {
                    echo $_SESSION['blad_regulamin'];
                    unset($_SESSION['blad_regulamin']);
                }
            ?>
        </div>

        <div class="form-group">
            <div class="g-recaptcha" data-sitekey="6Lc9CnYUAAAAALY-tIpqd_uNVTjOBjnkJZX18seq"></div>
            <?php
                if(isset($_SESSION['blad_recaptcha']))
                {
                    echo $_SESSION['blad_recaptcha'];
                    unset($_SESSION['blad_recaptcha']);
                }
            ?>
        </div>

        <button type="submit" class="btn btn-primary">Wyślij</button>
        <button type="reset" class="btn btn-default">Wyczyść</button>
    </form>
</div>

<?php
    include "side_part/dol.php";
?>
