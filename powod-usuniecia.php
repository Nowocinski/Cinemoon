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
        <div class="col-12"><h5>W celu poprawy jakości świadczonych usług prosilibiśmy o wypełnienie poniższej ankiety</h5></div>

        <div class="col-12">
            <form class="form" action="index.php">




                <div class="col-12">
                Powodem mojego usunięcia konta było:
                <input list="browsers">

                <datalist id="browsers">
                  <option value="Nieintuicyjność działania konta">
                  <option value="Brak wspracia ze strony administracji">
                  <option value="Duża ilość błędów podczas użytkowania konta">
                  <option value="Ciężko powiedzieć">
                </datalist>.
                </div>
                <button type="submit" class="btn btn-primary">Wyślij</button>
                </form>
        </div>

        </div>
    </div>
</div>

<?php
    include "side_part/dol.php";
?>
