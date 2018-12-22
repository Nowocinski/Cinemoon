<?php
	if (session_status() == PHP_SESSION_NONE)
		session_start();

	if(isset($_SESSION['typ_konta']) && $_SESSION['typ_konta']== 'pracownik')
	{
		header('Location: pracownik.php');
		exit();
	}
	
	elseif(isset($_SESSION['typ_konta']) && $_SESSION['typ_konta']== 'administratorIT')
    {
      header('Location: adminIT-info.php');
      exit();
    }
	
	elseif(isset($_SESSION['typ_konta']) && $_SESSION['typ_konta']== 'specjalistaDSObslugi')
    {
      header('Location: specjalista-ds-obslugi.php');
      exit();
    }
	
	elseif(isset($_SESSION['typ_konta']) && $_SESSION['typ_konta']== 'menadzerPracownikow')
    {
      header('Location: menadzer-pracownikow.php');
      exit();
    }
?>