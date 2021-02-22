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
        }
    }

    /* Setup SQL connexion */
    function setupCredentials()
    {
        try
        {
            $bdd = new PDO('mysql:dbname=LTA;host=localhost;port=3306;charset=utf8', 'ltaSuperUser', 'n73r96uxZbfC', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
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

        header('location: ./../en/createPackage');
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
            header('location: ./../connection');
            exit();
        }

        if (strlen($_POST['password'])<8) {
            header('location: ./../connection');
            exit();
        }

        if (strcmp($_POST['password'],$_POST['confirmPassword'])!=0) {
            header('location: ./../en/connection');
            exit();
        }

        $status = 0;

        $req = setupCredentials()->prepare("INSERT INTO USERS(companyName,email,address,telNumber,password,status) VALUES (:companyName, :email, :address, :telNumber, :password, :status)");
        $req->bindParam(':companyName', trim(htmlspecialchars($_POST['companyName'])), PDO::PARAM_STR);
        $req->bindParam(':email', trim(htmlspecialchars($_POST['email'])), PDO::PARAM_STR);
        $req->bindParam(':address', trim(htmlspecialchars($_POST['address'])), PDO::PARAM_STR);
        $req->bindParam(':telNumber', trim(htmlspecialchars($_POST['numTel'])), PDO::PARAM_STR);
        $req->bindParam(':password', hash('sha256', trim(htmlspecialchars($_POST['password']))), PDO::PARAM_STR);
        $req->bindParam(':status', $status, PDO::PARAM_INT);
        $req->execute();

        header('location: ./../en/connection');
    }



    /* Add a new elivery man to the database */
    function signUpDelivery()
    {
        $req = setupCredentials()->prepare("SELECT email FROM USERS WHERE email=?");
        $req->execute([
            $_POST['email'],
        ]);

        $res=$req->fetchAll(\PDO::FETCH_ASSOC);

        if ($res)
        {
            header('location: ./../en/connection');
            exit();
        }

        if (strlen($_POST['password'])<8) {
            header('location: ./../en/connection');
            exit();
        }

        if (strcmp($_POST['password'],$_POST['confirmPassword'])!=0) {
            header('location: ./../en/connection');
            exit();
        }

        $status = 1;

        $req = setupCredentials()->prepare("INSERT INTO USERS(firstName,name,email,telNumber,password,vehicleVolume,imatriculation,geoArea,status) VALUES (:firstName, :name, :email, :telNumber, :password, :vehicleVolume, :imatriculation, :geoArea, :status)");
        $req->bindParam(':firstName', trim(htmlspecialchars($_POST['firstName'])), PDO::PARAM_STR);
        $req->bindParam(':name', trim(htmlspecialchars($_POST['name'])), PDO::PARAM_STR);
        $req->bindParam(':email', trim(htmlspecialchars($_POST['email'])), PDO::PARAM_STR);
        $req->bindParam(':telNumber', trim(htmlspecialchars($_POST['numTel'])), PDO::PARAM_STR);
        $req->bindParam(':password', hash('sha256', trim(htmlspecialchars($_POST['password']))), PDO::PARAM_STR);
        $req->bindParam(':vehicleVolume', intval(trim(htmlspecialchars($_POST['vehicleVolume']))), PDO::PARAM_INT);
        $req->bindParam(':imatriculation', trim(htmlspecialchars($_POST['imatriculation'])), PDO::PARAM_STR);
        $req->bindParam(':geoArea', intval(trim(htmlspecialchars($_POST['geoArea']))), PDO::PARAM_INT);
        $req->bindParam(':status', $status, PDO::PARAM_INT);
        $req->execute();

        header('location: ./../en/connection');
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

        if ($res)
        {
            $_SESSION['idUser'] = $res[0]['idUser'];
            $_SESSION['status'] = $res[0]['status'];

            if ($_SESSION['status'] == 2) {
                header('location: ./../en/dashBoard.php');
                exit();
            }

            header('location: ./../en/home');
            exit();
        }
        else
        {
            session_unset();
            header('location: ./../en/connection');
            exit();
        }
    }



    /* Clean all variable session */
    function logOut()
    {
        session_unset();

        header("location: ./../en/connection");
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
                header("location: ./../en/profile");
                exit();
            }

            $req = setupCredentials()->prepare("UPDATE USERS SET address=:address, telNumber=:telNumber, companyName=:companyName, idDeposit=:idDeposit WHERE idUser=:idUser");

            $req->bindParam(':address', trim(htmlspecialchars($_POST['address'])), PDO::PARAM_STR);
            $req->bindParam(':telNumber', trim(htmlspecialchars($_POST['phoneNumber'])), PDO::PARAM_STR);
            $req->bindParam(':companyName', trim(htmlspecialchars($_POST['companyName'])), PDO::PARAM_STR);
            $req->bindParam(':idDeposit', trim(htmlspecialchars($_POST['idDeposit'])), PDO::PARAM_INT);
            $req->bindParam(':idUser', trim(htmlspecialchars($_SESSION['idUser'])), PDO::PARAM_INT);
            $req->execute();

            header("location: ./../en/profile");
        }
    }



    /* Update user password */
    function updatePassword()
    {
        if(!isset($_POST['currentPassword']) || !isset($_POST['newPassword']) || !isset($_POST['confirmPassword']))
        {
            header("location: ./../en/profile");
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
            header('location: ./../en/profile');
            exit();
        }

        if (strcmp($_POST['newPassword'], $_POST['confirmPassword'])) {
            header('location: ./../en/profile');
            exit();
        }

        $req = setupCredentials()->prepare("UPDATE USERS SET password=:password WHERE idUser=:idUser");

        $pwd = hash('sha256', trim(htmlspecialchars($_POST['newPassword'])));
        $req->bindParam(':password', $pwd, PDO::PARAM_STR);
        $req->bindParam(':idUser', $_SESSION['idUser'], PDO::PARAM_INT);
        $req->execute();

        header("location: ./../en/profile");
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

            header('location: ./../en/appConnection');
            exit();
        }
        else
        {
            header('location: ./../en/appConnection');
            exit();
        }
    }

    function showPackage(){
        $req = setupCredentials()->prepare("SELECT idPackage,weight,volumeSize,emailDest,Status FROM PACKAGES WHERE idOrder=?");
        $req->execute([
            $_GET['idOrder']
        ]);

        $res=$req->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($res as $key => $value) {
            echo "<ul>
                    <label>Package: ".$value['idPackage']."</label>
                    <li>Weight: ".$value['weight']."</li>
                    <li>Volume Size: ".$value['volumeSize']." m3</li>
                    <li>Addresse: ".$value['emailDest']."</li>
                    <li>Status: ";
                    if ($value['Status']==0){
                         echo "in deposit";
                    } elseif ($value['Status']==1) {
                         echo "in delivery";
                    }elseif ($value['Status']=2) {
                         echo "delivered";
                    }
                    echo "</li>
                    </ul>";
                }
                ?>
                <div id="orderButtonClose<?=$_GET['idOrder']?>" class="buttonPackages" onclick="showPackage(<?=$_GET['idOrder']?>)">
                    <a>Hide content</a>
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

        $query = "SELECT idUser,email,status from USERS";

        if (isset($_GET['userSelect'])) {
            $query .= " WHERE status=".$_GET['userSelect'];
        }

        if (isset($_GET['searchUser'])) {
            if (isset($_GET['userSelect'])) {
                $query .= ' AND email LIKE "'.$_GET['searchUser'].'%"';
            }else {
                $query .= ' WHERE email LIKE "'.$_GET['searchUser'].'%"';
            }
        }

        $req = setupCredentials()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value) {
            echo "<div class='user'>
                    <div class='mailAndType'>
                        <label>".$value['email']."</label>
                        <label>";
                        if ($value['status'] == 0) {
                            echo "Customers";
                        }elseif ($value['status'] == 1) {
                            echo "string";
                        }elseif ($value['status'] == 2) {
                            echo "admin";
                        }
                echo    "</label>";
                        ?>
                        <div  class="userButtonDetails" onclick="showUserDetails(<?=$value['idUser']?>)">
                            <a>Show details</a>
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
                    <li>Company Name: ".$res[0]['companyName']."</li>
                    <li>Address: ".$res[0]['address']."</li>
                    <li>Number: ".$res[0]['telNumber']."</li>
                </ul>";

            echo "<label>Order of this company:</label>";
            foreach ($res as $key => $value) {
                echo "<div class = 'orderUser'>";
                echo "<label>id: ".$value['idOrder']."</label>";
                echo "<label>Number Packages: ".$value['(SELECT COUNT(*) FROM PACKAGES WHERE idOrder=ORDER.idOrder)']."</label>";
                echo "<label status=".$value['deliveryStatus'].">";
                if ($value['deliveryStatus'] == 0) {
                    echo "Waiting for payment";
                }else if($value['deliveryStatus'] == 1) {
                    echo "In preparation";
                }else if($value['deliveryStatus'] == 2) {
                    echo "Finish";
                }
                echo "</label>";
                echo "</div>";
            }
            echo "<button type='button' name='deleteUser' onclick='deleteUser(".$_GET['idUser'].")'>Delete User</button>";
        }elseif ($res[0]['status'] == 1) {
            $req = setupCredentials()->prepare("SELECT firstName,name,telNumber,address FROM USERS WHERE idUser = :idUser");
            $req->bindParam(':idUser', $_GET['idUser'], PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetchAll(PDO::FETCH_ASSOC);

            echo "<ul>
                    <li>Company Name: ".$res[0]['firstName']."</li>
                    <li>Address: ".$res[0]['name']."</li>
                    <li>Number: ".$res[0]['telNumber']."</li>
                    <li>Number: ".$res[0]['address']."</li>
                </ul>";
            echo "<button type='button' name='deleteUser' onclick='deleteUser(".$_GET['idUser'].")'>Delete User</button>";
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
                            echo "Waiting for Payment";
                        }elseif ($value['deliveryStatus'] == 1) {
                            echo "In preparation";
                        }elseif ($value['deliveryStatus'] == 2) {
                            echo "Finish";
                        }
                echo    "</label>";
                echo    "<label>".$value['email']."</label>
                         <label>Packages: ".$value['(SELECT COUNT(*) FROM PACKAGES WHERE idOrder=ORDER.idOrder)']."</label>";
                        ?>
                        <div  class="orderButtonDetails" onclick="showOrderDetails(<?=$value['idOrder']?>)">
                            <a>Show details</a>
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

        $req = setupCredentials()->prepare("SELECT deliveryType,total,creationDate,PACKAGES.idPackage FROM `ORDER` INNER JOIN PACKAGES on `ORDER`.idOrder = PACKAGES.idOrder WHERE `ORDER`.idOrder = :idOrder");
        $req->bindParam(':idOrder', $_GET['idOrder'], PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        $input = $value['creationDate'];
        $date = strtotime($input);

        echo "<ul>
                <li>Creation date: ".date('d/m/Y', $date)."</li>
                <li>Delivery type: ";
                if ($res[0]['deliveryType'] == 0) {
                    echo "Standard";
                }else if($res[0]['deliveryType'] == 1){
                    echo "Express";
                }
                echo "</li>
                <li>Total: ".$res[0]['total']."</li>
            </ul>";

        echo "<label>Package of this Order: ";
        foreach ($res as $key => $value) {
            echo $value['idPackage']." ";
        }

        echo "</label>";
        echo "<button type='button' name='deleteOrder' onclick='deleteOrder(".$_GET['idOrder'].")'>Delete Order</button>";
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
                        <label>status: ";

                        if ($value['status'] == 0) {
                            echo "In deposit";
                        }elseif ($value['status'] == 1) {
                            echo "In delivery";
                        }elseif ($value['status'] == 2) {
                            echo "Delivered";
                        }

                        echo "</label>";
                        ?>
                        <div  class="packageButtonDetails" onclick="showPackageDetails(<?=$value['idPackage']?>)">
                            <a>Show details</a>
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

        echo $_GET['idPackage'];

        $req = setupCredentials()->prepare("SELECT weight,volumeSize,emailDest,address,city FROM PACKAGES WHERE idPackage=:idPackage");
        $req->bindParam(':idPackage', $_GET['idPackage'], PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        $req = setupCredentials()->prepare("SELECT DEPOSITS.address,DEPOSITS.city FROM PACKAGES INNER JOIN DEPOSITS ON PACKAGES.idDeposit = DEPOSITS.idDeposit WHERE idPackage=:idPackage");
        $req->bindParam(':idPackage', $_GET['idPackage'], PDO::PARAM_INT);
        $req->execute();
        $res2 = $req->fetchAll(PDO::FETCH_ASSOC);

        echo "<ul>
                <li>Weight: ".$res[0]['weight']." kg</li>
                <li>Volume Size: ".$res[0]['volumeSize']."</li>
                <li>Receiver mail: ".$res[0]['emailDest']."</li>
                <li>Receiver address: ".$res[0]['address']."</li>
                <li>Receiver city: ".$res[0]['city']."</li>
                <li>Deposit address: ".$res2[0]['address']."</li>
                <li>Deposit city: ".$res2[0]['city']."</li>
            </ul>";


        echo "<button type='button' name='deletePackage' onclick='deletePackage(".$_GET['idPackage'].")'>Delete Package</button>";

    }

    function deletePackage(){

        $req = setupCredentials()->prepare("DELETE FROM PACKAGES WHERE idPackage = :idPackage");
        $req->bindParam(':idPackage',$_POST['idPackage'], PDO::PARAM_INT);
        $req->execute();

    }


?>
