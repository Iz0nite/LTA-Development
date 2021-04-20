<?php
    if(!isset($_SESSION))
        session_start();

    if(isset($_POST['formType']))
    {
        if($_POST['formType'] == "addPackage") {
            addPackage();
        }
        else if ($_POST['formType'] == "signUpCustomers") {
            signUpCustomers();
        }
        else if ($_POST['formType'] == "signUpDelivery") {
            signUpDelivery();
        }
        else if ($_POST['formType'] == "signIn") {
            connexion();
        }
        else if ($_POST['formType'] == "logout") {
            logOut();
        }
        else if ($_POST['formType'] == "updateProfile") {
            updateProfile();
        }
        else if ($_POST['formType'] == "updatePassword") {
            updatePassword();
        }
        else if ($_POST['formType'] == "appConnection") {
            appConnection();
        }
        else if ($_POST['formType'] == "deleteUser") {
            deleteUser();
        }
        else if ($_POST['formType'] == "deleteOrder") {
            deleteOrder();
        }
        else if ($_POST['formType'] == "deletePackage") {
            deletePackage();
        }
        else if ($_POST['formType'] == "addAdmin") {
            addAdmin();
        }

    }

    if(isset($_GET['formType'])){
        if ($_GET['formType'] == 'showPackage') {
            showPackage();
        }else if($_GET['formType'] == 'usersList'){
            usersList();
        }else if($_GET['formType'] == 'showUserDetails'){
            showUserDetails();
        }else if($_GET['formType'] == 'ordersList') {
            ordersList();
        }else if ($_GET['formType'] == 'showOrderDetails') {
            showOrderDetails();
        }else if ($_GET['formType'] == 'packagesList') {
            packagesList();
        }else if ($_GET['formType'] == 'showPackageDetails') {
            showPackageDetails();
        }else if ($_GET['formType'] == 'addVehicle') {
            addVehicle();
        }else if ($_GET['formType'] == 'deleteVehicle') {
            deleteVehicle();
        }
    }

    /* Setup SQL connexion */
    function setupCredentials()
    {
        try
        {
            $bdd = new PDO('mysql:dbname=LTA;host=localhost;port=3306;charset=utf8', 'ltaSuperUser', 'NEMBkJ36ry9U', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        }
        catch(Exception $e)
        {
            die('Erreur : '.$e->getMessage());
        }

        return $bdd;
    }


    /* Get user data according to the key passed in parameter */
    function getUserData($id, $key)
    {
        $req = setupCredentials()->prepare("SELECT " . $key . " FROM USERS WHERE idUser=:idUser");

        $req->execute(array(
            'idUser' => htmlspecialchars($id)
        ));

    	$rslt = $req->fetchAll(\PDO::FETCH_ASSOC);

        return $rslt[0][$key];
    }

    /* Add a new package to the database */
    function addPackage()
    {
        $req = setupCredentials()->prepare("INSERT INTO PACKAGES(weight, dimension, address, statusDelivery, idUser, idDeposit)
            VALUES(:weight, :dimension, :address, :statusDelivery, :idUser, :idDeposit)");

        $req->execute(array(
            'weight' => intval(htmlspecialchars($_POST['weight']), 10),
            'dimension' => intval(htmlspecialchars($_POST['dimension']), 10),
            'address' => htmlspecialchars($_POST['address']),
            'statusDelivery' => 0,
            'idUser' => $_SESSION['id'],
            'idDeposit' => 1
        ));

        header('Location: ./../en/createPackage');
    }



    /* Add a new customer to the database */
    function signUpCustomers()
    {
        $req = setupCredentials()->prepare("SELECT email FROM USERS WHERE email=?");
        $req->execute([
            $_POST['email'],
        ]);

        $res=$req->fetchAll(\PDO::FETCH_ASSOC);

        if ($res)
        {
            header('Location: ./../connection');
            exit();
        }

        if (strlen($_POST['password'])<8) {
            header('Location: ./../connection');
            exit();
        }

        if (strcmp($_POST['password'],$_POST['confirmPassword'])!=0) {
            header('Location: ./../en/connection');
            exit();
        }

        $status = 0;

        $bdd = setupCredentials();
        $req = $bdd->prepare("INSERT INTO USERS(companyName,email,address,telNumber,password,status) VALUES (:companyName, :email, :address, :telNumber, :password, :status)");
        $req->bindValue(':companyName', trim(htmlspecialchars($_POST['companyName'])), PDO::PARAM_STR);
        $req->bindValue(':email', trim(htmlspecialchars($_POST['email'])), PDO::PARAM_STR);
        $req->bindValue(':address', trim(htmlspecialchars($_POST['address'])), PDO::PARAM_STR);
        $req->bindValue(':telNumber', trim(htmlspecialchars($_POST['numTel'])), PDO::PARAM_STR);
        $req->bindValue(':password', hash('sha256', trim(htmlspecialchars($_POST['password']))), PDO::PARAM_STR);
        $req->bindParam(':status', $status, PDO::PARAM_INT);
        $req->execute();

        mkdir("./../users/" . $bdd->lastInsertId(), 0777, true);
        mkdir("./../users/" . $bdd->lastInsertId() . "/qrcode", 0777, true);
        mkdir("./../users/" . $bdd->lastInsertId() . "/bill", 0777, true);

        header('Location: ./../en/connection');
    }



    /* Add a new delivery man to the database */
    function signUpDelivery()
    {
        $req = setupCredentials()->prepare("SELECT email FROM USERS WHERE email=?");
        $req->execute([
            $_POST['email'],
        ]);

        $res=$req->fetchAll(\PDO::FETCH_ASSOC);

        if ($res)
        {
            header('Location: ./../en/connection');
            exit();
        }

        if (strlen($_POST['password'])<8) {
            header('Location: ./../en/connection');
            exit();
        }

        if (strcmp($_POST['password'],$_POST['confirmPassword'])!=0) {
            header('Location: ./../en/connection');
            exit();
        }

        $status = 1;

        $req = setupCredentials()->prepare("INSERT INTO USERS(firstName,name,email,telNumber,password,status) VALUES (:firstName, :name, :email, :telNumber, :password, :status)");
        $req->bindParam(':firstName', trim(htmlspecialchars($_POST['firstName'])), PDO::PARAM_STR);
        $req->bindParam(':name', trim(htmlspecialchars($_POST['name'])), PDO::PARAM_STR);
        $req->bindParam(':email', trim(htmlspecialchars($_POST['email'])), PDO::PARAM_STR);
        $req->bindParam(':telNumber', trim(htmlspecialchars($_POST['numTel'])), PDO::PARAM_STR);
        $req->bindParam(':password', hash('sha256', trim(htmlspecialchars($_POST['password']))), PDO::PARAM_STR);
        $req->bindParam(':status', $status, PDO::PARAM_INT);
        $req->execute();

        header('Location: ./../en/connection');
    }



    /* Check user credentials and connect to his session */
    function connexion()
    {
        $req = setupCredentials()->prepare("SELECT idUser,status FROM USERS WHERE email=? AND password=?");
        $req->execute([
            $_POST['email'],
            hash('sha256',$_POST['password'])
        ]);

        $res=$req->fetchAll(\PDO::FETCH_ASSOC);

        if($res)
        {
            $_SESSION['idUser'] = $res[0]['idUser'];
            $_SESSION['status'] = $res[0]['status'];
            $_SESSION['setMethod'] = 1;

            if ($_SESSION['status'] == 2) {
                header('Location: ./../en/dashBoard');
                exit();
            }

            header('Location: ./../en/home');
            exit();
        }
        else
        {
            session_unset();
            header('Location: ./../en/connection');
            exit();
        }
    }



    /* Clean all variable session */
    function logOut()
    {
        session_unset();

        header("Location: ./../en/connection");
    }



    /* Get the list of all deposits */
    function getDepositsList()
    {
        $req = setupCredentials()->prepare("SELECT * FROM DEPOSITS");

        $req->execute();

    	$res = $req->fetchAll(\PDO::FETCH_ASSOC);

        return $res;
    }


    /* Update user data */
    function updateProfile()
    {
        if ($_SESSION['status'] == 0)
        {
            if(!isset($_POST['address']) || !isset($_POST['phoneNumber']) || !isset($_POST['companyName']) || !isset($_POST['idDeposit']))
            {
                header("Location: ./../en/profile");
                exit();
            }

            $req = setupCredentials()->prepare("UPDATE USERS SET address=:address, telNumber=:telNumber, companyName=:companyName, idDeposit=:idDeposit WHERE idUser=:idUser");

            $req->bindParam(':address', trim(htmlspecialchars($_POST['address'])), PDO::PARAM_STR);
            $req->bindParam(':telNumber', trim(htmlspecialchars($_POST['phoneNumber'])), PDO::PARAM_STR);
            $req->bindParam(':companyName', trim(htmlspecialchars($_POST['companyName'])), PDO::PARAM_STR);
            $req->bindParam(':idDeposit', trim(htmlspecialchars($_POST['idDeposit'])), PDO::PARAM_INT);
            $req->bindParam(':idUser', trim(htmlspecialchars($_SESSION['idUser'])), PDO::PARAM_INT);
            $req->execute();

            header("Location: ./../en/profile");
        }
        else if ($_SESSION['status'] == 1) {

            if(!isset($_POST['phoneNumber']) || !isset($_POST['idDeposit']) || !isset($_POST['geoArea']))
            {
                header("Location: ./../en/profile");
                exit();
            }

            $req = setupCredentials()->prepare("UPDATE USERS SET telNumber=:telNumber, idDeposit=:idDeposit, geoArea=:geoArea WHERE idUser=:idUser");

            $req->bindParam(':telNumber', trim(htmlspecialchars($_POST['phoneNumber'])), PDO::PARAM_STR);
            $req->bindParam(':idDeposit', trim(htmlspecialchars($_POST['idDeposit'])), PDO::PARAM_INT);
            $req->bindParam(':geoArea', trim(htmlspecialchars($_POST['geoArea'])), PDO::PARAM_STR);
            $req->bindParam(':idUser', trim(htmlspecialchars($_SESSION['idUser'])), PDO::PARAM_INT);
            $req->execute();

            header("Location: ./../en/profile");
        }
    }

    function vehicleList(){
        include_once("./../config/configLanguage.php");

        $profileTextLoad = loadProfileText();

        $req = setupCredentials()->prepare("SELECT idVehicle,registration,volumeSize FROM VEHICLE WHERE idUser=:idUser");
        $req->bindParam(':idUser', trim(htmlspecialchars($_SESSION['idUser'])), PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value) {
            echo "<div>
                    <label>" . $profileTextLoad['registration'][$_COOKIE['language']] . ": ".$value['registration']."</label>
                    <label>" . $profileTextLoad['volumeMax'][$_COOKIE['language']] . ": ".$value['volumeSize']."m3</label>
                    <button type='button' name='deleteVehicle' onclick='deleteVehicle(".$value['idVehicle'].")'>" . $profileTextLoad['deleteVehicle'][$_COOKIE['language']] . "</button>
                 </div>";
        }
    }


    function addVehicle(){

        $req = setupCredentials()->prepare("SELECT registration FROM VEHICLE WHERE registration=:registration");
        $req->bindParam(':registration', $_GET['registration'], PDO::PARAM_STR);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        if ($res)
        {
            vehicleList();
            exit();
        }

        if ($_GET['registration'] == "" || $_GET['volumeSize'] == "") {
            vehicleList();
            exit();
        }

        $req = setupCredentials()->prepare("INSERT INTO VEHICLE(registration,volumeSize,idUser) VALUES (:registration,:volumeSize,:idUser)");
        $req->bindParam(':registration', trim(htmlspecialchars($_GET['registration'])), PDO::PARAM_STR);
        $req->bindParam(':volumeSize', trim(htmlspecialchars($_GET['volumeSize'])), PDO::PARAM_INT);
        $req->bindParam(':idUser', trim(htmlspecialchars($_SESSION['idUser'])), PDO::PARAM_INT);
        $req->execute();

        vehicleList();
    }

    function deleteVehicle(){

        $req = setupCredentials()->prepare("DELETE FROM VEHICLE WHERE idVehicle = :idVehicle");
        $req->bindParam(':idVehicle',$_GET['idVehicle'], PDO::PARAM_INT);
        $req->execute();

        vehicleList();
    }



    /* Update user password */
    function updatePassword()
    {
        if(!isset($_POST['currentPassword']) || !isset($_POST['newPassword']) || !isset($_POST['confirmPassword']))
        {
            header("Location: ./../en/profile");
            exit();
        }

        $req = setupCredentials()->prepare("SELECT idUser FROM USERS WHERE idUser=:idUser AND password=:password");

        $pwd = hash('sha256', trim(htmlspecialchars($_POST['currentPassword'])));
        $req->bindParam(':idUser', $_SESSION['idUser'], PDO::PARAM_INT);
        $req->bindParam(':password', $pwd , PDO::PARAM_STR);
        $req->execute();

        $res = $req->fetchAll(\PDO::FETCH_ASSOC);

        if (!$res)
        {
            header('Location: ./../en/profile');
            exit();
        }

        if (strcmp($_POST['newPassword'], $_POST['confirmPassword'])) {
            header('Location: ./../en/profile');
            exit();
        }

        $req = setupCredentials()->prepare("UPDATE USERS SET password=:password WHERE idUser=:idUser");

        $pwd = hash('sha256', trim(htmlspecialchars($_POST['newPassword'])));
        $req->bindParam(':password', $pwd, PDO::PARAM_STR);
        $req->bindParam(':idUser', $_SESSION['idUser'], PDO::PARAM_INT);
        $req->execute();

        header("Location: ./../en/profile");
    }



    /* Check user credentials and connect to his session on the application*/
    function appConnection()
    {
        $req = setupCredentials()->prepare("SELECT idUser FROM USERS WHERE email=? AND password=?");
        $req->execute([
            $_POST['email'],
            hash('sha256',$_POST['password'])
        ]);

        $res=$req->fetchAll(\PDO::FETCH_ASSOC);

        if ($res)
        {
            $req = setupCredentials()->prepare("UPDATE USERS SET appSignIn=1 WHERE email=:email");

            $req->bindParam(':email', trim(htmlspecialchars($_POST['email'])), PDO::PARAM_STR);
            $req->execute();

            header('Location: ./../en/appConnection');
            exit();
        }
        else
        {
            header('Location: ./../en/appConnection');
            exit();
        }
    }

    function showPackage(){
        include_once("./configLanguage.php");

        $customerHistoryTextLoad = loadCustomerHistoryText();

        $req = setupCredentials()->prepare("SELECT idPackage,weight,volumeSize,emailDest,Status,deliveryStatus FROM PACKAGES INNER JOIN `ORDER` ON PACKAGES.idOrder = `ORDER`.idOrder WHERE PACKAGES.idOrder = ?");
        $req->execute([
            $_GET['idOrder']
        ]);

        $res=$req->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($res as $key => $value)
        {
            echo "<ul id = 'ulPackage".$value['idPackage']."'>
                <label>" . $customerHistoryTextLoad['package'][$_COOKIE['language']] . ": ".$value['idPackage']."</label>
                <li>" . $customerHistoryTextLoad['weight'][$_COOKIE['language']] . ": ".$value['weight']."g</li>
                <li>" . $customerHistoryTextLoad['volume'][$_COOKIE['language']] . ": ".$value['volumeSize']." cm3</li>
                <li>" . $customerHistoryTextLoad['email'][$_COOKIE['language']] . ": ".$value['emailDest']."</li>
                <li>" . $customerHistoryTextLoad['status'][$_COOKIE['language']] . ": ";
            if ($value['Status']==0)
                 echo $customerHistoryTextLoad['inDeposit'][$_COOKIE['language']];
            elseif ($value['Status']==1)
                 echo $customerHistoryTextLoad['inDelivery'][$_COOKIE['language']];
            elseif ($value['Status']=2)
                 echo $customerHistoryTextLoad['delivered'][$_COOKIE['language']];
            echo "</li>";

            if ($value["deliveryStatus"] == 0)
                echo "<label onclick='deletePackage(".$value['idPackage'].",".$_GET['idOrder'].")'>" . $customerHistoryTextLoad['deletePackage'][$_COOKIE['language']] . "</label>";
            echo "</ul>";
        }
        ?>
        <div id="orderButtonClose<?=$_GET['idOrder']?>" class="buttonPackages" onclick="showPackage(<?=$_GET['idOrder']?>)">
            <a><?= $customerHistoryTextLoad['hideContent'][$_COOKIE['language']] ?></a>
            <svg width="18" height="2" viewBox="0 0 18 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 1H17H1" stroke="#394967" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <?php
    }

    function billInformation($idOrder){
        $requestPackages = setupCredentials()->prepare("SELECT * FROM PACKAGES WHERE idOrder = :idOrder");
        $requestPackages->bindParam(':idOrder', $idOrder, PDO::PARAM_INT);
        $requestPackages->execute();
        $packages = $requestPackages->fetchAll(PDO::FETCH_ASSOC);

        $requestOrder = setupCredentials()->prepare("SELECT deliveryStatus, deliveryType, idUser, creationDate, paymentType, total FROM `ORDER` WHERE idOrder = :idOrder");
        $requestOrder->bindParam(':idOrder', $idOrder, PDO::PARAM_INT);
        $requestOrder->execute();
        $order = $requestOrder->fetch();

        $requestUser = setupCredentials()->prepare("SELECT email, telNumber, companyName, address FROM USERS WHERE idUser = :idUser");
        $requestUser->bindParam(':idUser', $order['idUser'], PDO::PARAM_INT);
        $requestUser->execute();
        $user = $requestUser->fetch();

        $tab = [$packages, $order, $user];
        return $tab;
    }

    function usersList(){
        include_once("./../config/configLanguage.php");

        $dashboardTextLoad = loadDashboardText();

        $query = "SELECT idUser,email,status from USERS";

        if (isset($_GET['userSelect'])){
            $query .= " WHERE status=".$_GET['userSelect'];
        }

        if (isset($_GET['searchUser'])){
            if (isset($_GET['userSelect'])) {
                $query .= ' AND email LIKE "'.$_GET['searchUser'].'%"';
            }else {
                $query .= ' WHERE email LIKE "'.$_GET['searchUser'].'%"';
            }
        }

        $req = setupCredentials()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value){
            echo "<div class='user'>
                    <div class='mailAndType'>
                        <label>".$value['email']."</label>
                        <label>";
                        if ($value['status'] == 0) {
                            echo $dashboardTextLoad['customer'][$_COOKIE['language']];
                        }elseif ($value['status'] == 1) {
                            echo $dashboardTextLoad['deliveryMan'][$_COOKIE['language']];
                        }elseif ($value['status'] == 2) {
                            echo $dashboardTextLoad['admin'][$_COOKIE['language']];
                        }
                echo    "</label>";
                        ?>
                        <div class="userButtonDetails" onclick="showUserDetails(<?=$value['idUser']?>)">
                            <a><?= $dashboardTextLoad['showDetails'][$_COOKIE['language']]; ?></a>
                            <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.5 1V9M9.5 17V9M9.5 9H17.5H1.5" stroke="#394967" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <?php
                echo    "</div>
                      <div id='moreInfoUser".$value['idUser']."' class='moreInfoUser'></div>
                    </div>";
        }
    }

    function showUserDetails(){
        include_once("./../config/configLanguage.php");

        $dashboardTextLoad = loadDashboardText();

        $req = setupCredentials()->prepare("SELECT status FROM USERS WHERE idUser = :idUser");
        $req->bindParam(':idUser', $_GET['idUser'], PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        if ($res[0]['status'] == 0) {
            $req = setupCredentials()->prepare("SELECT companyName,address,telNumber,ORDER.idOrder,ORDER.deliveryStatus,(SELECT COUNT(*) FROM PACKAGES WHERE idOrder=ORDER.idOrder) FROM USERS INNER JOIN `ORDER` ON `ORDER`.idUser=USERS.idUser WHERE USERS.idUser= :idUser");
            $req->bindParam(':idUser', $_GET['idUser'], PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetchAll(PDO::FETCH_ASSOC);

            echo "<ul>
                    <li>" . $dashboardTextLoad['companyName'][$_COOKIE['language']] . ": ".$res[0]['companyName']."</li>
                    <li>" . $dashboardTextLoad['address'][$_COOKIE['language']] . ": ".$res[0]['address']."</li>
                    <li>" . $dashboardTextLoad['phoneNumber'][$_COOKIE['language']] . ": ".$res[0]['telNumber']."</li>
                </ul>";

            echo "<label>" . $dashboardTextLoad['companyOrder'][$_COOKIE['language']] . ":</label>";
            foreach ($res as $key => $value) {
                echo "<div class = 'orderUser'>";
                echo "<label>" . $dashboardTextLoad['id'][$_COOKIE['language']] . ": ".$value['idOrder']."</label>";
                echo "<label>" . $dashboardTextLoad['packageNumber'][$_COOKIE['language']] . ": ".$value['(SELECT COUNT(*) FROM PACKAGES WHERE idOrder=ORDER.idOrder)']."</label>";
                echo "<label status=".$value['deliveryStatus'].">";
                if ($value['deliveryStatus'] == 0) {
                    echo $dashboardTextLoad['waitPayment'][$_COOKIE['language']];
                }else if($value['deliveryStatus'] == 1) {
                    echo $dashboardTextLoad['preparation'][$_COOKIE['language']];
                }else if($value['deliveryStatus'] == 2) {
                    echo $dashboardTextLoad['finish'][$_COOKIE['language']];
                }
                echo "</label>";
                echo "</div>";
            }
            echo "<button type='button' name='deleteUser' onclick='deleteUser(".$_GET['idUser'].")'>" . $dashboardTextLoad['deleteUser'][$_COOKIE['language']] . "</button>";
        }elseif ($res[0]['status'] == 1) {
            $req = setupCredentials()->prepare("SELECT firstName,name,telNumber FROM USERS WHERE idUser = :idUser");
            $req->bindParam(':idUser', $_GET['idUser'], PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetchAll(PDO::FETCH_ASSOC);

            echo "<ul>
                    <li>" . $dashboardTextLoad['firstName'][$_COOKIE['language']] . ": ".$res[0]['firstName']."</li>
                    <li>" . $dashboardTextLoad['lastName'][$_COOKIE['language']] . ": ".$res[0]['name']."</li>
                    <li>" . $dashboardTextLoad['phoneNumber'][$_COOKIE['language']] . ": ".$res[0]['telNumber']."</li>
                </ul>";
            echo "<button type='button' name='deleteUser' onclick='deleteUser(".$_GET['idUser'].")'>" . $dashboardTextLoad['deleteUser'][$_COOKIE['language']] . "</button>";
        }
    }

    function deleteUser(){

        echo "check";
        $req = setupCredentials()->prepare("SELECT idOrder FROM `ORDER` WHERE idUser = :idUser");
        $req->bindParam(':idUser', $_POST['idUser'], PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value) {
            $req = setupCredentials()->prepare("DELETE FROM PACKAGES WHERE idOrder = :idOrder");
            $req->bindParam(':idOrder',$value['idOrder'], PDO::PARAM_INT);
            $req->execute();
        }

        $req = setupCredentials()->prepare("DELETE FROM PACKAGES WHERE idUser = :idUser");
        $req->bindParam(':idUser', $_POST['idUser'], PDO::PARAM_INT);
        $req->execute();

        $req = setupCredentials()->prepare("DELETE FROM `ORDER` WHERE idUser = :idUser");
        $req->bindParam(':idUser', $_POST['idUser'], PDO::PARAM_INT);
        $req->execute();

        $req = setupCredentials()->prepare("DELETE FROM VEHICLE WHERE idUser = :idUser");
        $req->bindParam(':idUser', $_POST['idUser'], PDO::PARAM_INT);
        $req->execute();

        $req = setupCredentials()->prepare("DELETE FROM USERS WHERE idUser = :idUser");
        $req->bindParam(':idUser', $_POST['idUser'], PDO::PARAM_INT);
        $req->execute();
    }

    function ordersList(){
        include_once("./../config/configLanguage.php");

        $dashboardTextLoad = loadDashboardText();

        $query = "SELECT idOrder,deliveryStatus,USERS.email,(SELECT COUNT(*) FROM PACKAGES WHERE idOrder=ORDER.idOrder) FROM `ORDER` INNER JOIN USERS on `ORDER`.idUser = USERS.idUser";

        if (isset($_GET['orderSelect'])) {
            $query .= " WHERE deliveryStatus=".$_GET['orderSelect'];
        }

        if (isset($_GET['searchOrder'])) {
            if (isset($_GET['orderSelect'])) {
                $query .= ' AND idOrder LIKE "'.$_GET['searchOrder'].'%"';
            }else {
                $query .= ' WHERE idOrder LIKE "'.$_GET['searchOrder'].'%"';
            }
        }

        $req = setupCredentials()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value) {
            echo "<div class='order'>
                    <div class='basicOrderInfo'>
                        <label>".$value['idOrder']."</label>
                        <label>";
                        if ($value['deliveryStatus'] == 0) {
                            echo $dashboardTextLoad['waitPayment'][$_COOKIE['language']];
                        }elseif ($value['deliveryStatus'] == 1) {
                            echo $dashboardTextLoad['preparation'][$_COOKIE['language']];
                        }elseif ($value['deliveryStatus'] == 2) {
                            echo $dashboardTextLoad['finish'][$_COOKIE['language']];
                        }
                echo    "</label>";
                echo    "<label>".$value['email']."</label>
                         <label>" . $dashboardTextLoad['package'][$_COOKIE['language']] . ": ".$value['(SELECT COUNT(*) FROM PACKAGES WHERE idOrder=ORDER.idOrder)']."</label>";
                        ?>
                        <div  class="orderButtonDetails" onclick="showOrderDetails(<?=$value['idOrder']?>)">
                            <a><?= $dashboardTextLoad['showDetails'][$_COOKIE['language']]; ?></a>
                            <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.5 1V9M9.5 17V9M9.5 9H17.5H1.5" stroke="#394967" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <?php
                echo    "</div>
                      <div id='moreInfoOrder".$value['idOrder']."' class='moreInfoOrder'></div>
                    </div>";
        }
    }

    function showOrderDetails(){
        include_once("./../config/configLanguage.php");

        $dashboardTextLoad = loadDashboardText();

        $req = setupCredentials()->prepare("SELECT deliveryType,total,creationDate,PACKAGES.idPackage FROM `ORDER` INNER JOIN PACKAGES on `ORDER`.idOrder = PACKAGES.idOrder WHERE `ORDER`.idOrder = :idOrder");
        $req->bindParam(':idOrder', $_GET['idOrder'], PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        $input = $res['creationDate'];
        $date = strtotime($input);

        echo "<ul>
                <li>" . $dashboardTextLoad['creationDate'][$_COOKIE['language']] . ": ".date('d/m/Y', $date)."</li>
                <li>" . $dashboardTextLoad['deliveryType'][$_COOKIE['language']] . ": ";
                if ($res[0]['deliveryType'] == 0) {
                    echo $dashboardTextLoad['standard'][$_COOKIE['language']];
                }else if($res[0]['deliveryType'] == 1){
                    echo $dashboardTextLoad['express'][$_COOKIE['language']];
                }
                echo "</li>
                <li>" . $dashboardTextLoad['total'][$_COOKIE['language']] . ": ".$res[0]['total']."</li>
            </ul>";

        echo "<label>" . $dashboardTextLoad['orderPackage'][$_COOKIE['language']] . ": ";
        foreach ($res as $key => $value) {
            echo $value['idPackage']." ";
        }

        echo "</label>";
        echo "<button type='button' name='deleteOrder' onclick='deleteOrder(".$_GET['idOrder'].")'>" . $dashboardTextLoad['deleteOrder'][$_COOKIE['language']] . "</button>";
    }

    function deleteOrder(){

        $req = setupCredentials()->prepare("DELETE FROM PACKAGES WHERE idOrder = :idOrder");
        $req->bindParam(':idOrder',$_POST['idOrder'], PDO::PARAM_INT);
        $req->execute();

        $req = setupCredentials()->prepare("DELETE FROM `ORDER` WHERE idOrder = :idOrder");
        $req->bindParam(':idOrder',$_POST['idOrder'], PDO::PARAM_INT);
        $req->execute();

    }

    function packagesList(){
        include_once("./../config/configLanguage.php");

        $dashboardTextLoad = loadDashboardText();
        $query = "SELECT idPackage,PACKAGES.status,USERS.companyName,PACKAGES.idOrder FROM PACKAGES INNER JOIN `ORDER` on `ORDER`.idOrder = PACKAGES.idOrder INNER JOIN USERS on `ORDER`.idUser = USERS.idUser";

        if (isset($_GET['packageSelect'])) {
            $query .= " WHERE PACKAGES.status=".$_GET['packageSelect'];
        }

        if (isset($_GET['searchPackage'])) {
            if (isset($_GET['packageSelect'])) {
                $query .= ' AND idPackage LIKE "'.$_GET['searchPackage'].'%"';
            }else {
                $query .= ' WHERE idPackage LIKE "'.$_GET['searchPackage'].'%"';
            }
        }

        $req = setupCredentials()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value) {
            echo "<div class='package'>
                    <div class='basicPackageInfo'>
                        <label>".$value['idPackage']."</label>
                        <label>".$value['companyName']."</label>
                        <label>".$value['idOrder']."</label>
                        <label>" . $dashboardTextLoad['status'][$_COOKIE['language']] . ": ";

                        if ($value['status'] == 0) {
                            echo $dashboardTextLoad['inDeposit'][$_COOKIE['language']];
                        }elseif ($value['status'] == 1) {
                            echo $dashboardTextLoad['inDelivery'][$_COOKIE['language']];
                        }elseif ($value['status'] == 2) {
                            echo $dashboardTextLoad['delivered'][$_COOKIE['language']];
                        }

                        echo "</label>";
                        ?>
                        <div  class="packageButtonDetails" onclick="showPackageDetails(<?=$value['idPackage']?>)">
                            <a><?= $dashboardTextLoad['showDetails'][$_COOKIE['language']] ?></a>
                            <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.5 1V9M9.5 17V9M9.5 9H17.5H1.5" stroke="#394967" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <?php
                echo    "</div>
                      <div id='moreInfoPackage".$value['idPackage']."' class='moreInfoPackage'></div>
                    </div>";
        }

    }

    function showPackageDetails(){
        include_once("./../config/configLanguage.php");

        $dashboardTextLoad = loadDashboardText();

        $req = setupCredentials()->prepare("SELECT weight,volumeSize,emailDest,address,city FROM PACKAGES WHERE idPackage=:idPackage");
        $req->bindParam(':idPackage', $_GET['idPackage'], PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        $req = setupCredentials()->prepare("SELECT DEPOSITS.address,DEPOSITS.city FROM PACKAGES INNER JOIN DEPOSITS ON PACKAGES.idDeposit = DEPOSITS.idDeposit WHERE idPackage=:idPackage");
        $req->bindParam(':idPackage', $_GET['idPackage'], PDO::PARAM_INT);
        $req->execute();
        $res2 = $req->fetchAll(PDO::FETCH_ASSOC);

        echo "<ul>
                <li>" . $dashboardTextLoad['weight'][$_COOKIE['language']] . ": ".$res[0]['weight']." kg</li>
                <li>" . $dashboardTextLoad['volume'][$_COOKIE['language']] . ": ".$res[0]['volumeSize']."</li>
                <li>" . $dashboardTextLoad['emailDest'][$_COOKIE['language']] . ": ".$res[0]['emailDest']."</li>
                <li>" . $dashboardTextLoad['addressDest'][$_COOKIE['language']] . ": ".$res[0]['address']."</li>
                <li>" . $dashboardTextLoad['cityDest'][$_COOKIE['language']] . ": ".$res[0]['city']."</li>
                <li>" . $dashboardTextLoad['depositAddress'][$_COOKIE['language']] . ": ".$res2[0]['address']."</li>
                <li>" . $dashboardTextLoad['depositCity'][$_COOKIE['language']] . ": ".$res2[0]['city']."</li>
            </ul>";

        echo "<button type='button' name='deletePackage' onclick='deletePackage(".$_GET['idPackage'].")'>" . $dashboardTextLoad['deletePackage'][$_COOKIE['language']] . "</button>";
    }

    function deletePackage(){

        $req = setupCredentials()->prepare("SELECT price,total,PACKAGES.idOrder FROM PACKAGES INNER JOIN `ORDER` on PACKAGES.idOrder = `ORDER`.idOrder WHERE idPackage= :idPackage");
        $req->bindParam(':idPackage', $_POST['idPackage'], PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        $newTotal = $res[0]['total']-$res[0]['price'];

        $req = setupCredentials()->prepare("UPDATE `ORDER` SET total = :newTotal WHERE idOrder = :idOrder");
        $req->bindParam(':newTotal',$newTotal, PDO::PARAM_STR);
        $req->bindParam(':idOrder',$res[0]['idOrder'], PDO::PARAM_INT);
        $req->execute();

        $req = setupCredentials()->prepare("DELETE FROM PACKAGES WHERE idPackage = :idPackage");
        $req->bindParam(':idPackage',$_POST['idPackage'], PDO::PARAM_INT);
        $req->execute();

    }

    function checkOrderPayment($idOrder){
        $requestOrder = setupCredentials()->prepare("SELECT deliveryStatus FROM `ORDER` WHERE idOrder = :idOrder");
        $requestOrder->bindParam(':idOrder', $idOrder, PDO::PARAM_INT);
        $requestOrder->execute();
        $deliveryStatus = $requestOrder->fetch();
        return $deliveryStatus;
    }

    function addAdmin(){

        $req = setupCredentials()->prepare("SELECT email FROM USERS WHERE email=?");
        $req->execute([
            $_POST['adminEmail'],
        ]);

        $res=$req->fetchAll(\PDO::FETCH_ASSOC);

        if ($res)
        {
            header('Location: ./../dashBoard');
            exit();
        }

        if (strlen($_POST['adminPassword'])<8) {
            header('Location: ./../dashBoard');
            exit();
        }

        if (strcmp($_POST['adminPassword'],$_POST['adminConfirmPassword'])!=0) {
            header('Location: ./../en/dashBoard');
            exit();
        }

        $status = 2;

        $req = setupCredentials()->prepare("INSERT INTO USERS(email,password,status) VALUES (:email, :password, :status)");
        $req->bindParam(':email', trim(htmlspecialchars($_POST['adminEmail'])), PDO::PARAM_STR);
        $req->bindParam(':password', hash('sha256', trim(htmlspecialchars($_POST['adminPassword']))), PDO::PARAM_STR);
        $req->bindParam(':status', $status, PDO::PARAM_INT);
        $req->execute();

        header('Location: ./../en/dashBoard');

    }



    function getPackage($idArray)
    {
        $packageArray = [];

        for ($i = 0; $i < count($idArray); $i++)
        {
            $query = "SELECT address, postalCode, city FROM PACKAGES WHERE idPackage = ?";

            $req = setupCredentials()->prepare($query);
            $req->execute([$idArray[$i]]);

            array_push($packageArray, $req->fetch(\PDO::FETCH_ASSOC));
        }

        return $packageArray;
    }



    function buildUrlApi($packageArray)
    {
        $iPackageArray = 1;
        $duplicatePackageArray = [$packageArray[0]['address']];
        // echo $iPackageArray . "=>" . $packageArray[0]['address'] . "<br />";

        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=" . $packageArray[0]['address'] . ",+" . $packageArray[0]['postalCode'] . "+" . $packageArray[0]['city'];

        while(in_array($packageArray[$iPackageArray]['address'], $duplicatePackageArray))
            $iPackageArray++;

        $url .= "&destination=" . $packageArray[$iPackageArray]['address'] . ",+" . $packageArray[$iPackageArray]['postalCode'] . "+" . $packageArray[$iPackageArray]['city'];
        $duplicatePackageArray = [$packageArray[$iPackageArray]['address']];
        // echo $iPackageArray . "=>" . $packageArray[$iPackageArray]['address'] . "<br />";
        $iPackageArray++;

        if(count($packageArray) > 2 && $iPackageArray < count($packageArray))
        {
            $url .= "&waypoints=optimize:true|";
            // echo "i =>" . $iPackageArray . "<br />";
            // echo "count =>" . (count($packageArray) - 1) . "<br />";
            for ($i = $iPackageArray; $i < count($packageArray) - 1; $i++)
            {
                if (!in_array($packageArray[$i]['address'], $duplicatePackageArray))
                {
                    $url .= $packageArray[$i]['address'] . ",+" . $packageArray[$i]['postalCode'] . "+" . $packageArray[$i]['city'] . "|";
                    $duplicatePackageArray = [$packageArray[$i]['address']];
                    // echo $iPackageArray . "=>" . $packageArray[$i]['address'] . "<br />";
                }
            }

            if (!in_array($packageArray[count($packageArray) - 1]['address'], $duplicatePackageArray))
            {
                $url .= $packageArray[count($packageArray) - 1]['address'] . ",+" . $packageArray[count($packageArray) - 1]['postalCode'] . "+" . $packageArray[count($packageArray) - 1]['city'];
                // echo count($packageArray) - 1 . "=>" . $packageArray[count($packageArray) - 1]['address'] . "<br />";
            }
        }

        $url .= '&units=metric&key=AIzaSyCGojCmtnMT6-hBz-vUJ6eRrfIX_cjpERE';
        $url = str_replace(' ', '+', $url);

        return $url;
    }



    function getPaymentList()
    {
        $req = setupCredentials()->prepare("SELECT idRemuneration, firstName, name, amountTotal FROM USERS INNER JOIN REMUNERATION
            ON USERS.idUser = REMUNERATION.idUser WHERE isPayed = 0");
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        return $res;
    }



    function changeStatePayment($idRemuneration)
    {
        $req = setupCredentials()->prepare("UPDATE REMUNERATION SET isPayed = 1 WHERE idRemuneration = :idRemuneration");
        $req->execute([
            'idRemuneration' => $idRemuneration
        ]);

        return;
    }
?>
