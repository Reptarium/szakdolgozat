<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once('header.php');
    require_once('menu.php');
?>

<div class="container">
    <img src="reptarium_logo.png" alt="Reptárium" class="img-fluid img-resize rounded mx-auto d-block">
    <h1 class="text-center">Keresés eredménye</h1>
    <hr/>
    <div class="justify-content-center">
        <table class="table table-hover table-borderless table-success table-striped">
            <tbody>
            <?php 
                showSearchResults();
            ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-12 text-center">
        <a class="btn btn-light mb-3 mt-3" href="javascript:history.back()"><i class="fad fa-arrow-alt-square-left"></i> VISSZA</a>
    </div>
</div>

<?php
    require_once('footer.php');
    mysqli_close($mysqli);
?>