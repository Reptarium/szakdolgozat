<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once('header.php');
    require_once('menu.php');
    require_once('functions.php');
?>

<div class="container">
<img src="reptarium_logo.png" alt="Reptárium" class="img-fluid img-resize rounded mx-auto d-block">
    <h1 class="text-center">Üdvözli a Reptárium!</h1>
    <hr/>
    <div class="d-flex justify-content-center">
        <div class="text-center justify-content-center">
            <form action="" method="POST">
                <h2 class="text-center" id="home_php_text">Adja fel a hirdetését!</h2>
                <?php showMenuOptions(); ?>
            </form>
            <form method="GET" action="">
                <h2 class="text-center" id="home_php_text">Nem talál valamit?</h2>
                <input type="text" class="form-control form-control-lg text-center" name="search_entered" id="search_entered" placeholder="Segítünk!"/>
                <button type="submit" class="btn btn-light btn-lg mt-2 mb-4" name="search_button" id="search_button"><i class="fad fa-search"></i> Keresés</button>
            </form>
        </div>
    </div>
</div>

<?php
    require_once('footer.php');
    mysqli_close($mysqli);
?>