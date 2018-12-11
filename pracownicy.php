<?php
    if(session_status() == PHP_SESSION_NONE)
      session_start();

    if(isset($_SESSION['zalogowany']))
    {
        header('Location: index.php');
        exit();
    }

    if(isset($_SESSION['typ_konta']) && $_SESSION['typ_konta'] == 'administratorIT')
    {
        header('Location: adminIT-info.php');
        exit();
    }
	
	if(isset($_SESSION['typ_konta']) && $_SESSION['typ_konta'] == 'menadzerPracownikow')
    {
        header('Location: menadzer-pracownikow.php');
        exit();
    }
	
	if(isset($_SESSION['typ_konta']) && $_SESSION['typ_konta'] == 'pracownik')
    {
        header('Location: pracownik.php');
        exit();
    }
	
	if(isset($_SESSION['typ_konta']) && $_SESSION['typ_konta'] == 'specjalistaDSObslugi')
    {
        header('Location: specjalista-ds-obslugi.php');
        exit();
    }

    $title = "Pracownicy";
    $pracownik = true;

    if(isset($_POST['login']))
    {
      require_once "connect.php";

      //Wyłączenie worningów i włączenie wyświetlania wyjątków
      mysqli_report(MYSQLI_REPORT_STRICT);

      try
      {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

        if($polaczenie->connect_errno != 0)
            throw new Exception(mysqli_connect_errno());
        else
        {
          $login = $_POST['login'];
          $haslo = $_POST['haslo'];

          /* Walidacja podanych danych przez użutkonnika - zamiana znaków specjalnych na encje */
          $login = htmlentities($login, ENT_QUOTES, "UTF-8");

          /* Obsuga polskich znaków */
          $polaczenie->query("SET NAMES utf8");
          if($rezultat = $polaczenie->query(
              sprintf("SELECT * FROM pracownicy WHERE email='%s'",
              mysqli_real_escape_string($polaczenie, $login)
          )))
          {
            $ile_pracownikow = $rezultat->num_rows;
            if($ile_pracownikow > 0)
            {
              $wiersz = $rezultat->fetch_assoc();
              if(password_verify($haslo, $wiersz['haslo']))
              {
                $_SESSION['zalogowanypracownik'] = true;

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
              else {
                $_SESSION['blad'] = '<span style="color: red;">Hasło jest nieprawidłowe</span>';
              }
            }
            else
            {
              $_SESSION['blad'] = '<span style="color: red;">Błędny login lub hasło</span>';
            }
          }

          else
            throw new Exception($polaczenie->error);

          $polaczenie->close();
        }
      }

      catch(Exception $e)
      {
          echo '<span style="color: red">Błąd serwera. Spróbuj zarejestrować się później</span>';
          echo '<br>Informacja deweloperska: '.$e;
      }
    }

    include "side_part/gora.php";
?>



<div class="dane-konta2">
<header>
    <h1>Konto pracownika</h1>
</header>

<form action="#" method="post" class="form">
    <div class="form-group">
        <label>Login</label><br>
        <input type="text" name="login" class="form-control" placeholder="Twój adres e-mail pracownika" />
    </div>

    <div class="form-group">
        <label>Haslo</label><br>
        <input type="password" name="haslo" class="form-control" placeholder="Twoje hasło" />
        <?php
          if(isset($_SESSION['blad']))
          {
            echo $_SESSION['blad'];
            unset($_SESSION['blad']);
          }
        ?>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-warning">Zaloguj</button>
        <button type="reset" class="btn btn-default">Wyczyść</button>
    </div>
</form>
</div>




<?php
    include "side_part/dol.php";
    //--------------------------------------------------------------------------

?>
