<?php
    session_start();
    require_once('db_connect.php');

#region INDEX.PHP
    if(array_key_exists('login', $_POST)) {
        validateAtLogin();
    }
    else if(array_key_exists('guest', $_POST)) {
        validateAtGuest();
    }
    function validateAtLogin() {
        global $mysqli;
        if(isset($_POST['login'])){
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $_SESSION['username'] = $username;
            if ($username=='' && $password==''){
                echo "<script type='text/javascript'>alert('Add meg a felhasználónevedet és a jelszavadat!');</script>";
            }
            else if($username==''){
                echo "<script type='text/javascript'>alert('Add meg a felhasználónevedet is!');</script>";
            }
            else if($password==''){
                echo "<script type='text/javascript'>alert('Add meg a jelszavadat is!');</script>";
            }
            else
            {
                $error = true;
                $isDeactivated = 1;
                $sqlUser = "SELECT * FROM felhasznalo";
                $resultUser = $mysqli->query($sqlUser);
                if ($resultUser) {
                    while ($row = mysqli_fetch_assoc($resultUser)) {
                        $data[] = $row;
                    }
                }
                foreach ($data as $currentUser) {
                    if ($currentUser['felhasznalo_nev'] == $username && $currentUser['torolt_e'] == $isDeactivated && password_verify($_POST['password'], $currentUser['jelszo'])) {
                        $error = false;
                        echo "<script type='text/javascript'>alert('Dekativálták a felhasználódat! Keresd fel a fejlesztőket!');</script>";
                    }
                    elseif ($currentUser['felhasznalo_nev'] == $username && $currentUser['torolt_e'] != $isDeactivated && password_verify($_POST['password'], $currentUser['jelszo'])) {
                        $error = false;
                        header("location:home.php");
                    }
                }
    
                if ($error) {
                    echo "<script type='text/javascript'>alert('Hibás felhasználónév vagy jelszó!');</script>";
                    $error = false;
                }
            }
        }
    }
    function validateAtGuest() {
        global $mysqli;
        if(isset($_POST['guest'])){
            $username = 'Vendég';
            $password = '';
            $_SESSION['username'] = $username;
            $sqlGuest = "SELECT felhasznalo_nev, jelszo FROM felhasznalo WHERE felhasznalo_nev= '$username' AND jelszo= '$password' LIMIT 1";
            $resultGuest = $mysqli->query($sqlGuest);
            
            if(mysqli_num_rows($resultGuest) == 1){
                header("location:home.php");
            }
    
        }
    }
#endregion

#region REGISTER.PHP
    if(array_key_exists('register', $_POST)) {
        registerNewUser();
    }
    function registerNewUser() {
        global $mysqli;
        if(isset($_POST['register'])){
            $username = $_POST['username'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $mobile = $_POST['mobile'];
            $postal_code = $_POST['postal_code'];
            $address = $_POST['address'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $is_deleted = 0;
    
            $sqlCheck = "SELECT * FROM felhasznalo WHERE felhasznalo_nev = '$username' OR email = '$email'";
            $resultCheck = $mysqli->query($sqlCheck);
    
            if (mysqli_num_rows($resultCheck) == 1) {
                echo "<script type ='text/javascript'>alert('A felhasználónév vagy email cím használatban van!');</script>";
            }
            else {
                $sqlRegister = "INSERT INTO felhasznalo (felhasznalo_nev, nev, email, telszam, jelszo, iranyitoszam, cim, torolt_e) VALUES ('$username', '$name', '$email', '$mobile', '$password', '$postal_code', '$address', '$is_deleted');";
                $resultRegister = $mysqli->query($sqlRegister);
    
                if ($resultRegister) {
                    header("location:index.php");
                }
            }
        }
    }
#endregion

#region MENU.PHP
    function showUsernameAtNavbar() {
        global $mysqli;
        $sqlNavbar = "SELECT id, felhasznalo_nev FROM felhasznalo";
        $resultNavbar = $mysqli->query($sqlNavbar);
        $users = array();
        if($resultNavbar){
            while ($row = mysqli_fetch_assoc($resultNavbar)){
                $users[] = $row;
            }
        } else {
            die("Nem sikerült a lekérdezés..." .$mysqli->error);
        }

        foreach ($users as $user) {
            if ($_SESSION['username'] == $user['felhasznalo_nev'] && $user['id'] == 1) echo 'Üdvözlünk az oldalunkon, <strong>'. $_SESSION['username'] .'</strong>!';
            else if ($_SESSION['username'] == $user['felhasznalo_nev'] && $user['id'] != 1) echo 'Üdvözlünk az oldalunkon, <a style="text-decoration: none" href="user_page.php?id='. $user['id'] .'"><strong>'. $_SESSION['username'] .'</strong>!</a>';
        }
    }
	
	function loginOrLogoutAtNavbar() {
        global $mysqli;
        $sqlNavbar = "SELECT id, felhasznalo_nev FROM felhasznalo";
        $resultNavbar = $mysqli->query($sqlNavbar);
        $users = array();
        if($resultNavbar){
            while ($row = mysqli_fetch_assoc($resultNavbar)){
                $users[] = $row;
            }
        } else {
            die("Nem sikerült a lekérdezés..." .$mysqli->error);
        }

        foreach ($users as $user) {
            if ($_SESSION['username'] == $user['felhasznalo_nev'] && $user['id'] == 1) echo 'Bejelentkezés';
            else if ($_SESSION['username'] == $user['felhasznalo_nev'] && $user['id'] != 1) echo 'Kijelentkezés';
        }
    }
#endregion

#region HOME.PHP
    function showMenuOptions() {
        global $mysqli;
        $sqlHome = "SELECT id, felhasznalo_nev FROM felhasznalo";
        $resultHome = $mysqli->query($sqlHome);
        $users = array();
        if($resultHome){
            while ($row = mysqli_fetch_assoc($resultHome)){
                $users[] = $row;
            }
        } else {
            die("Nem sikerült a lekérdezés..." .$mysqli->error);
        }

        foreach ($users as $user) {
            if ($_SESSION['username'] == $user['felhasznalo_nev'] && $user['id'] != 1) {
                echo '<a id="new_ad_button" class="btn btn-success btn-lg" href="newad.php?id='. $user['id'] .'" name="new_ad"><i class="fad fa-upload"></i> Hirdetésfeladás</a><br>';
            }
            else if ($_SESSION['username'] == $user['felhasznalo_nev'] && $user['id'] == 1) {
                echo '<a id="register_button" class="btn btn-warning btn-lg" href="register.php" name="new_ad"><i class="fad fa-user-plus"></i> Csak regisztráció után!</a><br>';
            }
        } 
    }

    if(array_key_exists('search_button', $_GET)) {
        redirectWithSearchTerm();
    }
    function redirectWithSearchTerm() {
        if (!empty($_REQUEST['search_entered'])) {
            $term = $_REQUEST['search_entered'];
            header("location:search.php?term=$term");
        }
    }
#endregion

#region SEARCH.PHP
    function showSearchResults() {
        global $mysqli;
        if (isset($_GET['term'])) {
            $term = $_GET['term'];
        }
        $sqlSearch = "SELECT hh.id, hh.statusz, 0 AS hullo_vagy_eleseg, hh.kep, hh.leiras, hh.hirdetes_nev, hh.ar, hh.darabszam, hh.publikacio_ideje_TS FROM hirdetes_hullo hh WHERE (hh.statusz = 1) AND (hh.hirdetes_nev LIKE '%".$term."%' OR hh.leiras LIKE '%".$term."%' OR hh.ar LIKE '%".$term."%' OR hh.darabszam LIKE '%".$term."%' OR hh.publikacio_ideje_TS LIKE '%".$term."%') UNION SELECT he.id, he.statusz, 1 AS hullo_vagy_eleseg, he.kep, he.leiras, he.hirdetes_nev, he.ar, he.darabszam, he.publikacio_ideje_TS FROM hirdetes_eleseg he WHERE (he.statusz = 1) AND (he.hirdetes_nev LIKE '%".$term."%' OR he.leiras LIKE '%".$term."%' OR he.ar LIKE '%".$term."%' OR he.darabszam LIKE '%".$term."%' OR he.publikacio_ideje_TS LIKE '%".$term."%');";
        $allSearchedAd = array();
        $resultSearch = $mysqli->query($sqlSearch);
        if($resultSearch){
            while ($row = mysqli_fetch_assoc($resultSearch)){
                $allSearchedAd[] = $row;
            }
        } else {
            die("Nem sikerült a lekérdezés..." .$mysqli->error);
        }

        foreach ($allSearchedAd as $searchAd) {
            echo "<tr>";
                echo "<td class='col-md-5'><img src=". $searchAd['kep'] ." width=30% height=30%/></td>";
                echo "<td>". $searchAd['hirdetes_nev'] ."</td>";
                echo "<td>". $searchAd['ar'] ." Ft/db</td>";
                echo "<td>";
                    echo '<div class="d-flex gap-2 mx-auto">';
                    if ($searchAd['hullo_vagy_eleseg'] == 0) $forDetails = "reptile"; elseif ($searchAd['hullo_vagy_eleseg'] == 1) $forDetails = "feed";
                    echo '<a class="btn btn-light" href="'. $forDetails .'_advertisement_details.php?id='. $searchAd['id'] .'"><i class="fad fa-arrow-circle-right"></i> Részletek</a>';
                    echo '</div>';
                echo "</td>";    
            echo "</tr>";
        } 
    }
#endregion

#region NEWAD.PHP
    if(array_key_exists('feedAd_submit', $_POST)) {
        submitFeedAd();
    }
    else if(array_key_exists('reptileAd_submit', $_POST)) {
        submitReptileAd();
    }
    function submitFeedAd() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }

        if (isset($_POST['feedAd_submit'])){
            $feed_id = $_POST['feedAd_type'];
            $user_id = $id;
            $description = $_POST['feedAd_description'];
            $ad_name = $_POST['feedAd_name'];
            $price = $_POST['feedAd_price'];
            $amount = $_POST['feedAd_amount'];
            $status = 1;
            $ad_created = date('Y-m-d H:i:s');
            $ad_expirity = date('Y-m-d H:i:s', strtotime('+30 days', strtotime($ad_created)));
            $image_name = $_FILES['feedAd_picture']['name'];
            $target_dir = "images/feedImages";
            $target_file = $target_dir . basename($_FILES["feedAd_picture"]["name"]);
    
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
            $extensions_arr = array("jpg", "jpeg", "png");
    
            if (in_array($imageFileType, $extensions_arr)) {
                if (move_uploaded_file($_FILES['feedAd_picture']['tmp_name'], 'images/feedImages/'.$image_name)) {
                    $image_base64 = base64_encode(file_get_contents('images/feedImages/'.$image_name));
                    $image = 'data:image/'.$imageFileType.';base64,'.$image_base64;
                    $sql = "INSERT INTO `hirdetes_eleseg` (eleseg_id, felhasznalo_id, leiras, kep_neve, kep, hirdetes_nev, ar, darabszam, statusz, publikacio_ideje_TS, lejarati_datum) VALUES ('$feed_id', '$user_id', '$description', '$image_name', '$image', '$ad_name', '$price', '$amount', '$status', '$ad_created', '$ad_expirity');";
                
                    $result = $mysqli->query($sql);
                    if ($result){
                        $url = 'home.php';
                        header("Location: ".$url);
                    } else {
                        echo $mysqli->error;
                    }
    
                }
            } else {
				echo "<script type ='text/javascript'>alert('A feltölteni kívánt fájl kiterjesztése nem megfelelő!');</script>"; 
                echo $mysqli->error;
			}
        }
    }
    function submitReptileAd() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }

        if (isset($_POST['reptileAd_submit'])) {
            $reptile_id = $_POST['reptileAd_type'];
            $specie_id = $reptile_id;
            $user_id = $id;
            $description = $_POST['reptileAd_description'];
            $ad_name = $_POST['reptileAd_name'];
            $price = $_POST['reptileAd_price'];
            $amount = $_POST['reptileAd_amount'];
            $gender = $_POST['reptileAd_gender'];
            $age = $_POST['reptileAd_age'];
            $status = 1;
            $ad_created = date('Y-m-d H:i:s');
            $ad_expirity = date('Y-m-d H:i:s', strtotime('+30 days', strtotime($ad_created)));
            $image_name = $_FILES['reptileAd_picture']['name'];
            $target_dir = "images/reptileImages";
            $target_file = $target_dir . basename($_FILES["reptileAd_picture"]["name"]);
    
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
            $extensions_arr = array("jpg", "jpeg", "png");
    
            if (in_array($imageFileType, $extensions_arr)) {
                if (move_uploaded_file($_FILES['reptileAd_picture']['tmp_name'], 'images/reptileImages/'.$image_name)) {
                    $image_base64 = base64_encode(file_get_contents('images/reptileImages/'.$image_name));
                    $image = 'data:image/'.$imageFileType.';base64,'.$image_base64;
                    $sql = "INSERT INTO `hirdetes_hullo` (hullo_id, faj_id, felhasznalo_id, leiras, kep_neve, kep, hirdetes_nev, ar, darabszam, nem, kor, statusz, publikacio_ideje_TS, lejarati_datum) VALUES ('$reptile_id', '$specie_id', '$user_id', '$description', '$image_name', '$image', '$ad_name', '$price', '$amount', '$gender', '$age', '$status', '$ad_created', '$ad_expirity');";
                
                    $result = $mysqli->query($sql);
                    if ($result){
                        $url = 'home.php';
                        header("Location: ".$url);
                    } else {
                        echo $mysqli->error;
                    }
    
                }
            }
        } else {
			echo "<script type ='text/javascript'>alert('A feltölteni kívánt fájl kiterjesztése nem megfelelő!');</script>"; 
            echo $mysqli->error;
		}
    }
#endregion

#region FEED_SELECTOR.PHP
    function dropdownContent($sql,$field,$field_id,$current) {
	    global $mysqli;
        $result=$mysqli->query($sql);
	    $temp=""; 
	    $temp.='<option value="">Összes</option>';
        while($row=$result->fetch_assoc()) 
        {
	        if($row[$field_id]==$current)
	    	$temphelp="selected";
	    	else
	    	$temphelp="";
	    	$temp.='<option value="'.$row[$field_id].'" '.$temphelp.'>'.$row[$field].'</option>';
        }
	    return $temp;
    }
    function showSpecieOptions() {
        global $mysqli;
        $sql="SELECT * FROM faj;";
        if(!isset($_GET['specie'])) $_GET['specie']="";
        echo dropdownContent($sql,"faj","faj",$_GET['specie']);
    }
    function showAgeOptions() {
        global $mysqli;
        $specie = $_GET['specie'];
        if(isset($_GET['specie']) && $_GET['specie']!= "") {
            $sql = "SELECT DISTINCT faj.faj, eletkor_hullo.eletkor FROM elesegvalaszto_hullo INNER JOIN faj ON elesegvalaszto_hullo.faj_id = faj.id INNER JOIN eletkor_hullo ON elesegvalaszto_hullo.eletkor_hullo_id = eletkor_hullo.id WHERE faj.faj = '$specie';"; 
            echo dropdownContent($sql,"eletkor","eletkor",$_GET['age']);
        }
        else
		{
  		    echo '<option value="">Előbb válasszon fajt</option>';
		}
    }
    function displayDropdownResults() {
        global $mysqli;
        $specie = $_GET['specie'];
        if(isset($_GET['specie']) && $_GET['specie']!= "") {
        $age = $_GET['age'];
        }
        if (isset($_GET['specie']) && isset($_GET['age']) && $_GET['specie']!= "" && $_GET['age']!= "") {
            echo "<h2 class='text-center'>Az ön hüllője a következő eleségeket tudja elfogyasztani:</h2>";
            $sql = "SELECT DISTINCT eleseg.eleseg_nev FROM elesegvalaszto_hullo INNER JOIN faj ON elesegvalaszto_hullo.faj_id = faj.id INNER JOIN eletkor_hullo ON elesegvalaszto_hullo.eletkor_hullo_id = eletkor_hullo.id INNER JOIN eleseg ON elesegvalaszto_hullo.eleseg_id = eleseg.id WHERE faj.faj = '$specie' AND eletkor_hullo.eletkor = '$age';";
            $result = $mysqli->query($sql);
            $allFeedForReptile = array();
            if($result){
                while ($row = mysqli_fetch_assoc($result)){
                    $allFeedForReptile[] = $row;
                }
            } else {
                die("Nem sikerült a lekérdezés..." .$mysqli->error);
            }
            foreach ($allFeedForReptile as $feed) {
                echo "<ul>";
                    echo "<li><h3>". $feed['eleseg_nev'] ."</h3></li>";    
                echo "</ul>";
            }
        }
    }
#endregion

#region USER_PAGE.PHP
    function displayUserData() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $sqlUser = "SELECT * FROM felhasznalo WHERE id = '$id' ";
            $resultUser = $mysqli ->query($sqlUser);
            $data = mysqli_fetch_assoc($resultUser);
    
            if($resultUser){
                $name = $data['nev'];
                $username = $data['felhasznalo_nev'];
                $hashed_password = $data['jelszo'];
                $email = $data['email'];
                $phone = $data['telszam'];
                $postal_code = $data['iranyitoszam'];
                $address = $data['cim'];
            }

            ?>
            <label class="h5" for="name">Név</label><br>
            <input class="form-control form-control-lg" type="text" name="name" id="name" value="<?php echo $name; ?>" required><br>
            <label class="h5" for="username">Felhasználónév</label><br>
            <input class="form-control form-control-lg" type="text" name="username" id="username" value="<?php echo $username; ?>" required><br>
            <label class="h5" for="email">Email</label><br>
            <input class="form-control form-control-lg" type="email" name="email" id="email" value="<?php echo $email; ?>" required><br>
            <label class="h5" for="phone">Telefonszám</label><br>
            <input class="form-control form-control-lg" type="number" name="phone" id="phone" value="<?php echo $phone; ?>" required><br>
            <label class="h5" for="postal_code">Irányítószám</label><br>
            <input class="form-control form-control-lg" type="number" name="postal_code" id="postal_code" value="<?php echo $postal_code; ?>" required><br>
            <label class="h5" for="address">Cím</label><br>
            <input class="form-control form-control-lg" type="text" name="address" id="address" value="<?php echo $address; ?>" required><br><br>
            <?php
        }
    }

    if(array_key_exists('edit', $_POST)) {
        editUserData();
    }
    function editUserData() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }

        if(isset($_POST['edit'])){
            $name = $_POST['name'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $postal_code = $_POST['postal_code'];
            $address = $_POST['address'];
        
            $sql = "UPDATE felhasznalo SET nev = '$name', felhasznalo_nev = '$username', email = '$email', telszam = '$phone', iranyitoszam = '$postal_code', cim = '$address' WHERE id = '$id' ";
            $result = $mysqli->query($sql);
            if($result){
                header('location:index.php');
            }
            else{
                echo $mysqli->error;
            }
        }
    }

    function displayUserFeedAds() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        $sqlFeed = "SELECT * FROM hirdetes_eleseg WHERE hirdetes_eleseg.felhasznalo_id = '$id';";

        $resultFeed = $mysqli->query($sqlFeed);
        $allFeedAd = array();
        if($resultFeed){
            while ($row = mysqli_fetch_assoc($resultFeed)){
                $allFeedAd[] = $row;
            }
        } else {
            die("Nem sikerült a lekérdezés..." .$mysqli->error);
        }

        $i = 0;
        $numFeedItems = count($allFeedAd);
        if ($i == $numFeedItems) {
            echo "<tr>";
                    echo "<td class='text-center'>Még nincs egyetlen eleség hirdetésed sem!</td>";
            echo "</tr>";
        }
        else {
            foreach ($allFeedAd as $feedAd) {
                echo "<tr>";
                    echo "<td class='col-md-5'><img src=". $feedAd['kep'] ." width=30% height=30%/></td>";
                    echo "<td>". $feedAd['hirdetes_nev'] ."</td>";
                    echo "<td>". $feedAd['ar'] ." Ft/db</td>";
                    echo "<td>";
                        echo '<div class="d-flex gap-2 mx-auto">';
                            echo '<a class="btn btn-warning" href="feed_advertisement_edit.php?id='. $feedAd['id'] .'"><i class="fad fa-edit"></i> Módosítás</a>';
                            echo ($feedAd['statusz'] == 1) ? '<button type="submit" class="btn btn-danger" name="feed_deactivate'. $feedAd['id'] .'"><i class="fad fa-minus-circle"></i> Archiválás</button>' : '<button type="submit" class="btn btn-success" name="feed_reactivate'. $feedAd['id'] .'"><i class="fad fa-plus-circle"></i> Aktiválás</button>';
                        echo '</div>';
                    echo "</td>";    
                echo "</tr>";
                if(isset($_POST['feed_deactivate'. $feedAd['id'] .''])) { deactivateUserFeedAd(); echo "<script type ='text/javascript'>window.location.href = window.location.href</script>"; }
                if(isset($_POST['feed_reactivate'. $feedAd['id'] .''])) { reactivateUserFeedAd(); echo "<script type ='text/javascript'>window.location.href = window.location.href</script>"; }
            }
        }
    }

    function displayUserReptileAds() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        $sqlReptile = "SELECT * FROM hirdetes_hullo WHERE hirdetes_hullo.felhasznalo_id = '$id';";

        $resultReptile = $mysqli->query($sqlReptile);
        $allReptileAd = array();
        if($resultReptile){
            while ($row = mysqli_fetch_assoc($resultReptile)){
                $allReptileAd[] = $row;
            }
        } else {
            die("Nem sikerült a lekérdezés..." .$mysqli->error);
        }

        $i = 0;
        $numReptileItems = count($allReptileAd);
        if ($i == $numReptileItems) {
            echo "<tr>";
                echo "<td class='text-center'>Még nincs egyetlen hüllő hirdetésed sem!</td>";
            echo "</tr>";
        }
        else {
            foreach ($allReptileAd as $reptileAd) {
                echo "<tr>";
                    echo "<td class='col-md-5'><img src=". $reptileAd['kep'] ." width=30% height=30%/></td>";
                    echo "<td>". $reptileAd['hirdetes_nev'] ."</td>";
                    echo "<td>". $reptileAd['ar'] ." Ft/db</td>";
                    echo "<td>";
                        echo '<div class="d-flex gap-2 mx-auto">';
                            echo '<a class="btn btn-warning" href="reptile_advertisement_edit.php?id='. $reptileAd['id'] .'"><i class="fad fa-edit"></i> Módosítás</a>';
                            echo ($reptileAd['statusz'] == 1) ? '<button type="submit" class="btn btn-danger" name="reptile_deactivate'. $reptileAd['id'] .'"><i class="fad fa-minus-circle"></i> Archiválás</button>' : '<button type="submit" class="btn btn-success" name="reptile_reactivate'. $reptileAd['id'] .'"><i class="fad fa-plus-circle"></i> Aktiválás</button>';
                        echo '</div>';
                    echo "</td>";    
                echo "</tr>";
                if(isset($_POST['reptile_deactivate'. $reptileAd['id'] .''])) { deactivateUserReptileAd(); echo "<script type ='text/javascript'>window.location.href = window.location.href</script>"; }
                if(isset($_POST['reptile_reactivate'. $reptileAd['id'] .''])) { reactivateUserReptileAd(); echo "<script type ='text/javascript'>window.location.href = window.location.href</script>"; }

            }
        }

    }

    if(array_key_exists('editPassword', $_POST)) {
        editUserPassword();
    }
    function editUserPassword() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }

        $sqlUserPassword = "SELECT jelszo FROM felhasznalo WHERE id = '$id' ";
        $resultUserPassword = $mysqli ->query($sqlUserPassword);
        $data = mysqli_fetch_assoc($resultUserPassword);

        if($resultUserPassword) $hashed_password = $data['jelszo'];

        if(isset($_POST['editPassword'])){
            $password = $_POST['currentPassword'];
            if(password_verify($password, $hashed_password)){
                if ($_POST['newPassword1'] == $_POST['newPassword2']) {
                    $newPassword = password_hash($_POST['newPassword1'], PASSWORD_DEFAULT);
                    $sql = "UPDATE felhasznalo SET jelszo = '$newPassword' WHERE id = '$id';";
                    $result = $mysqli->query($sql);
                    if($result){
                        header('location:index.php');
                    }
                    else {
                        echo $mysqli->error;
                    }
                }
                else {
                    echo "<script type ='text/javascript'>alert('A kívánt jelszavak nem egyeznek!');</script>";
                }
                
            }
            else {
            echo "<script type ='text/javascript'>alert('A megadott jelszó helytelen!');</script>";
            }
        }
    }

    if(array_key_exists('deactivateAccount', $_POST)) {
        deactivateUser();
    }
    function deactivateUser() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
    
        if(isset($_POST['deactivateAccount'])){
            $sql = "UPDATE felhasznalo SET torolt_e = 1 WHERE id = '$id';";
            $result = $mysqli->query($sql);
            if($result){
                header('location:index.php');
            }
            else {
                echo $mysqli->error;
            }
        }
    }
    
    function deactivateUserReptileAd() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        $sqlReptile = "SELECT * FROM hirdetes_hullo WHERE hirdetes_hullo.felhasznalo_id = '$id';";
        $resultReptile = $mysqli->query($sqlReptile);
        $allReptileAd = array();
        if($resultReptile){
            while ($row = mysqli_fetch_assoc($resultReptile)){
                $allReptileAd[] = $row;
            }
        } else {
            die("Nem sikerült a lekérdezés..." .$mysqli->error);
        }
        foreach ($allReptileAd as $reptileAd) {
            if(isset($_POST['reptile_deactivate'. $reptileAd['id'] .''])){
                $currentID = $reptileAd['id'];
                $sql = "UPDATE hirdetes_hullo SET statusz = 0 WHERE id = '$currentID';";
                $result = $mysqli->query($sql);
                if($result){
                    echo "<script type ='text/javascript'>alert('Sikeres módosítás!');</script>";
                }
                else {
                    echo $mysqli->error;
                }
            }
        }
    }
    function reactivateUserReptileAd() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        $sqlReptile = "SELECT * FROM hirdetes_hullo WHERE hirdetes_hullo.felhasznalo_id = '$id';";
        $resultReptile = $mysqli->query($sqlReptile);
        $allReptileAd = array();
        if($resultReptile){
            while ($row = mysqli_fetch_assoc($resultReptile)){
                $allReptileAd[] = $row;
            }
        } else {
            die("Nem sikerült a lekérdezés..." .$mysqli->error);
        }
        foreach ($allReptileAd as $reptileAd) {
            if(isset($_POST['reptile_reactivate'. $reptileAd['id'] .''])){
                $currentID = $reptileAd['id'];
                $sql = "UPDATE hirdetes_hullo SET statusz = 1 WHERE id = '$currentID';";
                $result = $mysqli->query($sql);
                if($result){
                    echo "<script type ='text/javascript'>alert('Sikeres módosítás!');</script>";
                }
                else {
                    echo $mysqli->error;
                }
            }
        }
    }

    function deactivateUserFeedAd() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        $sqlFeed = "SELECT * FROM hirdetes_eleseg WHERE hirdetes_eleseg.felhasznalo_id = '$id';";
        $resultFeed = $mysqli->query($sqlFeed);
        $allFeedAd = array();
        if($resultFeed){
            while ($row = mysqli_fetch_assoc($resultFeed)){
                $allFeedAd[] = $row;
            }
        } else {
            die("Nem sikerült a lekérdezés..." .$mysqli->error);
        }
        foreach ($allFeedAd as $feedAd) {
            if(isset($_POST['feed_deactivate'. $feedAd['id'] .''])){
                $currentID = $feedAd['id'];
                $sql = "UPDATE hirdetes_eleseg SET statusz = 0 WHERE id = '$currentID';";
                $result = $mysqli->query($sql);
                if($result){
                    echo "<script type ='text/javascript'>alert('Sikeres módosítás!');</script>";
                }
                else {
                    echo $mysqli->error;
                }
            }
        }
    }
    function reactivateUserFeedAd() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        $sqlFeed = "SELECT * FROM hirdetes_eleseg WHERE hirdetes_eleseg.felhasznalo_id = '$id';";
        $resultFeed = $mysqli->query($sqlFeed);
        $allFeedAd = array();
        if($resultFeed){
            while ($row = mysqli_fetch_assoc($resultFeed)){
                $allFeedAd[] = $row;
            }
        } else {
            die("Nem sikerült a lekérdezés..." .$mysqli->error);
        }
        foreach ($allFeedAd as $feedAd) {
            if(isset($_POST['feed_reactivate'. $feedAd['id'] .''])){
                $currentID = $feedAd['id'];
                $sql = "UPDATE hirdetes_eleseg SET statusz = 1 WHERE id = '$currentID';";
                $result = $mysqli->query($sql);
                if($result){
                    echo "<script type ='text/javascript'>alert('Sikeres módosítás!');</script>";
                }
                else {
                    echo $mysqli->error;
                }
            }
        }
    }
#endregion

#region REPTILE_ADVERTISEMENT_EDIT.PHP
    function editReptileAd() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $sqlModifyReptileAd = "SELECT h.hullo_id, h.faj_id, h.felhasznalo_id, f.faj, h.leiras, h.hirdetes_nev, h.ar, h.darabszam, h.nem, h.kor, h.kep_neve, h.kep, h.publikacio_ideje_TS, h.lejarati_datum FROM hirdetes_hullo h INNER JOIN faj f ON h.faj_id = f.id WHERE h.id = '$id';";
            $resultModifyReptileAd = $mysqli ->query($sqlModifyReptileAd);
            $data = mysqli_fetch_assoc($resultModifyReptileAd);

            if($resultModifyReptileAd){
                $user_id = $data['felhasznalo_id'];
                $reptile_id = $data['faj_id'];
                $reptile_name = $data['faj'];
                $description = $data['leiras'];
                $current_image = $data['kep'];
                $ad_name = $data['hirdetes_nev'];
                $gender = $data['nem'];
                $age = $data['kor'];
                $price = $data['ar'];
                $amount = $data['darabszam'];
                $file = $data['kep_neve'];
            }
        }

        if(isset($_POST['reptileAd_modify'])){
            $species_id = $_POST['reptileAd_type'];
            $reptile_id = $species_id;
            $description = $_POST['reptileAd_description'];
            $ad_name = $_POST['reptileAd_name'];
            $price = $_POST['reptileAd_price'];
            $amount = $_POST['reptileAd_amount'];
            $age = $_POST['reptileAd_age'];
            $gender = $_POST['reptileAd_gender'];
            $ad_modified = date('Y-m-d H:i:s');
            $ad_expirity = date('Y-m-d H:i:s', strtotime('+30 days', strtotime($ad_modified)));
            if (empty($_FILES['reptileAd_picture']['name'])) {
                $sql = "UPDATE `hirdetes_hullo` SET hirdetes_hullo.faj_id = '$species_id', hirdetes_hullo.hullo_id = '$reptile_id', hirdetes_hullo.leiras = '$description', hirdetes_hullo.hirdetes_nev = '$ad_name', hirdetes_hullo.ar = '$price', hirdetes_hullo.darabszam = '$amount', hirdetes_hullo.nem = '$gender', hirdetes_hullo.kor = '$age', hirdetes_hullo.publikacio_ideje_TS = '$ad_modified', hirdetes_hullo.lejarati_datum = '$ad_expirity' WHERE hirdetes_hullo.id = '$id';";
                $result = $mysqli->query($sql);
                if ($result){
                    header('location:user_page.php?id='. $user_id .'');
                } else {
                    echo $mysqli->error;
                }
            }
            else {
                $filedel = "images/reptileImages/".$file;
                if (file_exists($filedel)) {
                    unlink($filedel);
                } else {
                    echo "Nincs ilyen fájl.";
                }
    
                $image_name = $_FILES['reptileAd_picture']['name'];
                $target_dir = "images/reptileImages";
                $target_file = $target_dir . basename($_FILES["reptileAd_picture"]["name"]);
    
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
                $extensions_arr = array("jpg", "jpeg", "png");
    
                if (in_array($imageFileType, $extensions_arr)) {
                    if (move_uploaded_file($_FILES['reptileAd_picture']['tmp_name'], 'images/reptileImages/'.$image_name)) {
                        $image_base64 = base64_encode(file_get_contents('images/reptileImages/'.$image_name));
                        $image = 'data:image/'.$imageFileType.';base64,'.$image_base64;
                        $sql = "UPDATE `hirdetes_hullo` SET hirdetes_hullo.faj_id = '$species_id', hirdetes_hullo.hullo_id = '$reptile_id', hirdetes_hullo.leiras = '$description', hirdetes_hullo.kep_neve = '$image_name', hirdetes_hullo.kep = '$image', hirdetes_hullo.hirdetes_nev = '$ad_name', hirdetes_hullo.ar = '$price', hirdetes_hullo.darabszam = '$amount', hirdetes_hullo.nem = '$gender', hirdetes_hullo.kor = '$age', hirdetes_hullo.publikacio_ideje_TS = '$ad_modified', hirdetes_hullo.lejarati_datum = '$ad_expirity' WHERE hirdetes_hullo.id = '$id';";
                
                        $result = $mysqli->query($sql);
                        if ($result){
                            header('location:user_page.php?id='. $user_id .'');
                        } else {
                            echo $mysqli->error;
                        }
    
                    }
                }
            }
        }
        ?>

        <div class="form-input mt-3">
            <label for="reptileAd_name"><i class="fad fa-hand-point-right"></i>Hirdetés címe</label>
            <input type="text" class="form-control form-control-lg" name="reptileAd_name" maxlength="50" value="<?php echo $ad_name; ?>" required>
        </div>
        <div class="form-input">
            <label class="form-label" for="reptileAd_description" required><i class="fad fa-hand-point-right"></i>Leírás</label>
            <textarea class="form-control form-control-lg" name="reptileAd_description" id="reptileAd_description" rows="4"><?php echo $description; ?></textarea>
        </div>
        <div class="form-input">
            <label for="reptileAd_type"><i class="fad fa-hand-point-right"></i>Hüllő fajtája</label>
            <select class="form-control form-control-lg" name="reptileAd_type" id="reptileAd_type" required><br>
                <?php
                    $resultSpecies = $mysqli->query("SELECT * FROM faj");
                    if($resultSpecies){
                        while($rowSpecies = mysqli_fetch_assoc($resultSpecies)){
                        $reptilesSpecies[] = $rowSpecies;
                        }
                    }
                    $selected = "";
                    foreach ($reptilesSpecies as $reptileSpecies) {
                        ($reptile_name == $reptileSpecies['faj']) ? $selected = "selected" : $selected = "";
                        echo "<option value=". $reptileSpecies['id']." $selected>".$reptileSpecies['faj'] ."</option>";
                    }
                ?>
            </select>
        </div>
        <div class="form-input">
            <label for="reptileAd_age"><i class="fad fa-hand-point-right"></i>Hüllő életkora</label>
            <input type="number" step="0.1" class="form-control form-control-lg" name="reptileAd_age" value="<?php echo $age; ?>" required>
        </div>
        <div class="form-input">
            <label for="reptileAd_gender"><i class="fad fa-hand-point-right"></i>Hüllő neme</label>
            <select class="form-control form-control-lg" name="reptileAd_gender" id="reptileAd_gender" required>
                <?php
                    $resultGender = $mysqli->query("SELECT hirdetes_hullo.id, hirdetes_hullo.nem FROM hirdetes_hullo WHERE hirdetes_hullo.id = '$id';");
                    if($resultGender){
                        while($rowGender = mysqli_fetch_assoc($resultGender)){
                        $reptilesGender[] = $rowGender;
                        }
                    }
                    $selected = "";
                    $value = "";
                    foreach ($reptilesGender as $reptileGender) {
                        ($reptileGender['nem'] == 0) ? ($selected = "selected" AND $value = "Még nem megállapítható") : (($reptileGender['nem'] == 1) ? ($selected = "selected" AND $value = "Nőstény") : (($reptileGender['nem'] == 2) ? ($selected = "selected" AND $value = "Hím") : $selected = ""));
                        echo "<option value=". $reptileGender['nem'] ." $selected>". $value ."</option>";
                        if ($reptileGender['nem'] == 0) {
                            echo "<option value='1'>Nőstény</option>";
                            echo "<option value='2'>Hím</option>";
                        } elseif ($reptileGender['nem'] == 1) {
                            echo "<option value='0'>Még nem megállapítható</option>";
                            echo "<option value='2'>Hím</option>";
                        } elseif ($reptileGender['nem'] == 2) {
                            echo "<option value='0'>Még nem megállapítható</option>";
                            echo "<option value='1'>Nőstény</option>";
                        } else {
                            echo "Hiba";
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-input">
            <label for="reptileAd_amount"><i class="fad fa-hand-point-right"></i>Darabszám</label>
            <input type="number" class="form-control form-control-lg" name="reptileAd_amount" value="<?php echo $amount; ?>" required>
        </div>
        <div class="form-input">
            <label for="reptileAd_price"><i class="fad fa-hand-point-right"></i>Egységár</label>
            <input type="number" class="form-control form-control-lg" name="reptileAd_price" value="<?php echo $price; ?>" required>
        </div>
        <div class="form-input">
            <label for="current_picture"><i class="fad fa-hand-point-right"></i>Kép</label><br>
            <input type="image" src="<?php echo $current_image; ?>" name="current_picture" width=30% height=30%/>
        </div>
        <div class="form-input">
            <label for="reptileAd_picture"><i class="fad fa-hand-point-right"></i>Kép módosítása</label>
            <input type="file" class="form-control form-control-lg" name="reptileAd_picture" accept="image/png, image/jpeg, image/jpg">
        </div>
        <?php
    }
#endregion

#region FEED_ADVERTISEMENT_EDIT.PHP
    function editFeedAd() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $sqlModifyFeedAd = "SELECT h.eleseg_id, h.felhasznalo_id, e.eleseg_nev, h.leiras, h.hirdetes_nev, h.ar, h.darabszam, h.kep_neve, h.kep, h.publikacio_ideje_TS, h.lejarati_datum FROM hirdetes_eleseg h INNER JOIN eleseg e ON h.eleseg_id = e.id WHERE h.id = '$id';";
            $resultModifyFeedAd = $mysqli ->query($sqlModifyFeedAd);
            $data = mysqli_fetch_assoc($resultModifyFeedAd);
        
            if($resultModifyFeedAd){
                $user_id = $data['felhasznalo_id'];
                $feed_id = $data['eleseg_id'];
                $feed_name = $data['eleseg_nev'];
                $description = $data['leiras'];
                $current_image = $data['kep'];
                $ad_name = $data['hirdetes_nev'];
                $price = $data['ar'];
                $amount = $data['darabszam'];
                $file = $data['kep_neve'];
            }
        }
        
        if(isset($_POST['feedAd_modify'])){
            $feed_id = $_POST['feedAd_type'];
            $description = $_POST['feedAd_description'];
            $ad_name = $_POST['feedAd_name'];
            $price = $_POST['feedAd_price'];
            $amount = $_POST['feedAd_amount'];
            $ad_modified = date('Y-m-d H:i:s');
            $ad_expirity = date('Y-m-d H:i:s', strtotime('+30 days', strtotime($ad_modified)));
            if (empty($_FILES['feedAd_picture']['name'])) {
                $sql = "UPDATE `hirdetes_eleseg` SET hirdetes_eleseg.eleseg_id = '$feed_id', hirdetes_eleseg.leiras = '$description', hirdetes_eleseg.hirdetes_nev = '$ad_name', hirdetes_eleseg.ar = '$price', hirdetes_eleseg.darabszam = '$amount', hirdetes_eleseg.publikacio_ideje_TS = '$ad_modified', hirdetes_eleseg.lejarati_datum = '$ad_expirity' WHERE hirdetes_eleseg.id = '$id';";
                $result = $mysqli->query($sql);
                if ($result){
                    header('location:user_page.php?id='. $user_id .'');
                } else {
                    echo $mysqli->error;
                }
            }
            else {
                $filedel = "images/feedImages/".$file;
                if (file_exists($filedel)) {
                    unlink($filedel);
                } else {
                    echo "Nincs ilyen fájl.";
                }
    
                $image_name = $_FILES['feedAd_picture']['name'];
                $target_dir = "images/feedImages";
                $target_file = $target_dir . basename($_FILES["feedAd_picture"]["name"]);
    
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
                $extensions_arr = array("jpg", "jpeg", "png");
    
                if (in_array($imageFileType, $extensions_arr)) {
                    if (move_uploaded_file($_FILES['feedAd_picture']['tmp_name'], 'images/feedImages/'.$image_name)) {
                        $image_base64 = base64_encode(file_get_contents('images/feedImages/'.$image_name));
                        $image = 'data:image/'.$imageFileType.';base64,'.$image_base64;
                        $sql = "UPDATE `hirdetes_eleseg` SET hirdetes_eleseg.eleseg_id = '$feed_id', hirdetes_eleseg.leiras = '$description', hirdetes_eleseg.kep_neve = '$image_name', hirdetes_eleseg.kep = '$image', hirdetes_eleseg.hirdetes_nev = '$ad_name', hirdetes_eleseg.ar = '$price', hirdetes_eleseg.darabszam = '$amount', hirdetes_eleseg.publikacio_ideje_TS = '$ad_modified', hirdetes_eleseg.lejarati_datum = '$ad_expirity' WHERE hirdetes_eleseg.id = '$id';";
                
                        $result = $mysqli->query($sql);
                        if ($result){
                            header('location:user_page.php?id='. $user_id .'');
                        } else {
                            echo $mysqli->error;
                        }
    
                    }
                }
            }
        }
        ?>

        <div class="form-input mt-3">
            <label for="feedAd_name"><i class="fad fa-hand-point-right"></i>Hirdetés címe</label>
            <input type="text" class="form-control form-control-lg" name="feedAd_name" maxlength="50" value="<?php echo $ad_name; ?>" required>
        </div>
        <div class="form-input">
            <label class="form-label" for="feedAd_description" required><i class="fad fa-hand-point-right"></i>Leírás</label>
            <textarea class="form-control form-control-lg" name="feedAd_description" id="feedAd_description" rows="4"><?php echo $description; ?></textarea>
        </div>
        <div class="form-input">
            <label for="feedAd_type"><i class="fad fa-hand-point-right"></i>Eleség fajtája</label>
            <select class="form-control form-control-lg" name="feedAd_type" id="feedAd_type" required><br>
                <?php
                    $result = $mysqli->query("SELECT * FROM eleseg");
                    if($result){
                        while($row = mysqli_fetch_assoc($result)){
                        $feeds[] = $row;
                        }
                    }
                    $selected = "";
                    foreach ($feeds as $feed) {
                        ($feed_name == $feed['eleseg_nev']) ? $selected = "selected" : $selected = "";
                        echo "<option value=". $feed['id']." $selected>".$feed['eleseg_nev'] ."</option>";
                    }
                ?>
            </select>
        </div>
        <div class="form-input">
            <label for="feedAd_amount"><i class="fad fa-hand-point-right"></i>Mennyiség (darab)</label>
            <input type="number" class="form-control form-control-lg" name="feedAd_amount" value="<?php echo $amount; ?>" required>
        </div>
        <div class="form-input">
            <label for="feedAd_price"><i class="fad fa-hand-point-right"></i>Egységár</label>
            <input type="number" class="form-control form-control-lg" name="feedAd_price" value="<?php echo $price; ?>" required>
        </div>
        <div class="form-input">
            <label for="current_picture"><i class="fad fa-hand-point-right"></i>Kép</label><br>
            <input type="image" src="<?php echo $current_image; ?>" name="current_picture" width=30% height=30%/>
        </div>
        <div class="form-input">
            <label for="feedAd_picture"><i class="fad fa-hand-point-right"></i>Kép módosítása</label>
            <input type="file" class="form-control form-control-lg" name="feedAd_picture" accept="image/png, image/jpeg, image/jpg">
        </div>
        <?php
    }
#endregion

#region REPTILE_ADVERTISEMENT_DETAILS.PHP
    function showReptileAdDetails() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
    
            $sqlReptile = "SELECT hh.id, hh.hullo_id, hh.faj_id, hh.felhasznalo_id, hh.leiras, hh.kep, hh.hirdetes_nev, hh.ar, hh.darabszam, hh.nem, hh.kor, hh.statusz, hh.publikacio_ideje_TS, hh.lejarati_datum, h.nev, h.gyakorisag, h.max_ho, h.min_ho, h.uvb, h.vitamin, h.paratartalom_id, h.etkezes_tipus_id, h.emesztes, f.felhasznalo_nev, f.telszam, fj.faj, fj.csalad FROM hirdetes_hullo hh INNER JOIN hullo h ON hh.hullo_id = h.id INNER JOIN felhasznalo f ON hh.felhasznalo_id = f.id INNER JOIN faj fj ON hh.faj_id = fj.id WHERE hh.id = '$id';";
    
            $resultReptile = $mysqli->query($sqlReptile);
            $allReptileAd = array();
            if($resultReptile){
                while ($line = mysqli_fetch_assoc($resultReptile)){
                    $allReptileAd[] = $line;
                }
            } else {
                die("Nem sikerült a lekérdezés..." .$mysqli->error);
            }
        }

        foreach ($allReptileAd as $reptileAd) {
            $vitamin = ($reptileAd['vitamin'] == 1) ? 'Van' : 'Nincs';
            $paratartalom = ($reptileAd['paratartalom_id'] == 1) ? "Alacsony" : (($reptileAd['paratartalom_id'] == 2)  ? "Közepes" : "Magas");
            $etkezesi_tipus = ($reptileAd['etkezes_tipus_id'] == 1) ? "Rovarevő" : (($reptileAd['etkezes_tipus_id'] == 2) ? "Rágcsáló" : (($reptileAd['etkezes_tipus_id'] == 3) ? "Növényevő" : (($reptileAd['etkezes_tipus_id'] == 4) ? "Mindenevő" : (($reptileAd['etkezes_tipus_id'] == 5) ? "Rovar- és rágcsálóevő" : "Növény- és rovarevő"))));
            echo "<tr>";
                echo "<td class='text-center'><img src=". $reptileAd['kep'] ." width=50% height=50%/></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hirdetés neve: ". $reptileAd['hirdetes_nev'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hirdetés leírása: ". $reptileAd['leiras'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hüllő típusa: ". $reptileAd['nev'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hüllőcsalád: ". $reptileAd['csalad'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hüllő etetésének gyakorisága: ". $reptileAd['gyakorisag'] ." nap</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hüllő hőmérsékletigénye: ". $reptileAd['min_ho'] ." °C - ". $reptileAd['max_ho'] ." °C</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hüllőnek van-e vitaminigénye: ". $vitamin ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Mit fogyaszt a hüllő: ". $etkezesi_tipus ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>A hüllő emésztési ciklusa: ". $reptileAd['emesztes'] ." nap</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>A hüllőnek való páratartalom szintje: ". $paratartalom ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Eladni kívánt mennyiség: ". $reptileAd['darabszam'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hirdetés feladásának ideje: ". $reptileAd['publikacio_ideje_TS'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hirdetés feladója: ". $reptileAd['felhasznalo_nev'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hirdető telefonszáma: ". $reptileAd['telszam'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Ár: ". $reptileAd['ar'] ." Ft/db</td>";
            echo "</tr>";
            echo "<td class='text-center'><a class='btn btn-light' href='javascript:history.back()'><i class='fad fa-arrow-circle-left'></i> Vissza</a></td>";
        } 
    }
#endregion

#region FEED_ADVERTISEMENT_DETAILS.PHP
    function showFeedAdDetails() {
        global $mysqli;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
    
            $sqlFeed = "SELECT h.id, h.eleseg_id, h.felhasznalo_id, h.leiras, h.kep, h.hirdetes_nev, h.ar, h.darabszam, h.statusz, h.publikacio_ideje_TS, h.lejarati_datum, f.felhasznalo_nev, f.telszam, e.eleseg_nev, e.tarolasi_ido FROM hirdetes_eleseg h INNER JOIN eleseg e ON h.eleseg_id = e.id INNER JOIN felhasznalo f ON h.felhasznalo_id = f.id WHERE h.id = '$id';";
    
            $resultFeed = $mysqli->query($sqlFeed);
            $allFeedAd = array();
            if($resultFeed){
                while ($row = mysqli_fetch_assoc($resultFeed)){
                    $allFeedAd[] = $row;
                }
            } else {
                die("Nem sikerült a lekérdezés..." .$mysqli->error);
            }
        }
        
        foreach ($allFeedAd as $feedAd) {
            echo "<tr>";
                echo "<td class='text-center'><img src=". $feedAd['kep'] ." width=50% height=50%/></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hirdetés neve: ". $feedAd['hirdetes_nev'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Eleség típusa: ". $feedAd['eleseg_nev'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Eleség tárolási ideje: ". $feedAd['tarolasi_ido'] ." nap</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hirdetés leírása: ". $feedAd['leiras'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Eladni kívánt mennyiség: ". $feedAd['darabszam'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hirdetés feladásának ideje: ". $feedAd['publikacio_ideje_TS'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hirdetés feladója: ". $feedAd['felhasznalo_nev'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Hirdető telefonszáma: ". $feedAd['telszam'] ."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='text-center'>Ár: ". $feedAd['ar'] ." Ft/db</td>";
            echo "</tr>";
            echo "<td class='text-center'><a class='btn btn-light' href='javascript:history.back()'><i class='fad fa-arrow-circle-left'></i> Vissza</a></td>";
        }
    }
#endregion

#region COMPARE_REPTILE.PHP
    function showCompareReptileResults() {
        global $mysqli;
        $passedValues = $_GET['compareReptile'];
        $sqlCompareReptileAd1 = "SELECT * FROM hirdetes_hullo hh INNER JOIN hullo h ON hh.hullo_id = h.id INNER JOIN faj fj ON hh.faj_id = fj.id INNER JOIN felhasznalo f ON hh.felhasznalo_id = f.id WHERE hh.id = '$passedValues[0]';";
        $sqlCompareReptileAd2 = "SELECT * FROM hirdetes_hullo hh INNER JOIN hullo h ON hh.hullo_id = h.id INNER JOIN faj fj ON hh.faj_id = fj.id INNER JOIN felhasznalo f ON hh.felhasznalo_id = f.id WHERE hh.id = '$passedValues[1]';";
        
        $resultCompareReptileAd1 = $mysqli->query($sqlCompareReptileAd1);
        $allComparedReptileAd1 = array();
        if($resultCompareReptileAd1){
            while ($row = mysqli_fetch_assoc($resultCompareReptileAd1)){
                $allCompareReptileAd1[] = $row;
            }
        } else {
            die("Nem sikerült a lekérdezés..." .$mysqli->error);
        }

        $resultCompareReptileAd2 = $mysqli->query($sqlCompareReptileAd2);
        $allComparedReptileAd2 = array();
        if($resultCompareReptileAd2){
            while ($row = mysqli_fetch_assoc($resultCompareReptileAd2)){
                $allCompareReptileAd2[] = $row;
            }
        } else {
            die("Nem sikerült a lekérdezés..." .$mysqli->error);
        }

        foreach ($allCompareReptileAd1 as $reptileAd) {
            foreach ($allCompareReptileAd2 as $reptileAd2) {
                $vitamin = ($reptileAd['vitamin'] == 1) ? "Szükséges" : "Nem szükséges";
                $vitamin2 = ($reptileAd2['vitamin'] == 1) ? "Szükséges" : "Nem szükséges";
                $paratartalom = ($reptileAd['paratartalom_id'] == 1) ? "Alacsony" : (($reptileAd['paratartalom_id'] == 2)  ? "Közepes" : "Magas");
                $paratartalom2 = ($reptileAd2['paratartalom_id'] == 1) ? "Alacsony" : (($reptileAd2['paratartalom_id'] == 2)  ? "Közepes" : "Magas");
                $etkezesi_tipus = ($reptileAd['etkezes_tipus_id'] == 1) ? "Rovarevő" : (($reptileAd['etkezes_tipus_id'] == 2) ? "Rágcsáló" : (($reptileAd['etkezes_tipus_id'] == 3) ? "Növényevő" : (($reptileAd['etkezes_tipus_id'] == 4) ? "Mindenevő" : (($reptileAd['etkezes_tipus_id'] == 5) ? "Rovar- és rágcsálóevő" : "Növény- és rovarevő"))));
                $etkezesi_tipus2 = ($reptileAd2['etkezes_tipus_id'] == 1) ? "Rovarevő" : (($reptileAd2['etkezes_tipus_id'] == 2) ? "Rágcsáló" : (($reptileAd2['etkezes_tipus_id'] == 3) ? "Növényevő" : (($reptileAd2['etkezes_tipus_id'] == 4) ? "Mindenevő" : (($reptileAd2['etkezes_tipus_id'] == 5) ? "Rovar- és rágcsálóevő" : "Növény- és rovarevő"))));
                echo "<tr>";
                    echo "<td>Hirdetés neve: ". $reptileAd['hirdetes_nev'] ."</td>";
                    echo "<td>". $reptileAd2['hirdetes_nev'] ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='col-md-5'><img src=". $reptileAd['kep'] ." width=50% height=50%/></td>";
                    echo "<td class='col-md-5'><img src=". $reptileAd2['kep'] ." width=50% height=50%/></td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hirdetés leírása: ". $reptileAd['leiras'] ."</td>";
                    echo "<td>". $reptileAd2['leiras'] ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Mennyiség: ". $reptileAd['darabszam'] ."</td>";
                    echo "<td>". $reptileAd2['darabszam'] ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Ár: ". $reptileAd['ar'] ." Ft/db</td>";
                    echo "<td>". $reptileAd2['ar'] ." Ft/db</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hirdetés feladásának ideje: ". $reptileAd['publikacio_ideje_TS'] ."</td>";
                    echo "<td>". $reptileAd2['publikacio_ideje_TS'] ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hirdetést feladó személy: ". $reptileAd['felhasznalo_nev'] ."</td>";
                    echo "<td>". $reptileAd2['felhasznalo_nev'] ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hirdetést feladó személy elérhetősége: ". $reptileAd['telszam'] ."</td>";
                    echo "<td>". $reptileAd2['telszam'] ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td colspan='2'>A reptárium csapata által biztosított addícionális információ:</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hüllő fajtája: ". $reptileAd['faj'] ."</td>";
                    echo "<td>". $reptileAd2['faj'] ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hüllő családja: ". $reptileAd['csalad'] ."</td>";
                    echo "<td>". $reptileAd2['csalad'] ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hány naponta ajánlott etetni: ". $reptileAd['gyakorisag'] ." nap</td>";
                    echo "<td>". $reptileAd2['gyakorisag'] ." nap</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>A hüllőnek ajánlott páratartalom szintje: ". $paratartalom ."</td>";
                    echo "<td>". $paratartalom2 ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hüllő ajánlott hőigénye: ". $reptileAd['min_ho'] ." °C - ". $reptileAd['max_ho'] ." °C</td>";
                    echo "<td>". $reptileAd2['min_ho'] ." °C - ". $reptileAd2['max_ho'] ." °C</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Szükséges-e vitamin a hüllőnek: ". $vitamin ."</td>";
                    echo "<td>". $vitamin2 ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hüllő várható utódainak száma: ". $reptileAd['varhato_utod_min'] ."  - ". $reptileAd['varhato_utod_max'] ." utód</td>";
                    echo "<td>". $reptileAd2['varhato_utod_min'] ."  - ". $reptileAd2['varhato_utod_max'] ." utód</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hüllő számára ideális élőhely méretigénye: ". $reptileAd['elohely_meretigeny_szelesseg'] ."x". $reptileAd['elohely_meretigeny_melyseg'] ."x". $reptileAd['elohely_meretigeny_magassag'] ." cm</td>";
                    echo "<td>". $reptileAd2['elohely_meretigeny_szelesseg'] ."x". $reptileAd2['elohely_meretigeny_melyseg'] ."x". $reptileAd2['elohely_meretigeny_magassag'] ." cm</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hüllő átlagos élettartama: ". $reptileAd['elettartam_min'] ."  - ". $reptileAd['elettartam_max'] ." év</td>";
                    echo "<td>". $reptileAd2['elettartam_min'] ."  - ". $reptileAd2['elettartam_max'] ." év</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>A hüllő fogyasztási hajlandósága: ". $etkezesi_tipus ."</td>";
                    echo "<td>". $etkezesi_tipus2 ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>A hüllő emésztési ciklusa: ". $reptileAd['emesztes'] ." nap</td>";
                    echo "<td>". $reptileAd2['emesztes'] ." nap</td>";
                echo "</tr>";
                    echo "<td colspan='2'>";
                        echo '<div class="gap-2 mx-auto">';
                        echo '<a class="btn btn-light" href="javascript:history.back()"><i class="fad fa-arrow-circle-left"></i> VISSZA</a>';
                        echo '</div>';
                    echo "</td>";    
                echo "</tr>";
            }
        }
    }
#endregion

#region COMPARE_FEED.PHP
    function showCompareFeedResults() {
        global $mysqli;
        $passedValues = $_GET['compareFeed'];
        $sqlCompareFeedAd1 = "SELECT * FROM hirdetes_eleseg h INNER JOIN eleseg e ON h.eleseg_id = e.id INNER JOIN felhasznalo f ON h.felhasznalo_id = f.id WHERE h.id = '$passedValues[0]';";
        $sqlCompareFeedAd2 = "SELECT * FROM hirdetes_eleseg h INNER JOIN eleseg e ON h.eleseg_id = e.id INNER JOIN felhasznalo f ON h.felhasznalo_id = f.id WHERE h.id = '$passedValues[1]';";
        
        $resultCompareFeedAd1 = $mysqli->query($sqlCompareFeedAd1);
        $allComparedFeedAd1 = array();
        if($resultCompareFeedAd1){
            while ($row = mysqli_fetch_assoc($resultCompareFeedAd1)){
                $allCompareFeedAd1[] = $row;
            }
        } else {
            die("Nem sikerült a lekérdezés..." .$mysqli->error);
        }
    
        $resultCompareFeedAd2 = $mysqli->query($sqlCompareFeedAd2);
        $allComparedFeedAd2 = array();
        if($resultCompareFeedAd2){
            while ($row = mysqli_fetch_assoc($resultCompareFeedAd2)){
                $allCompareFeedAd2[] = $row;
            }
        } else {
            die("Nem sikerült a lekérdezés..." .$mysqli->error);
        }

        foreach ($allCompareFeedAd1 as $feedAd) {
            foreach ($allCompareFeedAd2 as $feedAd2) {
                $packing = ($feedAd['meret'] == 1) ? "csomag" : "darab";
                $packing2 = ($feedAd2['meret'] == 1) ? "csomag" : "darab";
                echo "<tr>";
                    echo "<td>Hirdetés neve: ". $feedAd['hirdetes_nev'] ."</td>";
                    echo "<td>". $feedAd2['hirdetes_nev'] ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='col-md-5'><img src=". $feedAd['kep'] ." width=50% height=50%/></td>";
                    echo "<td class='col-md-5'><img src=". $feedAd2['kep'] ." width=50% height=50%/></td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hirdetés leírása: ". $feedAd['leiras'] ."</td>";
                    echo "<td>". $feedAd2['leiras'] ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Mennyiség: ". $feedAd['darabszam'] ." ". $packing . "</td>";
                    echo "<td>". $feedAd2['darabszam'] ." ". $packing2 . "</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Ár: ". $feedAd['ar'] ." Ft/db</td>";
                    echo "<td>". $feedAd2['ar'] ." Ft/db</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hirdetés feladásának ideje: ". $feedAd['publikacio_ideje_TS'] ."</td>";
                    echo "<td>". $feedAd2['publikacio_ideje_TS'] ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hirdetést feladó személy: ". $feedAd['felhasznalo_nev'] ."</td>";
                    echo "<td>". $feedAd2['felhasznalo_nev'] ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Hirdetést feladó személy elérhetősége: ". $feedAd['telszam'] ."</td>";
                    echo "<td>". $feedAd2['telszam'] ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td colspan='2'>A reptárium csapata által biztosított addícionális információ:</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Eleség fajtája: ". $feedAd['eleseg_nev'] ."</td>";
                    echo "<td>". $feedAd2['eleseg_nev'] ."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Eleség fehérje tartalma: ". $feedAd['feherje_tartalom'] ." %</td>";
                    echo "<td>". $feedAd2['feherje_tartalom'] ." %</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>Eleség megvételétől javasolt tárolási idő: ". $feedAd['tarolasi_ido'] ." nap</td>";
                    echo "<td>". $feedAd2['tarolasi_ido'] ." nap</td>";
                echo "</tr>";
                    echo "<td colspan='2'>";
                        echo '<div class="gap-2 mx-auto">';
                        echo '<a class="btn btn-light" href="javascript:history.back()"><i class="fad fa-arrow-circle-left"></i> VISSZA</a>';
                        echo '</div>';
                    echo "</td>";    
                echo "</tr>";
            }
        }
    }
#endregion

#region ADVERTISEMENTS.PHP
    function displayFeedAds() {
        global $mysqli;
        $adsPerPage = 3;

        $sqlFeeds = "SELECT * FROM hirdetes_eleseg WHERE statusz = 1 LIMIT $adsPerPage;";
        $resultFeeds = $mysqli->query($sqlFeeds);

        echo "<div class='float-left'>";
            echo "<input type='submit' class='btn mt-3 mb-3' value='Összehasonlítás' id='feedCompareButton'/>";
        echo "</div>";

        while ($row = mysqli_fetch_array($resultFeeds)){
            echo "<tr>";
                echo "<td class='col-md-5'><img src=". $row['kep'] ." width=30% height=30%/></td>";
                echo "<td>". $row['hirdetes_nev'] ."</td>";
                echo "<td>". $row['ar'] ." Ft/db</td>";
                echo "<td>";
                    echo '<div class="d-flex gap-2 mx-auto">';
                    echo '<a class="btn btn-light" href="feed_advertisement_details.php?id='. $row['id'] .'"><i class="fad fa-arrow-circle-right"></i> Részletek</a>';
                    echo '<input class="uncheck mt-2" type="checkbox" id="compareFeed['. $row['id'] .']" name="compareFeed[]" value="'. $row['id'] .'" /><label class="form-check-label" for="compareFeed[]">Összehasonlítom</label>';
                    echo '</div>';
                echo "</td>";    
            echo "</tr>";
            $post_id = $row['id'];
        }
        ?>
        <tr id="feed_remove_row">  
            <td colspan="5"><button type="button" name="feed_btn_more" data-id="<?php echo $post_id; ?>" id="feed_btn_more" class="btn btn-success form-control">Több hirdetés megjelenítése</button></td>  
        </tr>
        <?php
    }

    function displayReptileAds() {
        global $mysqli;
        $adsPerPage = 3;

        $sqlReptiles = "SELECT * FROM hirdetes_hullo WHERE statusz = 1 LIMIT $adsPerPage;";
        $resultReptiles = $mysqli->query($sqlReptiles);
        echo "<div class='float-left'>";
            echo "<input type='submit' class='btn mt-3 mb-3' value='Összehasonlítás' id='reptileCompareButton'/>";
        echo "</div>";

        while ($row = mysqli_fetch_array($resultReptiles)){
            echo "<tbody>";
            echo "<tr>";
                echo "<td class='col-md-5'><img src=". $row['kep'] ." width=30% height=30%/></td>";
                echo "<td>". $row['hirdetes_nev'] ."</td>";
                echo "<td>". $row['ar'] ." Ft/db</td>";
                echo "<td>";
                    echo '<div class="d-flex gap-2 mx-auto">';
                    echo '<a class="btn btn-light" href="reptile_advertisement_details.php?id='. $row['id'] .'"><i class="fad fa-arrow-circle-right"></i> Részletek</a>';
                    echo '<input class="uncheck mt-2" type="checkbox" id="compareReptile['. $row['id'] .']" name="compareReptile[]" value="'. $row['id'] .'" /><label class="form-check-label" for="compareReptile[]">Összehasonlítom</label>';
                    echo '</div>';
                echo "</td>";    
            echo "</tr>";
            echo "</tbody>";
            $post_id = $row['id'];
        }
        ?>
        <tr id="reptile_remove_row">  
            <td colspan="5"><button type="button" name="reptile_btn_more" data-id="<?php echo $post_id; ?>" id="reptile_btn_more" class="btn btn-success form-control">Több hirdetés megjelenítése</button></td>  
        </tr>
        <?php
    }
#endregion

#region REPTILE_ADVERTISEMENTS_LOADER.PHP
    function loadMoreReptileAd() {
        global $mysqli;
        $output = '';  
        $post_id = '';
        sleep(1);

        $currentId = $_POST['post_id'];

        $sqlReptile = "SELECT * FROM hirdetes_hullo WHERE id > $currentId AND statusz = 1 ORDER BY id ASC LIMIT 3";
        $resultReptile = $mysqli->query($sqlReptile);
        if(mysqli_num_rows($resultReptile) > 0)  
        {
            while($row = mysqli_fetch_array($resultReptile))
            {   
                $output .=  '<tbody>';
                $output .=  '<tr>';
                $output .= '<td class="col-md-5"><img src="'. $row['kep'] .'" width=30% height=30%/></td>';
                $output .= '<td>'. $row['hirdetes_nev'] .'</td>';
                $output .= '<td>'. $row['ar'] .' Ft/db</td>';
                $output .= '<td>';
                $output .= '<div class="d-flex gap-2 mx-auto">';
                $output .= '<a class="btn btn-light" href="reptile_advertisement_details.php?id='. $row['id'] .'"><i class="fad fa-arrow-circle-right"></i> Részletek</a>';
                $output .= '<input class="uncheck mt-2" type="checkbox" id="compareReptile['. $row['id'] .']" name="compareReptile[]" value="'. $row['id'] .'" /><label class="form-check-label" for="compareReptile[]">Összehasonlítom</label>';
                $output .= '</div>';
                $output .= '</td>';
                $output .= '</tr>';
                $output .=  '</tbody>';
                $post_id = $row['id']; 
            }    
            $output .= '<tbody><tr id="reptile_remove_row">'; 
            $output .= '<td colspan="5"><button type="button" name="reptile_btn_more" data-id="'. $post_id .'" id="reptile_btn_more" class="btn btn-success form-control">Több hirdetés megjelenítése</button></td>';
            $output .= '</tr>';
            $output .= '</tbody>';  

            echo $output;  
        }  
    }
#endregion

#region FEED_ADVERTISEMENTS_LOADER.PHP
    function loadMoreFeedAd() {
        global $mysqli;
        $output = '';  
        $post_id = '';  
        sleep(1);

        $currentId = $_POST['post_id'];

        $sqlFeed = "SELECT * FROM hirdetes_eleseg WHERE id > $currentId AND statusz = 1 ORDER BY id ASC LIMIT 3";
        $resultFeed = $mysqli->query($sqlFeed);
        if(mysqli_num_rows($resultFeed) > 0)  
        {
            while($row = mysqli_fetch_array($resultFeed))
            {   
                $output .=  '<tbody>'; 
                $output .=  '<tr>';
                $output .= '<td class="col-md-5"><img src="'. $row['kep'] .'" width=30% height=30%/></td>';
                $output .= '<td>'. $row['hirdetes_nev'] .'</td>';
                $output .= '<td>'. $row['ar'] .' Ft/db</td>';
                $output .= '<td>';
                $output .= '<div class="d-flex gap-2 mx-auto">';
                $output .= '<a class="btn btn-light" href="feed_advertisement_details.php?id='. $row['id'] .'"><i class="fad fa-arrow-circle-right"></i> Részletek</a>';
                $output .= '<input class="uncheck mt-2" type="checkbox" id="compareFeed['. $row['id'] .']" name="compareFeed[]" value="'. $row['id'] .'" /><label class="form-check-label" for="compareFeed[]">Összehasonlítom</label>';
                $output .= '</div>';
                $output .= '</td>';
                $output .= '</tr>';
                $output .=  '</tbody>'; 
                $post_id = $row['id']; 
            }    
            $output .= '<tbody><tr id="feed_remove_row">'; 
            $output .= '<td colspan="5"><button type="button" name="feed_btn_more" data-id="'. $post_id .'" id="feed_btn_more" class="btn btn-success form-control">Több hirdetés megjelenítése</button></td>';
            $output .= '</tr>';
            $output .= '</tbody>';  

            echo $output;
        }  
    }
#endregion
?>