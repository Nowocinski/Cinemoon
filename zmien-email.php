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
        //Walidacja nowego e-maila
        if(!preg_match('/^[a-zA-Z0-9\.\_\-]+@[a-zA-Z0-9\.\_\-]+[a-z]+$/',$_POST['nowe']))
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_nowe'] = '<span style="color: red;">Niepoprawny adres e-mail</span>';
        }

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
                $rezultat1 = $polaczenie->query("SELECT email FROM klienci WHERE id_klienta='$id_klienta'");
                if(!$rezultat1)
                    throw new Exception($polaczenie->error);
                else
                {
                    $rezultat2 = $polaczenie->query("SELECT email FROM klienci WHERE email='$nowe'");
                    if($polaczenie->connect_errno != 0)
                        throw new Exception(mysqli_connect_errno());
                    if($rezultat2->num_rows > 0)
                    {
                        $poprawna_walidacja = false;
                        $_SESSION['blad_nowe'] = '<span style="color: red;">Podany e-mail jest już przypisany do innego konta</span>';
                    }

                    else
                    {
                        $wiersz = mysqli_fetch_assoc($rezultat1);
                        if($wiersz['email'] != $_POST['stare'])
                        {
                            $poprawna_walidacja = false;
                            $_SESSION['blad_stare'] = '<span style="color: red;">Podany e-mail nie jest zgodne z obecnym</span>';
                        }

                        if($wiersz['email'] == $_POST['stare'] && $poprawna_walidacja == true)
                        {
                            if($polaczenie->query("UPDATE klienci SET email='$nowe' WHERE id_klienta='$id_klienta'"))
                            {
                                $_SESSION['email'] = $nowe;
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
            echo '<span style="color: red;">Błąd serwera. Spróbuj zmienić adres e-mail później</span>';
            //echo '<br>Informacja deweloperska: '.$e;
        }
    }

    $title = 'Zmiana adresu e-mail';
    include "side_part/gora.php";
?>

<div class="container dane-konta3">
        <form class="form" action="zmien-email.php" method="post">
            <span style="text-align: center;"><h3>Zmiana adres e-mail</h3></span>
            
            <div class="form-group">
                <label>Podaj poprzedni adres e-mail</label>
                <input type="text" class="form-control" placeholder="Poprzedni e-mail" name="stare" />
                <?php
                    if(isset($_SESSION['blad_stare']))
                    {
                        echo $_SESSION['blad_stare'];
                        unset($_SESSION['blad_stare']);
                    }
                ?>
            </div>

            <div class="form-group">
                <label>Podaj nowy e-mail</label>
                <input type="text" class="form-control" placeholder="Nowy e-mail" name="nowe" />
                <?php
                    if(isset($_SESSION['blad_nowe']))
                    {
                        echo $_SESSION['blad_nowe'];
                        unset($_SESSION['blad_nowe']);
                    }
                ?>
            </div>
                <button type="submit" class="btn btn-primary">Zmień e-mail</button>
                <button type="reset" class="btn btn-default">Wyczyść</button>
        </form>
</div>

<?php
    include 'side_part/dol.php'
?>