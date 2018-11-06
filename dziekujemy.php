<?php
/*
    if(!isset($_SESSION['udana_rezerwacja_miejsca']))
    {
         header('Location: index.php');
         exit();
    }

    else
    {
        unset($_SESSION['udana_rezerwacja_miejsca']);
    }
*/
    if(session_status() == PHP_SESSION_NONE)
        session_start();

    $title = "Dziękujemy";
    include "side_part/gora.php";
    include "side_part/nav.php";
//==========================================================================================================
/*echo $_SESSION['blad_emaila'];
  if(isset($_SESSION['wyslanomaila']))
  {
    if($_SESSION['wyslanomaila'] == true)
    {
      echo 'Potwierdzenie o dokonaniu rezerwacji powędrowało na Twój adres e-mail';
      //unset($_SESSION['wyslanomaila']);
    }
    else
    {
      echo 'Przepraszamy, ale nie udało się wysłać potiwerdzenia na Twój adres e-mail';
      //unset($_SESSION['wyslanomaila']);
    }
  }*/
?>

<div class="container dane-konta3">
    <div class="row">
        <div class="col-12 text-center">Dziękujemy, <?php echo $_SESSION['imie']; ?>. Rezerwacja na film przebiegła poprawnie.</div>
        <div class="col-12 text-center">Pamiętaj, aby przyjść na około 30 minut przed jego rozpoczęciem.</div>
        <div class="col-12 text-center">Jeśli chcesz możesz przejść do <a href="kont
            ">swojego konta</a>, aby zobaczyć listę wszystkich Twoich rezerwacji.</div>
    </div>
</div>

<div class="container dane-konta2 mb-0">
    <div class="row">
        <div class="col-12 text-center"><span style="font-size: 45px;">Zobacz również inne nasze filmy</span></div>
    </div>
</div>

<?php
//==========================================================================================================
    include "side_part/filmy.php";
    include "side_part/footer.php";
    include "side_part/dol.php";
?>
