<?php
    $title = "Rejestracja";
    //----------------------------------------------
    include "side_part/gora.php";
    include "side_part/nav.php";

    
?>
<div class="dane-konta2">
    <header>
        <h1>REJESTRACJA</h1>
        <section>
            Masz konto? <a href="logowanie.php">Zaloguj się</a>
        </section>
    </header>

    <form class="form" action="rejestracja.php" method="post">
        <div class="form-group">
            <label>Imię</label>
            <input type="text" class="form-control" id="nameField" placeholder="Twoje imię" name="imie" />
        </div>

        <div class="form-group">
            <label for="emailField">Nazwisko</label>
            <input type="text" class="form-control" id="emailField" placeholder="Twój nazwisko" name="nazwisko"/>
        </div>

        <div class="form-group">
            <label for="emailField">E-mail</label>
            <input type="text" class="form-control" id="emailField" placeholder="Twój adres e-mail" name="email"/>
        </div>

        <div class="form-group">
            <label for="phoneField">Haslo</label>
            <input type="password" class="form-control" id="phoneField" placeholder="Twoje hasło" name="haslo"/>
        </div>

        <div class="form-group">
            <label for="phoneField">Powtórz haslo</label>
            <input type="password" class="form-control" id="phoneField" placeholder="Twoje hasło" name="haslo"/>
        </div>

        <div class="form-group">
            <label for="phoneField">Telefon (opcjonalnie)</label>
            <input type="password" class="form-control" id="phoneField" placeholder="Twój numer telefonu" name="haslo"/>
        </div>

        <div class="form-group">
            <label for="phoneField">Typ konta</label>
            <select name="nazwa">
                <option>Normalne</option>
                <option>Studenckie</option>
            </select>
        </div>
        
        <div class="form-group">
            <label><input type="checkbox" value=""> Przeczytałem i akceptuję regulamin</label>
        </div>

        <button type="submit" class="btn btn-primary">Wyślij</button> <button type="reset" class="btn btn-default">Wyczyść</button>
    </form>
</div>

<?php
    include "side_part/dol.php";
?>