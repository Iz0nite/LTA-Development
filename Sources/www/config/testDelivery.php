<?php

include_once("./config.php");
// include("/var/www/html/config/config.php");

/*$curl = curl_init();
if ($curl) {
    curl_setopt($curl, CURLOPT_URL, "https://maps.googleapis.com/maps/api/directions/json?origin=Disneyland&destination=Universal+Studios+Hollywood&units=metric&key=AIzaSyCGojCmtnMT6-hBz-vUJ6eRrfIX_cjpERE");

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($curl);

    $result = json_decode($response, true);

    echo $result['routes'][0]['legs'][0]['distance']['value'];
    // var_dump($result['routes']['legs']);

}else{
    echo "pas bon";
}*/
if (isset($_POST['formType'])){
    if ($_POST['formType'] == "refuseDelivery"){
        refuseDelivery($_POST['idRoadMap']);
    }
}

if (isset($_GET['formType'])){
    if ($_GET['formType'] == "newDelivery"){
        newDelivery($_GET['idUser'],$_GET['idVehicle']);
    }elseif ($_GET['formType'] == "getPolyline"){
        getPolyline($_GET['idRoadMap']);
    }elseif ($_GET['formType'] == "choiceButton"){
        choiceButton($_GET['idRoadMap']);
    }elseif ($_GET['formType'] == "acceptDelivery"){
        acceptDelivery($_GET['idRoadMap']);
    }elseif ($_GET['formType'] == "inVehicle"){
        checkPickUp($_GET['idPackage'],1);
    }elseif ($_GET['formType'] == "outOfVehicle"){
        checkPickUp($_GET['idPackage'],0);
    }elseif ($_GET['formType'] == "checkAllPackagesInVehicle"){
        checkAllPackagesInVehicle($_GET['idRoadMap']);
    }elseif ($_GET['formType'] == "deliver"){
        checkDeliver($_GET['idPackage'],2,0);
    }elseif ($_GET['formType'] == "notDeliver"){
        checkDeliver($_GET['idPackage'],1,1);
    }elseif ($_GET['formType'] == "checkFinishDelivery"){
        checkFinishDelivery($_GET['idRoadMap']);
    }
}

function newDelivery($idUser,$idVehicle){

    $bdd = setupCredentials();
    $req = $bdd->prepare("INSERT INTO ROADMAP (idUser) VALUES (:idUser)");
    $req->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $req->execute();

    $idRoadMap = $bdd->lastInsertId();

    $req = $bdd->prepare("SELECT geoArea, DEPOSITS.idDeposit, DEPOSITS.address, volumeSize from USERS INNER JOIN DEPOSITS ON USERS.idDeposit = DEPOSITS.idDeposit INNER JOIN VEHICLE on USERS.idUser = VEHICLE.idUser WHERE USERS.idUser = :idUser AND VEHICLE.idVehicle = :idVehicle ");
    $req->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $req->bindParam(':idVehicle', $idVehicle, PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetch();

    // var_dump($res);

    $idList = fillUp($res['geoArea'], $res['volumeSize'], $res['idDeposit'], $res['address'],$idRoadMap);

    // var_dump($idList);

    if (count($idList) === 0){

        $req = $bdd->prepare("DELETE FROM ROADMAP WHERE idRoadMap = :idRoadMap");
        $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
        $req->execute();

        echo "Sorry no route could be found. Retry later.";
        return ;
    }

    $addressList = setTabRoutes($idList);

    // echo "<br>";

    // var_dump($addressList);

    $routes = getRoutes($addressList,$res['address']);

    $polyline = $routes['routes'][0]['overview_polyline']['points'];

    $distance = 0;

    for ($i = 0;$i < count($addressList); $i++){

        $distance += $routes['routes'][0]['legs'][$i]['distance']['value'];
    }
    // var_dump($routes);
    // echo $routes['routes'][0]['overview_polyline']['points'];

    $req = $bdd->prepare("UPDATE ROADMAP SET polyline = :polyline, distance = :distance WHERE idRoadMap = :idRoadMap");
    $req->bindParam(':polyline', $polyline, PDO::PARAM_STR);
    $req->bindParam(':distance', $distance, PDO::PARAM_INT);
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();

    echo $idRoadMap;
}

function fillUp(int $geoArea,float $volumeSize,int $idDeposit,string $depositAddress, int $idRoadMap): ?array{

    $remainingVolume = $volumeSize * 100;
    $inMeterGeoArea = $geoArea*1000;

    $bdd = setupCredentials();
    $req = $bdd->prepare("SELECT idPackage,volumeSize,address,city,postalCode,deliveryType,creationDate,`ORDER`.idOrder FROM PACKAGES INNER JOIN `ORDER` on PACKAGES.idOrder = `ORDER`.idOrder WHERE idDeposit = :idDeposit AND status = 0 AND deliveryStatus = 1 order by creationDate, deliveryType DESC");
    $req->bindParam(':idDeposit', $idDeposit, PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetchAll(PDO::FETCH_ASSOC);

    // echo "<pre>";
    // print_r($res);
    // echo "</pre>";

    $idPackageList = [];
    $addressPackageList = [];

    $urlDepositAddress = str_replace(" ", "+", $depositAddress);


    foreach ($res as $key => $value) {
        $urlApi = "https://maps.googleapis.com/maps/api/directions/json?origin=" . $urlDepositAddress . "&destination=";
        if ($remainingVolume > 0) {
            if (count($addressPackageList)<10) {
                if ($value['volumeSize'] <= $remainingVolume) {

                    $urlPackageAddress = str_replace(" ", "+", $value['address']);
                    $urlApi .= $urlPackageAddress . ",+" . $value['postalCode'] . "+" . $value['city'] . "&units=metric&key=AIzaSyCGojCmtnMT6-hBz-vUJ6eRrfIX_cjpERE";
                    // echo $value['address'] . "<br>";
                    if (getDistanceDeposit($inMeterGeoArea, $urlApi)) {
                        // echo $urlApi . "<br>";
                        array_push($idPackageList, $value['idPackage']);
                        // echo "id: " . $value['idPackage'] . "<br>";
                        $remainingVolume -= $value['volumeSize'];

                        if (in_array($value['address'],$addressPackageList) === false){
                            array_push($addressPackageList,$value['address']);
                        }

                        $idPackage = $value['idPackage'];

                        $req = $bdd->prepare("UPDATE PACKAGES SET idRoadMap = :idRoadMap, status = 1 WHERE idPackage = :idPackage");
                        $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
                        $req->bindParam(':idPackage', $idPackage, PDO::PARAM_INT);
                        $req->execute();
                    }
                }
            }else{
                return $idPackageList;
            }
        } else {
            return $idPackageList;
        }

        // echo $remainingVolume . "<br>";
    }


    return $idPackageList;
}

function getDistanceDeposit(int $inMeterGeoArea,string $urlApi): bool{
    $curl = curl_init();
    if ($curl) {
        curl_setopt($curl, CURLOPT_URL, $urlApi);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        $result = json_decode($response, true);

        $distance = $result['routes'][0]['legs'][0]['distance']['value'];
        /*echo $urlApi . "<br>";
        echo $inMeterGeoArea. "<br>";
        echo $distance. "<br>";*/
        if ($distance<=$inMeterGeoArea){
            return true;
        }else{
            return false;
        }
        // var_dump($result['routes']['legs']);
    }else{
     echo "pas bon";
     return false;
    }
}

function setTabRoutes(array $idList): array{

    $addressList = [];
    $bdd = setupCredentials();
    for ($i=0; $i<count($idList);$i++){
        //echo $idList[$i];
        $req = $bdd->prepare("SELECT address,city,postalCode FROM PACKAGES WHERE idPackage=:idPackage");
        $req->bindParam(':idPackage', $idList[$i], PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        //echo $res[0]['address'];
        $urlAddress = str_replace(" ", "+", $res[0]['address']);
        $urlPackageAddress = $urlAddress . ",+" . $res[0]['postalCode'] . "+" . $res[0]['city'];

        if (in_array($urlPackageAddress,$addressList) === false){
            //echo $urlPackageAddress;
            array_push($addressList,$urlPackageAddress);
        }
    }

    return $addressList;
}

function getRoutes(array $addressList,string $depositAddress): array{
    $urlDepositAddress = str_replace(" ", "+", $depositAddress);

    $urlApi = "https://maps.googleapis.com/maps/api/directions/json?origin=" . $urlDepositAddress . "&destination=".$urlDepositAddress."&waypoints=optimize:true" ;

    for ($i=0; $i<count($addressList);$i++){
        $urlApi.= "|".$addressList[$i];
    }

    $urlApi.= "&units=metric&key=AIzaSyCGojCmtnMT6-hBz-vUJ6eRrfIX_cjpERE";

    //echo $urlApi;

    $curl = curl_init();
    if ($curl) {
        curl_setopt($curl, CURLOPT_URL, $urlApi);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        return json_decode($response, true);

    }else{
        echo "pas bon";
    }

}

function getPolyline($idRoadMap){

    $req = setupCredentials()->prepare("SELECT polyline FROM ROADMAP WHERE idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetch();

    $polylinePoint = $res['polyline'];

    //$polylinePoint = json_encode($polylinePoint);

    printf($polylinePoint);
}

function choiceButton($idRoadMap){
    ?>

    <label onclick="acceptDelivery(<?php echo $idRoadMap ?>)">Accept this delivery</label>
    <form action="./../config/testDelivery" method="post">
        <input type='hidden' name='formType' value='refuseDelivery'>
        <input type='hidden' name='idRoadMap' value=<?php echo $idRoadMap ?>>
        <input type='submit' value='Refuse this delivery'>
    </form>

<?php
}

function refuseDelivery($idRoadMap){

    $bdd = setupCredentials();
    $req = $bdd->prepare("UPDATE PACKAGES SET status = 0, idRoadmap=NULL WHERE idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();

    $req = $bdd->prepare("DELETE FROM ROADMAP WHERE idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();

    header("Location: ./../home");

}

function acceptDelivery($idRoadMap){

    $bdd = setupCredentials();
    $req = $bdd->prepare("UPDATE ROADMAP SET status = 1  WHERE idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();

    $req = $bdd->prepare("SELECT idPackage,address,city,inVehicle FROM PACKAGES WHERE idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetchAll(PDO::FETCH_ASSOC);

    foreach ($res as $key => $value){   ?>

        <div class="packageToDeliver">
            <div class="infoPackage">
                <label><?php echo $value['idPackage'] ?></label>
                <label>Address: <?php echo $value['address'] ?></label>
                <label>City: <?php echo $value['city'] ?></label>
            </div>
            <div class="check" id="checkDiv">
                <label for="check<?php echo $value['idPackage'];  ?>">Check when you pick up the package</label>
                <input id="check<?php echo $value['idPackage'];  ?>" type="checkbox" <?php if ($value['inVehicle'] == 1) echo "checked"; ?> onchange="checkPickUp(<?php echo $value['idPackage'] ?>)">
            </div>
        </div>



    <?php } ?>

        <label onclick='checkAllPackagesInVehicle(<?php echo $idRoadMap ?>)'>Start delivery</label>

    <?php

}

function checkPickUp($idPackage,$inVehicleStatus){

    $bdd = setupCredentials();
    $req = $bdd->prepare("UPDATE PACKAGES SET inVehicle = :inVehicleStatus  WHERE idPackage = :idPackage");
    $req->bindParam(':inVehicleStatus', $inVehicleStatus, PDO::PARAM_INT);
    $req->bindParam(':idPackage', $idPackage, PDO::PARAM_INT);
    $req->execute();

}

function checkAllPackagesInVehicle($idRoadMap){

    $bdd = setupCredentials();
    $req = $bdd->prepare("SELECT COUNT(*) FROM PACKAGES WHERE idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetch();

    $req = $bdd->prepare("SELECT COUNT(*) FROM PACKAGES WHERE inVehicle = 1 AND idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();
    $res2 = $req->fetch();

    $req = $bdd->prepare("SELECT status FROM ROADMAP WHERE idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();
    $res3 = $req->fetch();



    if ($res['COUNT(*)'] === $res2['COUNT(*)'] || $res3['status'] == 2){

        startDelivery($idRoadMap);
    }


}

function startDelivery($idRoadMap) {

    $bdd = setupCredentials();
    $req = $bdd->prepare("UPDATE ROADMAP SET status = 2 WHERE idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();

    $req = $bdd->prepare("SELECT idPackage,address,city,status FROM PACKAGES WHERE idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetchAll(PDO::FETCH_ASSOC);

    foreach ($res as $key => $value){   ?>

        <div class="packageToDeliver">
            <div class="infoPackage">
                <label><?php echo $value['idPackage'] ?></label>
                <label>Address: <?php echo $value['address'] ?></label>
                <label>City: <?php echo $value['city'] ?></label>
            </div>
            <div class="check" id="checkDiv">
                <label for="check<?php echo $value['idPackage'];  ?>">Check when you deliver the package</label>
                <input id="check<?php echo $value['idPackage'];  ?>" type="checkbox" <?php if ($value['status'] == 2) echo "checked"; ?> onchange="checkDeliver(<?php echo $value['idPackage'] ?>)">
            </div>
        </div>



    <?php } ?>

    <label onclick='checkFinishDelivery(<?php echo $idRoadMap ?>)'>Finish delivery</label>

    <?php

}

function checkDeliver($idPackage,$packageStatus,$inVehicleStatus){

    $bdd = setupCredentials();
    $req = $bdd->prepare("UPDATE PACKAGES SET status = :status,inVehicle = :inVehicleStatus  WHERE idPackage = :idPackage");
    $req->bindParam(':status', $packageStatus, PDO::PARAM_INT);
    $req->bindParam(':inVehicleStatus', $inVehicleStatus, PDO::PARAM_INT);
    $req->bindParam(':idPackage', $idPackage, PDO::PARAM_INT);
    $req->execute();

}

function checkFinishDelivery($idRoadMap){

    $bdd = setupCredentials();
    $req = $bdd->prepare("SELECT COUNT(*) FROM PACKAGES WHERE idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetch();

    $req = $bdd->prepare("SELECT COUNT(*) FROM PACKAGES WHERE status = 2 AND idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();
    $res2 = $req->fetch();

    if ($res['COUNT(*)'] === $res2['COUNT(*)']){

        finishDelivery($idRoadMap);
    }


}

function finishDelivery($idRoadMap){

    $bdd = setupCredentials();
    $req = $bdd->prepare("UPDATE ROADMAP SET status = 3  WHERE idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();

    echo "You have finish your delivery !";

    checkFinishOrder($idRoadMap);
}

function checkFinishOrder($idRoadMap){

    $bdd = setupCredentials();
    $req = $bdd->prepare("SELECT idOrder FROM PACKAGES WHERE idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetchAll(PDO::FETCH_ASSOC);

    $alreadyCheck = [];

    foreach ($res as $key => $value){
        if (in_array($value['idOrder'],$alreadyCheck) === false){
            array_push($alreadyCheck,$value['idOrder']);

            $req = $bdd->prepare("SELECT COUNT(*) FROM PACKAGES WHERE idOrder = :idOrder");
            $req->bindParam(':idOrder', $value['idOrder'], PDO::PARAM_INT);
            $req->execute();
            $res = $req->fetch();

            $req = $bdd->prepare("SELECT COUNT(*) FROM PACKAGES WHERE idOrder = :idOrder AND status = 2");
            $req->bindParam(':idOrder', $value['idOrder'], PDO::PARAM_INT);
            $req->execute();
            $res2 = $req->fetch();

            if ($res['COUNT(*)'] === $res2['COUNT(*)']){

                $req = $bdd->prepare("UPDATE `ORDER` SET deliveryStatus = 2 WHERE idOrder = :idOrder");
                $req->bindParam(':idOrder', $value['idOrder'], PDO::PARAM_INT);
                $req->execute();

                $req = $bdd->prepare("SELECT idUser FROM `ORDER` WHERE idOrder = :idOrder");
                $req->bindParam(':idOrder', $value['idOrder'], PDO::PARAM_INT);
                $req->execute();
                $res2 = $req->fetch();

                sendNotif($value['idOrder'], $res2['idUser']);

            }
        }
    }
}

function sendNotif(int $idOrder, int $idUser){
    $response = sendMessage($idOrder, $idUser);
    $return["allresponses"] = $response;
    $return = json_encode( $return);

    //print("\n\nJSON received:\n");
    //print($return);
    //print("\n");
}

function sendMessage(int $idOrder, int $idUser){
    $message = 'All packages of your order number: ' . $idOrder . ' have been delivered';
    $content = array(
        "en" => $message
    );

    $fields = array(
        'app_id' => "304775f8-d0e7-4491-925d-cbd0110c11b5",
        'filters' => array(array("field" => "tag", "key" => "idUser", "relation" => "=", "value" => $idUser)),
        'data' => array("foo" => "bar"),
        'contents' => $content
    );

    $fields = json_encode($fields);
    //print("\nJSON sent:\n");
    //print($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
        'Authorization: Basic NjBhMTliODgtNjQzOC00OTRiLWJjZjctZmMyMDI2YWE3NDVl'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
