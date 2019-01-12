<?php
    if(session_status() == PHP_SESSION_NONE)
        session_start();

    if(!isset($_SESSION['konto_zostalo_usuniete']))
    {
        header('Location: index.php');
        exit();
    }
    else
    {
      unset($_SESSION['konto_zostalo_usuniete']);
      session_unset();
    }

    $title = "Konto zostało usunięte";
    include "side_part/gora.php";
    include "side_part/nav.php";
?>

<div class="container text-center dane-konta3">
    <div class="row">
        <div class="col-12"><h3>Konto zostało z powodzeniem usunięte</h3></div>
    </div>
</div>

<?php
    include "side_part/dol.php";
?>
