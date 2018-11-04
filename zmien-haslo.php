<?php
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    if(!isset($_SESSION['zalogowany']))
    {
        header('Location: logowanie.php');
        exit();
    }

    if(isset($_POST['nowe']))
    {
        $poprawna_walidacja = true;
        $nowe = $_POST['nowe'];
        $stare = $_POST['stare'];
        $id_klienta = $_SESSION['id_klienta'];
//----------------------------------------------------------------------------------------------------------------------------------
        //Walidacja nowego hasła
        if(strlen($_POST['nowe']) < 6 || strlen($_POST['nowe']) > 30)
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_haslo1'] = '<span style="color: red;">Hasło musi składać się od 6 do 30 znaków</span>';
        }
        
        if($_POST['nowe'] != $_POST['stare'] || $_POST['nowe'] == '' || $_POST['stare'] == '')
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_haslo2'] = '<span style="color: red;">Hasła nie pasują do siebie</span>';
        }
        
        $zahasowane_haslo = password_hash($_POST['nowe'], PASSWORD_DEFAULT);
//----------------------------------------------------------------------------------------------------------------------------------
        if(strlen($_POST['stare']) == 0)
        {
            $poprawna_walidacja = false;
            $_SESSION['blad_stare'] = '<span style="color: red;">To pole nie możę być puste</span>';
        }
//----------------------------------------------------------------------------------------------------------------------------------
        //Łączenie się z bazą
//----------------------------------------------------------------------------------------------------------------------------------
    $title = 'Zmiana hasła';
    include "side_part/gora.php";
?>

<div class="container dane-konta3">
        <form class="form" action="zmien-haslo.php" method="post">
            <span style="text-align: center;"><h3>Zmiana hasła</h3></span>
            
            <div class="form-group">
                <label>Podaj poprzednie hasło</label>
                <input type="password" class="form-control" placeholder="Poprzednie hasło" name="stare" />
                <?php
                    if(isset($_SESSION['blad_stare']))
                    {
                        echo $_SESSION['blad_stare'];
                        unset($_SESSION['blad_stare']);
                    }
                ?>
            </div>
            
            
            
            
            <div class="form-group">
                <label>Podaj nowę hasło</label>
                <input type="password" class="form-control" placeholder="Nowę hasło" name="nowe" />
                <?php
                    if(isset($_SESSION['blad_nowe']))
                    {
                        echo $_SESSION['blad_nowe'];
                        unset($_SESSION['blad_nowe']);
                    }
                ?>
            </div>
            
            
            
                <button type="submit" class="btn btn-primary">Zmień hasło</button>
                <button type="reset" class="btn btn-default">Wyczyść</button>
            
            
            
        </form>
</div>

<?php
    include 'side_part/dol.php'
?>