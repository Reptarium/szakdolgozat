<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once('header.php');
    require_once('functions.php');
    
?>
 
    <div class="container-index">
        <img src="reptarium_logo.png" alt="Reptárium" class="img-fluid img-resize rounded mx-auto d-block">
        <h1 class="text-center">Bejelentkezés</h1>
        <hr/>
        <div class="d-flex justify-content-center"> 
            <form action="" method="POST">
                <div class="form-input">
                    <label class="h5" for="username"><i class="fad fa-user"></i>Felhasználónév</label>
                    <input type="text" class="form-control form-control-lg" name="username" placeholder="Add meg a felhasználónevedet">
                </div>
                <div class="form-input">
                    <label class="h5" for="password"><i class="fad fa-key"></i>Jelszó</label>
                    <input type="password" class="form-control form-control-lg" name="password" placeholder="Add meg a jelszavadat"></br>
                </div>
                <div class="col text-center">
                    <input type="submit" class="btn btn-light btn-lg" value="BEJELENTKEZÉS" name="login">
                    <input type="submit" class="btn btn-light btn-lg" value="FOLYTATÁS VENDÉGKÉNT" name="guest">
                </div>
                <h2 class="text-center mt-2 mb-5">Még nincs felhasználód? Regisztrálj <a href="register.php" class="link-warning">itt</a>!</h2>
            </form>
        </div>
    </div>

<?php
    require_once('footer.php');
    mysqli_close($mysqli);
?>