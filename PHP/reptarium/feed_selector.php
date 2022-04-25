<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once('header.php');
    require_once('menu.php');
    require_once('functions.php');
?>

<div class="container">
    <img src="reptarium_logo.png" alt="Reptárium" class="img-fluid img-resize rounded mx-auto d-block">
    <hr/>
    <h1 class="text-center">Amennyiben nem tudja, hogy a hüllőjének milyen eleség való, használja a legördülő listás eleségválasztónkat! </h1>
    <form method="get" action="">
    <div class="panel panel-default">
      <div class="panel-body">
            <div class="form-group">
                <label class="h5" for="specie">Faj</label>
                <select id="feed_selector_select" name="specie" class="form-control" onchange="submit()">
                    <?php showSpecieOptions(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="h5" for="title">Életkor</label>
                <select id="feed_selector_select" name="age" class="form-control mb-3" onchange="submit()">
                <?php showAgeOptions(); ?>
                </select>
            </div>
      </div>
    </div>
    </form>
    <?php displayDropdownResults(); ?>
</div>

<?php
    require_once('footer.php');
    mysqli_close($mysqli);
?>