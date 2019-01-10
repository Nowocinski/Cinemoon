<?php
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    if(!isset($_SESSION['zalogowany']))
    {
        header('Location: logowanie.php');
        exit();
    }

    if(isset($_POST['nowe']))
    {
        $poprawna_walidacja = true;
        $nowe = $_POST['nowe'];
        $stare = $_POST['stare'];
        $id_klienta = $_SESSION['id_klienta'];
//----------------------------------------------------------------------------------------------------------------------------------
        //Walidacja nowego imienia
        if(strlen($_POST['nowe']) < 3 || strlen($_POST['nowe']) > 20)
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_nowe'] = '<span style="color: red;">Imię musi składać się od 3 do 20 znaków</span>';
        }

        if(!preg_match('/^[a-zA-Z\ą\ć\ę\ł\ń\ó\ś\ź\ż\Ą\Ć\Ę\Ł\Ń\Ó\Ś\Ź\Ż]+$/',$_POST['nowe']))
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_nowe'] = '<span style="color: red;">Imię nie może składać się ze znaków specjalnych, spacji ani liczb</span>';
        }
//----------------------------------------------------------------------------------------------------------------------------------
        if(strlen($_POST['stare']) == 0)
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_stare'] = '<span style="color: red;">To pole nie możę być puste</span>';
        }
//----------------------------------------------------------------------------------------------------------------------------------
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
                $rezultat = $polaczenie->query("SELECT imie FROM klienci WHERE id_klienta='$id_klienta'");
                if(!$rezultat)
                    throw new Exception($polaczenie->error);
                else
                {
                    $wiersz = mysqli_fetch_assoc($rezultat);
                    if($wiersz['imie'] != $_POST['stare'])
                    {
                        $poprawna_walidacja = false;
                        $_SESSION['blad_stare'] = '<span style="color: red;">Podane imie nie jest zgodne z obecnym</span>';
                    }

                    if($wiersz['imie'] == $_POST['stare'] && $poprawna_walidacja == true)
                    {
                        if($polaczenie->query("UPDATE klienci SET imie='$nowe' WHERE id_klienta='$id_klienta'"))
                        {
                            $_SESSION['imie'] = $nowe;
                            unset($_SESSION['blad_stare']);
                            unset($_SESSION['blad_nowe']);
                            unset($_POST['stare']);
                            unset($_POST['nowe']);

                            header('Location: konto.php');
                            exit();
                        }

                        else
                            throw new Exception($polaczenie->error);
                    }
                }
                $rezultat->free_result();
                $polaczenie->close();
            }
        }

        catch(Exception $e)
        {
            echo '<span style="color: red;">Błąd serwera. Spróbuj zmienić imię później</span>';
            //echo '<br>Informacja deweloperska: '.$e;
        }
    }

    $title = 'Zmiana imienia';
    include "side_part/gora.php";
?>

<div class="container dane-konta3">
        <form class="form" action="zmien-imie.php" method="post">
            <span style="text-align: center;"><h3>Zmiana imienia</h3></span>

            <div class="form-group">
                <label>Podaj poprzednie imię</label>
                <input type="text" class="form-control" placeholder="Poprzednie imię" name="stare" />
                <?php
                    if(isset($_SESSION['blad_stare']))
                    {
                        echo $_SESSION['blad_stare'];
                        unset($_SESSION['blad_stare']);
                    }
                ?>
            </div>

            <div class="form-group">
                <label>Podaj nowę imię</label>
                <input type="text" class="form-control" placeholder="Nowę imię" name="nowe" />
                <?php
                    if(isset($_SESSION['blad_nowe']))
                    {
                        echo $_SESSION['blad_nowe'];
                        unset($_SESSION['blad_nowe']);
                    }
                ?>
            </div>
                <button type="submit" class="btn btn-danger">Zmień imię</button>
                <button type="reset" class="btn btn-default">Wyczyść</button>
        </form>
</div>

<?php
    include 'side_part/dol.php'
?>
