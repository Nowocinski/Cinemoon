<?php

    if (session_status() == PHP_SESSION_NONE)
        session_start();

    if(!isset($_SESSION['zalogowany']))
    {
        header('Location: logowanie.php');
        exit();
    }

    $title = 'Usunięcie konta';
    include "side_part/gora.php";

    if(isset($_POST['email']))
    {
        $poprawna_walidacja = true;
        $id_klienta = $_SESSION['id_klienta'];
        $email = $_POST['email'];
        $haslo = $_POST['haslo'];
//----------------------------------------------------------------------------------------------------------------------------------
        //Walidacja e-maila
        if(!preg_match('/^[a-zA-Z0-9\.\_\-]+@[a-zA-Z0-9\.\_\-]+[a-z]+$/',$_POST['email']))
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_email'] = '<span style="color: red;">Niepoprawny adres e-mail</span>';
        }

        if(strlen($_POST['email']) == 0)
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_email'] = '<span style="color: red;">To pole nie możę być puste</span>';
        }
//----------------------------------------------------------------------------------------------------------------------------------
        //Walidacja hasła
        if(strlen($_POST['haslo']) == 0)
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_haslo'] = '<span style="color: red;">To pole nie możę być puste</span>';
        }
//----------------------------------------------------------------------------------------------------------------------------------
        //Walidacja checkbox'a
        if(!isset($_POST['potwierdzenie']))
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_potwierdzenie'] = '<br><span style="color: red;">Potwierdzenie usunięcia konta musi być zaznaczone</span>';
        }
//----------------------------------------------------------------------------------------------------------------------------------
        if($poprawna_walidacja)
        {
            try
            {
                //Wyłączenie worningów i włączenie wyświetlania wyjątków
                mysqli_report(MYSQLI_REPORT_STRICT);
                
                require_once "connect.php";
                $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

                if($polaczenie->connect_errno != 0)
                        throw new Exception(mysqli_connect_errno());
                else
                {
                    // Kodowanie polskich znaków
                    $polaczenie->query("SET NAMES utf8");
                    //Sprawdzenie adresu e-mail
                    $rezultat = $polaczenie->query("SELECT email, haslo FROM klienci WHERE id_klienta='$id_klienta' AND email='$email'");
                    if(!$rezultat)
                        throw new Exception($polaczenie->error);
                    else
                    {
                        if($rezultat->num_rows == 0)
                        {
                            $poprawna_walidacja = false;
                            $_SESSION['blad_email'] = '<span style="color: red;">Ten adres e-mail nie jest przepisany do tego konta</span>';
                        }
                        else
                        {
                            //Sprawdzenie hasła
                            $wiersz = $rezultat->fetch_assoc();
                            if(!password_verify($haslo, $wiersz['haslo']))
                            {
                                $poprawna_walidacja = false;
                                $_SESSION['blad_haslo'] = '<br><span style="color: red;">Nieprawidłowe hasło</span>';
                            }
                            elseif(password_verify($haslo, $wiersz['haslo']) && $poprawna_walidacja == true)
                            {
                                //Usuwanie rezerwacji użytkownika
                                $rezultat = $polaczenie->query("DELETE FROM rezerwacje WHERE rezerwacje.id_klienta='$id_klienta'");
                                if(!$rezultat)
                                    throw new Exception($polaczenie->error);
                                else
                                {
                                    //Usuwanie danych użytkownika
                                    $rezultat = $polaczenie->query("DELETE FROM klienci WHERE klienci.id_klienta='$id_klienta'");
                                    if(!$rezultat)
                                        throw new Exception($polaczenie->error);

                                    else
                                    {
                                        $rezultat->free_result();
                                        $polaczenie->close();
                                        $_SESSION['konto_zostalo_usuniete'] = true;
                                        //session_unset();
                                        header('Location: powod-usuniecia.php');
                                    }
                                }
                            }
                        }
                    }
                }

                $rezultat->free_result();
                $polaczenie->close();
            }

            catch(Exception $e)
            {
                echo '<span style="color: red;">Błąd serwera. Spróbuj zarejestrować się później</span>';
                echo '<br>Informacja deweloperska: '.$e;
            }
        }
//----------------------------------------------------------------------------------------------------------------------------------
    }
?>


<div class="container dane-konta3">
        <form class="form" action="usun-konto.php" method="post">
            <span style="text-align: center;">
                <h3>Usunięcie konta</h3>
                <h6><p><?php echo $_SESSION['imie'] ?>, czy na pewno chcesz usunąć swoje konto? Jeśli tego dokonasz wszystkie twoje dane wraz z rezerwacjami zostaną bezpowrotnie utracone.</p></h6>
            </span>
            <div class="form-group">
                <label>Podaj swój adres e-mail</label>
                <input type="text" class="form-control" placeholder="Adres e-mail" name="email" />
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
            <input type="password" class="form-control" placeholder="Twoje hasło" name="haslo"/>
            <?php
                if(isset($_SESSION['blad_haslo']))
                {
                    echo $_SESSION['blad_haslo'];
                    unset($_SESSION['blad_haslo']);
                }
            ?>
            </div>

            <div class="form-group">
            <label><input type="checkbox" name="potwierdzenie"> Tak, chcę usunąć konto.</label>
            <?php
                if(isset($_SESSION['blad_potwierdzenie']))
                {
                    echo $_SESSION['blad_potwierdzenie'];
                    unset($_SESSION['blad_potwierdzenie']);
                }
            ?>
            </div>
            
                <button type="submit" class="btn btn-danger">Usuń konto</button>
                <button type="reset" class="btn btn-default">Wyczyść</button>
        </form>
</div>


<?php
    include 'side_part/dol.php'
?>