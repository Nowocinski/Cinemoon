<?php
	if(!isset($_GET['pole']))
    {
      header('Location: index.php');
      exit();
    }

	$id = $_GET['pole'];
	
	require_once "connect.php";
    $grafika = $_POST['film'];

    //Wyłączenie worningów i włączenie wyświetlania wyjątków
    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if($polaczenie->connect_errno != 0)
                throw new Exception(mysqli_connect_errno());

        else
        {
            $rezultat = $polaczenie->query("DELETE FROM repertuar WHERE repertuar.id_repertuaru='$id'");
			if(!$rezultat)
                throw new Exception($polaczenie->error);
			else
            {				
				$rezultat = $polaczenie->query("DELETE FROM rezerwacje WHERE rezerwacje.id_repertuaru='$id'");
				if(!$rezultat)
					throw new Exception($polaczenie->error);
				else
				{
					$polaczenie->close();
					$_SESSION['seans_usuniety'] = true;
					header('Location: adminIT-repertuar.php');
					exit();
				}
            }
			$rezultat->free_result();
			$polaczenie->close();
        }
    }

    catch(Exception $e)
    {
        echo '<span style="color: red">Błąd serwera.</span>';
        echo '<br>Informacja deweloperska: '.$e;
    }
?>