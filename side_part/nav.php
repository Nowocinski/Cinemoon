<?php
    if (session_status() == PHP_SESSION_NONE)
        session_start();
?>
<header>
     <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
         <a class="navbar-brand" href="index.php"><img src="img/logo.png" width="142" height="30" alt="Cinemoon" class="d-inline-block align-bottom"></a>

         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expended="false" aria-label="Przełącznik nawigacji">
			<span class="navbar-toggler-icon"></span>
		 </button>

         <div class="collapse navbar-collapse" id="mainmenu">
             <ul class="navbar-nav">
                 <li class="nav-item active">
                     <a class="nav-link" href="repertuar.php">REPERTUAR</a>
                 </li>
                 <li class="nav-item active" href="bilety.php">
                     <a class="nav-link" href="cennik.php">CENNIK</a>
                 </li>
                 <li>
                    <a class="nav-link active" href="kontakt.php">KONTAKT</a>
                 </li>
                <li>
                    <a class="nav-link active" href="pracownicy.php">DLA PRACOWNIKÓW</a>
                 </li>
<?php
                    if(!isset($_SESSION['zalogowany']))
                    {
echo<<<END
                       </ul>
                       <form class="form-inline" action="zaloguj.php" method="post">
                            <input type="text" name="login" class="form-control mr-2" placeholder="Login">
                            <input type="password" name="haslo" class="form-control mr-2" placeholder="hasło">
                            <button type="submit" class="btn btn-primary mr-5">Zaloguj</button>
                       </form>
                       <form action="rejestracja.php">
                       <button type="submit" class="btn btn-danger mt-2">REJESTRACJA</button>
                       </form>
END;
                    }
                    else
                    {
                        echo '<li><a class="nav-link bg-danger active" href="konto.php">'.$_SESSION['imie'].' '.$_SESSION['nazwisko'].'</a></li></ul>';
                    }
?>
                 
         </div>
    </nav>
</header>