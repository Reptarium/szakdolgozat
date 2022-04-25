<?php 
    header('Content-Type: text/html; charset=utf-8');
    require_once('header.php');
    require_once('functions.php');
?>

<div class="container">
        <img src="reptarium_logo.png" alt="Reptárium" class="img-fluid img-resize rounded mx-auto d-block">
        <h1 class="text-center">Regisztráció</h1>
        <hr/>
        <div class="d-flex justify-content-center"> 
            <form action="" method="POST">
                <div class="form-input">
                    <label class="h5" for="name"><i class="fad fa-hand-point-right"></i>Teljes név</label>
                    <input type="text" class="form-control form-control-lg" name="name" placeholder="Add meg a nevedet" required>
                </div>
                <div class="form-input">
                    <label class="h5" for="username"><i class="fad fa-hand-point-right"></i>Felhasználónév</label>
                    <input type="text" class="form-control form-control-lg" name="username" placeholder="Add meg a felhasználónevedet" required>
                </div>
                <div class="form-input">
                    <label class="h5" for="email"><i class="fad fa-hand-point-right"></i>Email cím</label>
                    <input type="email" class="form-control form-control-lg" name="email" placeholder="Add meg az email címedet" required>
                </div>
                <div class="form-input">
                    <label class="h5" for="mobile"><i class="fad fa-hand-point-right"></i>Telefonszám</label>
                    <input type="tel" class="form-control form-control-lg" name="mobile" pattern="^[0-9]{3,45}$" placeholder="Add meg a telefonszámodat (formátum: 06301111111)" required>
                </div>
                <div class="form-input">
                    <label class="h5" for="postal_code"><i class="fad fa-hand-point-right"></i>Irányítószám</label>
                    <input type="number" class="form-control form-control-lg" name="postal_code" placeholder="Add meg az irányítószámodat" required>
                </div>
                <div class="form-input">
                    <label class="h5" for="address"><i class="fad fa-hand-point-right"></i>Lakcím</label>
                    <input type="text" class="form-control form-control-lg" name="address" placeholder="Add meg a lakcímedet! (Város - Utca - Házszám)" required>
                </div>
                <div class="form-input">
                    <label class="h5" for="password"><i class="fad fa-hand-point-right"></i>Jelszó</label>
                    <input type="password" class="form-control form-control-lg" name="password" placeholder="Add meg a jelszavadat" required></br>
                </div>
                <div class="col text-center">
                    <input type="submit" class="btn btn-light btn-lg" value="REGISZTRÁCIÓ" name="register">
                </div>
                <h2 class="text-center mt-2 mb-5">Már regisztráltál? Jelentkezz be <a href="index.php" class="link-warning">itt</a>!</h2>
            </form>
        </div>
    </div>

<?php
    require_once('footer.php');
    mysqli_close($mysqli);
?>