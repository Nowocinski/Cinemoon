<?php
    session_start();
    if(!isset($_SESSION['zalogowany']))
    {
        header('Location: logowanie.php');
        exit();
    }

    $title = 'Ustawienia konta';

    include "side_part/gora.php";
    include "side_part/nav.php";

    if(isset($_SESSION['blad']))
    {
        echo $_SESSION['blad'];
		unset($_SESSION['blad']);
    }
?>

<!-- Zmiana imienia -->
<div id="zmienImie" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
		<h4 class="modal-title text-warning">Zmiana imienia</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="zmien-imie.php" method="post" id="zImie">
			<label>Podaj nowe imię</label>
			<input type="text" class="form-control" placeholder="Nowe imię" name="imie" style="color: black;" minlength="3" maxlength="20" required>
			<label><input type="checkbox" name="potwierdzenie" required> Tak, chcę zmienić imię</label><br>
		</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning" form="zImie">Zmień</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
      </div>
    </div>
  </div>
</div>

<!-- Zmiana nazwiska -->
<div id="zmienNazwisko" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
		<h4 class="modal-title text-warning">Zmiana nazwiska</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="zmien-nazwisko.php" method="post" id="zNazwisko">
			<label>Podaj nowe nazwisko</label>
			<input type="text" class="form-control" placeholder="Nowe nazwisko" name="nazwisko" style="color: black;" minlength="2" maxlength="30" required>
			<label><input type="checkbox" name="potwierdzenie" required> Tak, chcę zmienić nazwisko</label><br>
		</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning" form="zNazwisko">Zmień</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
      </div>
    </div>
  </div>
</div>

<!-- Zmiana numeru telefonu -->
<div id="zmienNrTelefonu" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
		<h4 class="modal-title text-warning">Zmiana numer telefonu</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="zmien-numer.php" method="post" id="zNrTel">
			<label>Podaj nowe numer telegonu</label>
			<input type="number" class="form-control" placeholder="Nowe numer telefonu" name="telefon" style="color: black;" min="100000000" max="999999999" required>
			<label><input type="checkbox" name="potwierdzenie" required> Tak, chcę zmienić numer telefonu</label><br>
		</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning" form="zNrTel">Zmień</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
      </div>
    </div>
  </div>
</div>

<!-- Zmiana adresu e-mail -->
<div id="zmienEmail" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
		<h4 class="modal-title text-warning">Zmiana adres e-mail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="zmien-email.php" method="post" id="zEmail">
			<label>Podaj nowe adres e-mail</label>
			<input type="email" class="form-control" placeholder="Nowe adres e-mail" name="email" style="color: black;" required>
			<label><input type="checkbox" name="potwierdzenie" required> Tak, chcę zmienić adres e-mail</label><br>
		</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning" form="zEmail">Zmień</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
      </div>
    </div>
  </div>
</div>

<!-- Zmiana hasła -->
<div id="zmienHaslo" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
		<h4 class="modal-title text-warning">Zmiane hasło</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="zmien-haslo.php" method="post" id="zHaslo">
			<label>Podaj nowe adres e-mail</label>
			<input type="password" class="form-control" placeholder="Nowe nowe hasło" name="haslo" style="color: black;" required>
			<label><input type="checkbox" name="potwierdzenie" required> Tak, chcę zmienić hasło</label><br>
		</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning" form="zHaslo">Zmień</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
      </div>
    </div>
  </div>
</div>

<!-- Usunięcie konta -->
<div id="usunKonto" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
		<h4 class="modal-title text-danger">Usunięcie konta</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="usun-konto.php" method="post" id="uK">
			<label>Usunięcie konta spowoduje usunięcie wszystkich zgoromadzonych przez Ciebie danych na tej stronie. Chcesz kontynuować?</label>
			<label><input type="checkbox" name="potwierdzenie" required> Tak, chcę usunąć konto</label><br>
		</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger" form="uK">Usuń</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
      </div>
    </div>
  </div>
</div>

<div class="container">
    <div class="row">
		<div class="dane-konta col-12 mt-3 text-center px-5">
			<article>
				<header>
					<b>USTAWIENIA KONTA</b>
				</header>
					<div class="table-responsive">
					  <table class="table">
						<tr>
							<th>Imię</th>
							<td><?php echo $_SESSION['imie']; ?></td>
							<td><button class="btn btn-warning" type="submit" data-toggle="modal" data-target="#zmienImie">Zmień</button></td>
						</tr>
						<tr>
							<th>Nazwisko</th>
							<td><?php echo $_SESSION['nazwisko']; ?></td>
							<td><button class="btn btn-warning" type="submit" data-toggle="modal" data-target="#zmienNazwisko">Zmień</button></td>
						</tr>
						<tr>
							<th>Adres e-mail</th>
							<td><?php echo $_SESSION['email']; ?></td>
							<td><button class="btn btn-warning" type="submit" data-toggle="modal" data-target="#zmienEmail">Zmień</button></td>
						</tr>
						<tr>
							<th>Hasło</th>
							<td></td>
							<td><button class="btn btn-warning" type="submit" data-toggle="modal" data-target="#zmienHaslo">Zmień</button></td>
						</tr>
						<tr>
							<th>Numer telefonu</th>
							<td><?php if($_SESSION['nr_telefonu'] != '') echo $_SESSION['nr_telefonu']; else echo '<span style="color: gray">(nie podano)</span><br>';?></td>
							<td><button class="btn btn-warning" type="submit" data-toggle="modal" data-target="#zmienNrTelefonu">Zmień</button></td>
						</tr>
					  </table>
					</div>
			</article>
		</div>
    </div>
</div>

<div class="container">
    <div class="row">
		<div class="dane-konta col-12 mt-3 text-center px-5">
			<article>
				<header>
					<b>USUWNIĘCIE KONTA</b>
				</header>
					<div class="table-responsive">
					  <table class="table">
						<tr>
							<th>Ta opcja spowoduje usunięcie konta</th>
							<td><button class="btn btn-danger" type="submit" data-toggle="modal" data-target="#usunKonto">Usuń</button></td>
						</tr>
					  </table>
					</div>
			</article>
		</div>
    </div>
</div>

<?php
    include 'side_part/dol.php'
?>
