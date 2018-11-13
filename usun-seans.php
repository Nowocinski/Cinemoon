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
            if($polaczenie->query(""))
			{
				//...
			}
        }
        $polaczenie->close();
    }

    catch(Exception $e)
    {
        echo '<span style="color: red">Błąd serwera. Spróbuj zarejestrować się później</span>';
        echo '<br>Informacja deweloperska: '.$e;
    }
?>