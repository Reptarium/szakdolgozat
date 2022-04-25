<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once('header.php');
    require_once('menu.php');
?>

<div class="container">
    <img src="reptarium_logo.png" alt="Reptárium" class="img-fluid img-resize rounded mx-auto d-block">
    <hr/>
    <h2 class="text-center">Amennyiben nem tud dönteni két hüllő, esetleg két eleség között, abban az esetben szeretnénk figyelmébe ajánlani a Reptárium hirdetés összehasonlítóját!<br>Nincs más dolga, mint az aktív hirdetések közül két hirdetést kiválasztani, majd az "Összehasonlítom" gombra kattintva a csapatunk megteszi a tőlünk telhetőt! </h1>
    <div class="col-md-12 text-center">
        <a class="btn btn-light mb-3 mt-3" href="advertisements.php"><i class="fad fa-ad"></i> HIRDETÉSEK</a>
    </div>
</div>

<?php
    require_once('footer.php');
?>