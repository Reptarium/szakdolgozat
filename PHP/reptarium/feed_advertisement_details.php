<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once('header.php');
    require_once('menu.php');
    require_once('functions.php');
?>

<div class="container justify-content-center">
    <table class="table table-hover table-borderless table-success table-striped">
        <tbody>
        <?php showFeedAdDetails(); ?>
        </tbody>
    </table>
</div>

<?php
    require_once('footer.php');
    mysqli_close($mysqli);
?>