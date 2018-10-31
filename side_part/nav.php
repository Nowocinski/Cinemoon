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
                       <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggler="dropdown role="button">KONTO</a>
                            
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="logowanie.php">Logowanie</a>
                                <a class="dropdown-item" href="rejestracja.php">Rejestracja</a>
                            </div>
                            
                       </div>
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