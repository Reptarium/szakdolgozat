<?php
require_once('functions.php');
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="home.php">
            <img src="reptarium_logo.png" alt="" width="30" height="30" class="d-inline-block align-text-top">
            Főoldal
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link" href="advertisements.php">Hirdetések</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="compare.php">Összehasonlítás</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="feed_selector.php">Eleségválasztó</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="index.php"><?php loginOrLogoutAtNavbar(); ?></a>
            </li>
        </ul>
        <span class="navbar-text"> <?php showUsernameAtNavbar(); ?> </span>
        </div>
    </div>
</nav>