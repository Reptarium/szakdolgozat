<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once('header.php');
    require_once('menu.php');
    require_once('functions.php');
?>

<div class="container">
    <div class="justify-content-center">
    <table class="table table-hover table-borderless table-success table-striped">
        <thead>
            <th class="text-center" colspan="2">Összehasonlított hirdetések</th>
        </thead>
        <tbody class="text-center">
        <?php showCompareFeedResults(); ?>
        </tbody>
    </table>
    </div>
</div>

<?php
    require_once('footer.php');
    mysqli_close($mysqli);
?>