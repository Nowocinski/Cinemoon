<?php
    if(!isset($_POST['login']) || !isset($_POST['haslo']))
    {
        header('Location: logowanie.php');
        exit();
    }

    if (session_status() == PHP_SESSION_NONE)
        session_start();

    require_once "connect.php";

    /* Ustanowienie połączenia, @ wycisza ewntualne błędy */
    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

    /* Połączenie z bazą danych */
    if($polaczenie->connect_errno != 0)
        echo "Błąd! ".$polaczenie->connect_errno." Opis: ".$polaczenie->connect_error;

    else
    {
        $login = $_POST['login'];
        $haslo = $_POST['haslo'];
        
        /* Walidacja podanych danych przez użutkonnika - zamiana znaków specjalnych na encje */
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
        //$haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");
        
        /* Obsuga polskich znaków */
        $polaczenie->query("SET NAMES utf8");
        /* Zapytanie do bazy danych */
        /* mysqli_real_escape_string - zabezpiecza skrypt przed wstrzykiwaniem SQL */
        //$sql = "SELECT * FROM klienci WHERE email='$login' AND haslo='$haslo'";
		
		//Logowanie klienta
        if($rezultat = @$polaczenie->query(
            sprintf("SELECT * FROM klienci WHERE email='%s'",
            mysqli_real_escape_string($polaczenie, $login)
        )))
        {
            $ile_userow = $rezultat->num_rows;
            if($ile_userow > 0)
            {
                /* Tworzenie tablicy asocjacyjnej (skojarzeniowej) */
                $wiersz = $rezultat->fetch_assoc();
                if(password_verify($haslo, $wiersz['haslo']))
                {
                    $_SESSION['zalogowany'] = true;

                    $_SESSION['id_klienta'] = $wiersz['id_klienta'];
                    $_SESSION['imie'] = $wiersz['imie'];
                    $_SESSION['nazwisko'] = $wiersz['nazwisko'];
                    $_SESSION['email'] = $wiersz['email'];
                    $_SESSION['nr_telefonu'] = $wiersz['nr_telefonu'];

                    unset($_SESSION['blad']);
                    $rezultat->free_result();
                    header('Location: konto.php');
					exit();
                }
            }
        }

		//Logowanie pracownika
		if($rezultat = @$polaczenie->query(
            sprintf("SELECT * FROM pracownicy WHERE email='%s'",
            mysqli_real_escape_string($polaczenie, $login)
        )))
        {
            $ile_userow = $rezultat->num_rows;
            if($ile_userow > 0)
            {
                /* Tworzenie tablicy asocjacyjnej (skojarzeniowej) */
                $wiersz = $rezultat->fetch_assoc();
                if(password_verify($haslo, $wiersz['haslo']))
                {
                    $_SESSION['zalogowany'] = true;

					$_SESSION['id_pracownika'] = $wiersz['id_pracownika'];
					$_SESSION['typ_konta'] = $wiersz['typ_konta'];
					$_SESSION['imie'] = $wiersz['imie'];
					$_SESSION['nazwisko'] = $wiersz['nazwisko'];
					$_SESSION['miejscowosc'] = $wiersz['miejscowosc'];
					$_SESSION['adres'] = $wiersz['adres'];
					$_SESSION['email'] = $wiersz['email'];
					$_SESSION['nr_telefonu'] = $wiersz['nr_telefonu'];

					unset($_SESSION['blad']);
					$rezultat->free_result();
					if ( 'administratorIT' == $_SESSION['typ_konta'])
						header('Location: adminIT-info.php');
					elseif ( 'pracownik' == $_SESSION['typ_konta'])
						header('Location: pracownik.php');
					elseif ( 'menadzerPracownikow' == $_SESSION['typ_konta'])
						header('Location: menadzer-pracownikow.php');
					elseif ( 'specjalistaDSObslugi' == $_SESSION['typ_konta'])
						header('Location: specjalista-ds-obslugi.php');
					//else ...
                }
                
                else
                {
                    $_SESSION['blad'] = "Nieprawidłowy login lub hasło";
                    header('Location: logowanie.php');
                }
            }

            else
            {
                $_SESSION['blad'] = "Nieprawidłowy login lub hasło";
                header('Location: logowanie.php');
            }
        }
        
        $polaczenie->close();
    }
?>