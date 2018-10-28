<?php
    session_start();
    if(isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'])
    {
        header('Location: konto.php');
        exit();
    }
    $title = "Logowanie";
    include "side_part/gora.php";
?>

<header>
    <h1>Logowanie</h1>
    <section>
        Nie masz konta? <a href="rejestracja.php">Zarejestruj się</a>
    </section>
</header>

<form action="zaloguj.php" method="post" class="form">
    <div class="form-group">
        <label>Login</label><br>
        <input type="text" name="login" class="form-control" placeholder="Twój adres e-mail" />
    </div>
    
    <div class="form-group">
        <label>Haslo</label><br>
        <input type="password" name="haslo" class="form-control" placeholder="Twoje hasło" />
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Zaloguj</button>
        <button type="reset" class="btn btn-default">Wyczyść</button>
    </div>
</form>

<?php
    if(isset($_SESSION['blad']))
        echo '<span style="color: red">'.$_SESSION['blad'].'</span><br>';
    include "side_part/dol.php";
?>