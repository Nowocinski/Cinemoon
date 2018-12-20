<?php
    $title = "Cinemoon";

    // Strona startowa
    include "side_part/gora.php";
    include "side_part/nav.php";
    include "side_part/naglowek.php";
	include "side_part/aktualnosci.php";
?>
<div class="container text-center mt-3">
    <div class="row">
        <div class="col-12"><span style="font-size: 45px; color: white;">Nasze filmy</span></div>
    </div>
</div>
<?php
    include "side_part/filmy.php";
    include "side_part/footer.php";
    include "side_part/dol.php";
    //--------------------------------------------------------------------------

?>