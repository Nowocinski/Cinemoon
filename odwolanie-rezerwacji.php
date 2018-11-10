<?php
    if (session_status() == PHP_SESSION_NONE)
        session_start();
    if(!isset($_SESSION['zalogowany']) || !isset($_POST['przycisk']))
    {
      header('Location: index.php');
      exit();
    }

    $id_rezerwacji = $_POST['przycisk'];
    //echo $_POST['przycisk'];

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
        $polaczenie->query("SET NAMES utf8");
        $rezultat = $polaczenie->query("SELECT id_rezerwacji FROM rezerwacje WHERE id_rezerwacji='$id_rezerwacji'");

        if(!$rezultat)
            throw new Exception($polaczenie->error);
        else
        {
            if($rezultat->num_rows == 0)
              throw new Exception('Brak rezerwacji w bazie');
            else
            {
              if($polaczenie->query("DELETE FROM rezerwacje WHERE id_rezerwacji='$id_rezerwacji'"))
              {
                header('Location: konto.php');
                exit();
              }
              else
                throw new Exception($polaczenie->error);
            }
            $rezultat->free_result();
        }
        $polaczenie->close();
      }
    }
    catch (Exception $e)
    {
      //echo '<br>Informacja deweloperska: '.$e;
    }
?>
