<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once('header.php');
    require_once('menu.php');
    require_once('functions.php');
?>

<div class="container">
    <img src="reptarium_logo.png" alt="Reptárium" class="img-fluid img-resize rounded mx-auto d-block">
    <h1 class="text-center">Hirdetésfeladás</h1>
    <hr/>
    <div class="justify-content-center">
        <form action="" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Válassza ki, hogy milyen hirdetést adna fel!</legend>
                <input type="radio" name="adtype" id="feed" value="feed" onclick="toggleOptionsOnNewAdPHP();">
                <label class="form-check-label h5" for="feed">Eleséget szeretnék hirdetni</label><br>
                <input type="radio" name="adtype" id="reptile" value="reptile" onclick="toggleOptionsOnNewAdPHP();">
                <label class="form-check-label h5" for="reptile">Hüllőt szeretnénk hirdetni</label><br>
            </fieldset>
            <div class="d-flex justify-content-center"> 
                <div id="feedAdForm" style="display:none;">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-input mt-3">
                            <label class="h5" for="feedAd_name"><i class="fad fa-hand-point-right"></i>Hirdetés címe</label>
                            <input type="text" class="form-control form-control-lg" name="feedAd_name" maxlength="50" placeholder="Hirdetés címe" required>
                        </div>
                        <div class="form-input">
                            <label class="form-label h5" for="feedAd_description" required><i class="fad fa-hand-point-right"></i>Leírás</label>
                            <textarea class="form-control form-control-lg" name="feedAd_description" id="feedAd_description" rows="4" placeholder="Hirdetés leírása"></textarea>
                        </div>
                        <div class="form-input">
                            <label class="h5" for="feedAd_type"><i class="fad fa-hand-point-right"></i>Eleség fajtája</label>
                            <select class="form-control form-control-lg" name="feedAd_type" id="feedAd_type" required><br>
                                <?php
                                    $result = $mysqli->query("SELECT * FROM eleseg");
                                    if($result){
                                        while($row = mysqli_fetch_assoc($result)){
                                        $feeds[] = $row;
                                        }
                                    }
                                    echo '<option selected disabled>Válasszon</option>';
                                    foreach ($feeds as $feed) {
                                        echo "<option value=". $feed['id'].">".$feed['eleseg_nev'] ."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-input">
                            <label class="h5" for="feedAd_amount"><i class="fad fa-hand-point-right"></i>Mennyiség (darab)</label>
                            <input type="number" class="form-control form-control-lg" name="feedAd_amount" placeholder="Az eleség darabszáma" required>
                        </div>
                        <div class="form-input">
                            <label class="h5" for="feedAd_price"><i class="fad fa-hand-point-right"></i>Egységár</label>
                            <input type="number" class="form-control form-control-lg" name="feedAd_price" placeholder="Az eleség ára" required>
                        </div>
                        <div class="form-input">
                            <label class="h5" for="feedAd_picture"><i class="fad fa-hand-point-right"></i>Kép</label>
                            <input type="file" class="form-control form-control-lg" name="feedAd_picture" accept="image/png, image/jpeg, image/jpg" required>
                        </div>
                        <div class="col text-center mt-3">
                            <input type="submit" class="btn btn-light btn-lg mb-3" value="HIRDETÉS FELADÁSA" name="feedAd_submit">
                        </div>
                    </form>
                </div>
                <div id="reptileAdForm" style="display:none;">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-input mt-3">
                            <label class="h5" for="reptileAd_name"><i class="fad fa-hand-point-right"></i>Hirdetés címe</label>
                            <input type="text" class="form-control form-control-lg" name="reptileAd_name" maxlength="50" placeholder="Hirdetés címe" required>
                        </div>
                        <div class="form-input">
                            <label class="form-label h5" for="reptileAd_description" required><i class="fad fa-hand-point-right"></i>Leírás</label>
                            <textarea class="form-control form-control-lg" name="reptileAd_description" id="reptileAd_description" rows="4" placeholder="Hirdetés leírása"></textarea>
                        </div>
                        <div class="form-input">
                            <label class="h5" for="reptileAd_type"><i class="fad fa-hand-point-right"></i>Hüllő fajtája</label>
                            <select class="form-control form-control-lg" name="reptileAd_type" id="reptileAd_type" required><br>
                                <?php
                                    $result = $mysqli->query("SELECT * FROM faj");
                                    if($result){
                                        while($row = mysqli_fetch_assoc($result)){
                                        $reptiles[] = $row;
                                        }
                                    }
                                    echo '<option selected disabled>Válasszon</option>';
                                    foreach ($reptiles as $reptile) {
                                        echo "<option value=". $reptile['id'].">".$reptile['faj'] ."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-input">
                            <label class="h5" for="reptileAd_age"><i class="fad fa-hand-point-right"></i>Hüllő életkora</label>
                            <input type="number" step="0.1" class="form-control form-control-lg" name="reptileAd_age" placeholder="A hüllő életkora" required>
                        </div>
                        <div class="form-input">
                            <label class="h5" for="reptileAd_gender"><i class="fad fa-hand-point-right"></i>Hüllő neme</label>
                            <select class="form-control form-control-lg" name="reptileAd_gender" id="reptileAd_gender" required>
                                <option selected disabled>Válasszon</option>
                                <option value="0">Még nem megállapítható</option>
                                <option value="1">Nőstény</option>
                                <option value="2">Hím</option>
                            </select>
                        </div>
                        <div class="form-input">
                            <label class="h5" for="reptileAd_amount"><i class="fad fa-hand-point-right"></i>Darabszám</label>
                            <input type="number" class="form-control form-control-lg" name="reptileAd_amount" placeholder="A hüllő mennyisége" required>
                        </div>
                        <div class="form-input">
                            <label class="h5" for="reptileAd_price"><i class="fad fa-hand-point-right"></i>Egységár</label>
                            <input type="number" class="form-control form-control-lg" name="reptileAd_price" placeholder="A hüllő ára" required>
                        </div>
                        <div class="form-input">
                            <label class="h5" for="reptiledAd_picture"><i class="fad fa-hand-point-right"></i>Kép</label>
                            <input type="file" class="form-control form-control-lg" name="reptileAd_picture" accept="image/png, image/jpeg, image/jpg" required>
                        </div>
                        <div class="col text-center mt-3">
                            <input type="submit" class="btn btn-light btn-lg mb-3" value="HIRDETÉS FELADÁSA" name="reptileAd_submit">
                        </div>
                    </form>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
    require_once('footer.php');
    mysqli_close($mysqli);
?>