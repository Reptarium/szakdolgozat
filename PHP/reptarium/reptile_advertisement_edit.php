<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once('header.php');
    require_once('menu.php');
    require_once('functions.php');
?>

<div class="container">
    <h1 class="text-center">Hirdetésem módosítása</h1> 
    <hr/>
    <div class="container-fluid d-flex justify-content-center">
        <form action="" method="POST" enctype="multipart/form-data">
            <?php editReptileAd(); ?>
            <div class="col text-center mt-3 mb-3">
                <a class="btn btn-light btn-lg" href="javascript:history.back()"><i class="fad fa-arrow-alt-square-left"></i>VISSZA</a>
                <input type="submit" class="btn btn-light btn-lg" value="MÓDOSÍTÁS" name="reptileAd_modify">
            </div>
        </form>    
    </div>
</div>

<?php
    require_once('footer.php');
    mysqli_close($mysqli);
?>