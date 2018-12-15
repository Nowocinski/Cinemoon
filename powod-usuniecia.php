<?php
    if(session_status() == PHP_SESSION_NONE)
        session_start();
	
	if(isset($_POST['powod']))
	{
		require_once 'connect.php';

		try
		{
			$polaczenie = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
		}
		catch(PDOException $e)
		{
			echo "Nie można nazwiązać połączenia z bazą danych";
		}

		$zapytanie = $polaczenie->prepare("INSERT INTO usuniniete_konta VALUES ('', :po, :du)");
		$zapytanie->bindValue(':po', $_POST['powod'], PDO::PARAM_STR);
		$zapytanie->bindValue(':po', date('Y-m-d H:i:s'), PDO::PARAM_STR);
		$zapytanie->execute();
	}

    if(!isset($_SESSION['konto_zostalo_usuniete']))
    {
        header('Location: index.php');
        exit();
    }
    else
    {
      unset($_SESSION['konto_zostalo_usuniete']);
      session_unset();
    }

    $title = "Konto zostało usunięte";
    include "side_part/gora.php";
    include "side_part/nav.php";
?>

<div class="container text-center dane-konta3">
    <div class="row">
        <div class="col-12"><h3>Konto zostało z powodzeniem usunięte</h3></div>
        <div class="col-12"><h5>W celu poprawy jakości świadczonych usług prosilibiśmy o wypełnienie poniższej ankiety</h5></div>

        <div class="col-12">
            <form class="form" action="powod-usuniecia.php" method="post">
                <div class="col-12">
                Powodem mojego usunięcia konta było:

                <select name="powod">
                  <option value="Nieintuicyjność działania konta">Nieintuicyjność działania konta</option>
                  <option value="Brak wspracia ze strony administracji">Brak wspracia ze strony administracji</option>
                  <option value="Duża ilość błędów podczas użytkowania konta">Duża ilość błędów podczas użytkowania konta</option>
                  <option value="Ciężko powiedzieć">Ciężko powiedzieć</option>
                <select>.
                </div>
                <button type="submit" class="btn btn-primary mt-3">Wyślij</button>
            </form>
        </div>
    </div>
</div>

<?php
    include "side_part/dol.php";
?>
