<?php
    if (session_status() == PHP_SESSION_NONE)
        session_start();

        if(isset($_SESSION['zalogowany']))
        {
          $imie = $_SESSION['imie'];
          $nazwisko = $_SESSION['nazwisko'];
        }
?>

<header>
     <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
         <a class="navbar-brand" href="index.php"><img src="img/logo.png" width="142" height="30" alt="Cinemoon" class="d-inline-block align-bottom"></a>

     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expended="false" aria-label="Przełącznik nawigacji">
			<span class="navbar-toggler-icon"></span>
		 </button>

         <div class="collapse navbar-collapse" id="mainmenu">
             <ul class="navbar-nav mr-auto">
                 <li class="nav-item active btn btn-default">
                     <a class="nav-link" href="repertuar.php">REPERTUAR</a>
                 </li>
                 <li class="nav-item active btn btn-default" href="bilety.php">
                     <a class="nav-link" href="cennik.php">CENNIK</a>
                 </li>
                 <li class="nav-item active btn btn-default">
                    <a class="nav-link active" href="kontakt.php">KONTAKT</a>
                 </li>
                <li class="nav-item active btn btn-default">
                    <a class="nav-link active" href="pracownicy.php">DLA PRACOWNIKÓW</a>
                 </li>
             </ul>
<?php
                    if(!isset($_SESSION['zalogowany']))
                    {
echo<<<END



<ul class="navbar-nav">
                       <div class="nav-item dropdown">
            						<a class="nav-link dropdown-toggle btn btn-danger active" href="#" data-toggle="dropdown" role="button" aria-expanded="false" id="submenu" aria-haspopup="true"> Konto Cinemoon</a></li>

            						<div class="dropdown-menu" aria-labelledby="submenu">

            							<a class="dropdown-item text-center" href="logowanie.php"> Logowanie </a>

            							<div class="dropdown-divider"></div>

            							<a class="dropdown-item text-center" href="rejestracja.php"> Rejestracja </a>

            						</div>

            					</div>

</ul>

END;
                    }
                    else
                    {
echo<<<END
<ul class="navbar-nav">
                      <div class="nav-item dropdown">
                       <a class="nav-link dropdown-toggle btn btn-danger active" href="#" data-toggle="dropdown" role="button" aria-expanded="false" id="submenu" aria-haspopup="true"> $imie $nazwisko </a>

                       <div class="dropdown-menu" aria-labelledby="submenu">

                         <a class="dropdown-item text-center" href="konto.php"> Moje konto </a>

                         <div class="dropdown-divider"></div>

                         <a class="dropdown-item text-center" href="wyloguj.php"> Wyloguj się </a>

                       </div>

                      </div>
</ul>
END;
                    }
?>

         </div>
    </nav>
</header>
