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
        //Walidacja nowego numeru telefonu
        if(!preg_match('/^[0-9]{9}$/', $_POST['nowe']))
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_nowe'] = '<span style="color: red;">Numer telefonu musi składać się z 9 cyfr bez znaków specjalnych oraz spacji</span>';
        }

        if(strlen($_POST['nowe']) == 0)
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_nowe'] = '<span style="color: red;">To pole nie może być puste</span>';
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
                $rezultat1 = $polaczenie->query("SELECT nr_telefonu FROM klienci WHERE id_klienta='$id_klienta'");
                if(!$rezultat1)
                    throw new Exception($polaczenie->error);
                else
                {
                    $rezultat2 = $polaczenie->query("SELECT nr_telefonu FROM klienci WHERE nr_telefonu='$nowe' AND nr_telefonu!=''");
                    if($polaczenie->connect_errno != 0)
                        throw new Exception(mysqli_connect_errno());
                    if($rezultat2->num_rows > 0)
                    {
                        $poprawna_walidacja = false;
                        $_SESSION['blad_nowe'] = '<span style="color: red;">Podany numer telefonu jest już przypisany do innego konta</span>';
                    }

                    else
                    {
                        $wiersz = mysqli_fetch_assoc($rezultat1);
                        if($wiersz['nr_telefonu'] != $_POST['stare'])
                        {
                            $poprawna_walidacja = false;
                            $_SESSION['blad_stare'] = '<span style="color: red;">Podany numer telefonu nie jest zgodne z obecnym</span>';
                        }

                        if($wiersz['nr_telefonu'] == $_POST['stare'] && $poprawna_walidacja == true)
                        {
                            if($polaczenie->query("UPDATE klienci SET nr_telefonu='$nowe' WHERE id_klienta='$id_klienta'"))
                            {
                                $_SESSION['nr_telefonu'] = $nowe;
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
                }
                $rezultat1->free_result();
                $rezultat2->free_result();
                $polaczenie->close();
            }
        }

        catch(Exception $e)
        {
            echo '<span style="color: red;">Błąd serwera. Spróbuj zmienić numer telefonu później</span>';
            //echo '<br>Informacja deweloperska: '.$e;
        }
    }

    $title = 'Zmiana numeru telefonu';
    include "side_part/gora.php";
?>

<div class="container dane-konta3">
        <form class="form" action="zmien-numer.php" method="post">
            <span style="text-align: center;"><h3>Zmiana numeru telefonu</h3></span>

            <div class="form-group">
                <label>Podaj poprzedni numer telefonu</label>
                <input type="text" class="form-control" placeholder="Poprzedni numer" name="stare" />
                <?php
                    if(isset($_SESSION['blad_stare']))
                    {
                        echo $_SESSION['blad_stare'];
                        unset($_SESSION['blad_stare']);
                    }
                ?>
            </div>
            <div class="form-group">
                <label>Podaj nowy numer telefonu</label>
                <input type="text" class="form-control" placeholder="Nowy numer" name="nowe" />
                <?php
                    if(isset($_SESSION['blad_nowe']))
                    {
                        echo $_SESSION['blad_nowe'];
                        unset($_SESSION['blad_nowe']);
                    }
                ?>
            </div>
                <button type="submit" class="btn btn-danger">Zmień numer</button>
                <button type="reset" class="btn btn-default">Wyczyść</button>



        </form>
</div>

<?php
    include 'side_part/dol.php'
?>
