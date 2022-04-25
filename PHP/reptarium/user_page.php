<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once('db_connect.php');
    require_once('header.php');
    require_once('menu.php');
    require_once('functions.php');
?>

<div class="container">
    <ul class="nav nav-tabs justify-content-center gap-2 mt-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-user-edit"></i> Adataim</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="ads-tab" data-bs-toggle="tab" data-bs-target="#ads" type="button" role="tab" aria-controls="profile" aria-selected="false"><i class="fas fa-ad"></i> Hirdetéseim</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="changePassword-tab" data-bs-toggle="tab" data-bs-target="#changePassword" type="button" role="tab" aria-controls="contact" aria-selected="false"><i class="fas fa-key"></i> Jelszó módosítása</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="deactivateAccount-tab" data-bs-toggle="tab" data-bs-target="#deactivateAccount" type="button" role="tab" aria-controls="contact" aria-selected="false"><i class="fas fa-user-minus"></i> Felhasználó deaktiválása</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
            <hr/>
            <h1 class="text-center mt-3">Felhasználói adatok módosítása</h1> 
            <hr/>
            <div class="container-fluid d-flex justify-content-center">
                <form action="" method="POST">
                    <?php displayUserData(); ?>
                    <div class="col text-center">
                        <button type="submit" class="btn btn-warning btn-lg mb-3" name="edit"><i class="fad fa-edit"></i>MÓDOSÍT</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="tab-pane fade" id="ads" role="tabpanel" aria-labelledby="ads-tab">
            <table class="table table-hover table-borderless table-success table-striped mt-3">
                <thead>
                <th class="text-center" colspan="5">Eleség hirdetéseim</th>
                </thead>
                <tbody>
                    <form action="" method="POST">
                        <?php displayUserFeedAds(); ?>
                    </form>
                </tbody>
            </table>
            <table class="table table-hover table-borderless table-success table-striped">
                <thead>
                <th class="text-center" colspan="5">Hüllő hirdetéseim</th>
                </thead>
                <tbody>
                    <form action="" method="POST">
                        <?php displayUserReptileAds(); ?>
                    </form>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="changePassword" role="tabpanel" aria-labelledby="changePassword-tab">
            <hr/>
            <h1 class="text-center mt-3">Jelszó módosítása</h1> 
            <hr/>
            <div class="container-fluid d-flex justify-content-center">
                <form action="" method="POST">
                    <label class="h5" for="currentPassword">Jelenlegi jelszó</label><br>
                    <input class="form-control form-control-lg" type="password" name="currentPassword" id="currentPassword" placeholder="A jelenlegi jelszavad" required><br>
                    <label class="h5" for="newPassword1">Új jelszó</label><br>
                    <input class="form-control form-control-lg" type="password" name="newPassword1" id="newPassword1" placeholder="Az új jelszavad" required><br>
                    <label class="h5" for="newPassword2">Új jelszó megint</label><br>
                    <input class="form-control form-control-lg" type="password" name="newPassword2" id="newPassword2" placeholder="Az új jelszavad megint" required><br><br>
                    <div class="col text-center">
                        <button type="submit" class="btn btn-warning btn-lg mb-3" name="editPassword"><i class="fad fa-edit"></i>MÓDOSÍT</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="tab-pane fade" id="deactivateAccount" role="tabpanel" aria-labelledby="deactivateAccount-tab">
            <hr/>
            <h2 class="text-center mt-3">Amennyiben deaktiválod a felhasználódat, akkor azt csak a fejlesztők tudják visszaállítani!</h1> 
            <hr/>
            <form action="" method="POST">
                <div class="col text-center">
                    <a class="btn btn-light btn-lg mb-3" href="javascript:history.back()"><i class="fad fa-arrow-alt-square-left"></i>VISSZA</a>
                    <button type="submit" class="btn btn-warning btn-lg mb-3" name="deactivateAccount"><i class="fad fa-user-lock"></i>DEAKTIVÁLÁS</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
    require_once('footer.php');
    mysqli_close($mysqli);
?>