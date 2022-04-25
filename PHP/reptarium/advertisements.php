<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once('header.php');
    require_once('menu.php');
    require_once('functions.php');
?>

<div class="container">
    <img src="reptarium_logo.png" alt="Reptárium" class="img-fluid img-resize rounded mx-auto d-block">
    <h1 class="text-center">Hirdetések</h1>
    <hr/>
    <div class="justify-content-center">
        <fieldset>
            <legend>Válassza ki, hogy milyen hirdetéstípust szeretne látni!</legend>
            <input type="radio" class="form-check-input" name="adtype" id="feedAdList" value="feedAdList" onclick="window.location='advertisements.php?page=1&checked=1'; toggleOptionsOnAdvertisementsPHP();">
            <label class="form-check-label h5" for="feedAdList">Eleségek</label><br>
            <input type="radio" class="form-check-input" name="adtype" id="reptileAdList" value="reptileAdList" onclick="window.location='advertisements.php?page=1&checked=2'; toggleOptionsOnAdvertisementsPHP();">
            <label class="form-check-label h5" for="reptileAdList">Hüllők</label><br>
        </fieldset>
        <div id="allFeedAds" style="display:none;">
            <form id="compare" method="get" action="compare_feed.php" enctype="application/x-www-form-urlencoded" onsubmit="return validateMyForm();">
                <table class="table table-hover table-borderless table-success table-striped" id="feed_load_data_table">
                    <?php displayFeedAds(); ?>
                </table>
            </form>
        </div>
        <div id="allReptileAds" style="display:none;">
            <form id="compare" method="get" action="compare_reptile.php" enctype="application/x-www-form-urlencoded" onsubmit="return validateMyForm();">
                <table class="table table-hover table-borderless table-success table-striped" id="reptile_load_data_table">
                    <?php displayReptileAds(); ?>
                </table>
            </form>
        </div>
    </form>
</div>

<?php
    require_once('footer.php');
    mysqli_close($mysqli);
?>