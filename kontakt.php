<?php
	include "side_part/przekierowanie-pracownikow.php";

	$title = "Kontakt";

    include "side_part/gora.php";
    include "side_part/nav.php";

	if(isset($_POST['imie']))
	{
		if(!preg_match('/^[a-zA-Z0-9]+(-[a-zA-Z0-9]+)*@([a-zA-Z0-9]+.)+(com|pl)$/', $_POST['email']))
		{
			$blad_email = 'Składnia e-maila nie jest poprawna';
		}

		else
		{
			$imie = $_POST['imie'];
			$nazwisko = $_POST['nazwisko'];
			$tresc = $_POST['tresc'];
			$temat = $_POST['temat'];

			require_once 'connect.php';

			try
			{
				$polaczenie = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
			}
			catch(PDOException $e)
			{
				echo "Nie można nazwiązać połączenia z bazą danych";
			}

			$zapytanie = $polaczenie->prepare("INSERT INTO wiadomosci VALUES('',:im,:na,:em,:te,:tr,:dt)");
			$zapytanie->bindValue(':im', $_POST['imie'], PDO::PARAM_STR);
			$zapytanie->bindValue(':na', $_POST['nazwisko'], PDO::PARAM_STR);
			$zapytanie->bindValue(':em', $_POST['email'], PDO::PARAM_STR);
			$zapytanie->bindValue(':te', $_POST['temat'], PDO::PARAM_STR);
			$zapytanie->bindValue(':tr', $_POST['tresc'], PDO::PARAM_STR);
			$zapytanie->bindValue(':dt', date('Y-m-d H:i:s'), PDO::PARAM_STR);
			$zapytanie->execute();

			$imie = $nazwisko = $tresc = $temat = '';
		}
	}
?>

<header class="filmy2 mt-2 mb-4 p-3">
	<h1>Kontakt</h1>
</header>

<main>
	<div class="col-6 formularz">
		<form action="kontakt.php" method="post">
			<div class="input-group">
			  <div class="input-group-prepend">
				<span class="input-group-text" style="width: 90px;">Imię</span>
			  </div>
			  <input type="text" class="form-control" name="imie" minlength="2" maxlength="30" <?php if(isset($imie) && $imie!='') echo 'value="'.$imie.'"';?> required>
			</div>
			
			<div class="input-group mt-4">
			  <div class="input-group-prepend">
				<span class="input-group-text" style="width: 90px;">Nazwisko</span>
			  </div>
			  <input type="text" class="form-control" name="nazwisko" minlength="3" maxlength="30" <?php if(isset($nazwisko) && $nazwisko!='') echo 'value="'.$nazwisko.'"';?> required>
			</div>
			
			<div class="input-group mt-4">
			  <div class="input-group-prepend">
				<span class="input-group-text" style="width: 90px;">E-mail</span>
			  </div>
			  <input type="email" class="form-control <?php if(isset($blad_email)) echo 'is-invalid'; ?>" name="email" <?php if(isset($blad_email) && $blad_email!='') echo 'placeholder="'.$blad_email.'" style="background-color: #ffcdd2;"'; unset($blad_email); ?> required>
			</div>
			
			<div class="input-group mt-4">
			  <div class="input-group-prepend">
				<span class="input-group-text" style="width: 90px;">Temat</span>
			  </div>
			  <select class="custom-select" name="temat">
				<option value="Pytanie" <?php if(isset($temat) && $temat==1) echo 'selected'; ?>>Pytanie</option>
				<option value="Organizacja wydarzeń" <?php if(isset($temat) && $temat==2) echo 'selected'; ?>>Organizacja wydarzeń</option>
				<option value="Zatrudnienie" <?php if(isset($temat) && $temat==3) echo 'selected'; ?>>Zatrudnienie</option>
				<option value="Współpraca" <?php if(isset($temat) && $temat==4) echo 'selected'; ?>>Współpraca</option>
				<option value="Inne" <?php if(isset($temat) && $temat==5) echo 'selected'; ?>>Inne</option>
			  </select>
			</div>

			<textarea class="form-control mt-4 mb-4" style="height: 150px;" placeholder="Treść wiadomości" name="tresc" value="21"required><?php if(isset($tresc) && $tresc!='') echo $tresc;?></textarea>
			
			<div class="text-center mb-5">
				<button type="submit" class="btn">Wyślij</button>
			</div>
		</form>
	</div>
</main>

<?php
    include "side_part/dol.php";
?>