<?php

	$title = "Kontakt";

    include "side_part/gora.php";
    include "side_part/nav.php";
?>

<header class="filmy2 mt-5 mb-4">
	<h1>Kontakt</h1>
</header>

<main>
	<div class="col-6 formularz">
		<form action="#" method="post">
			<div class="input-group">
			  <div class="input-group-prepend">
				<span class="input-group-text" style="width: 90px;">Imię</span>
			  </div>
			  <input type="text" class="form-control" name="imie" required>
			</div>
			
			<div class="input-group mt-4">
			  <div class="input-group-prepend">
				<span class="input-group-text" style="width: 90px;">Nazwisko</span>
			  </div>
			  <input type="text" class="form-control" name="nazwisko" required>
			</div>
			
			<div class="input-group mt-4">
			  <div class="input-group-prepend">
				<span class="input-group-text" style="width: 90px;">E-mail</span>
			  </div>
			  <input type="email" class="form-control" name="email" required>
			</div>
			
			<div class="input-group mt-4">
			  <div class="input-group-prepend">
				<span class="input-group-text" style="width: 90px;">Temat</span>
			  </div>
			  <select class="custom-select" name="temat">
				<option value="1">Organizacja wydarzeń</option>
				<option value="2">Zatrudnienie</option>
				<option value="3">Współpraca partnerska</option>
				<option value="4">Inne</option>
			  </select>
			</div>

			<textarea class="form-control mt-4 mb-4" style="height: 150px;" placeholder="Treść wiadomości" name="tresc"required></textarea>
			
			<div class="text-center mb-3">
				<button type="submit" class="btn">Wyślij</button>
			</div>
		</form>
	</div>
</main>

<?php
	include "side_part/footer.php";
    include "side_part/dol.php";
?>