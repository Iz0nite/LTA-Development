<?php
    include_once("./../config/config.php");
    include_once("./php/testDelivery.php");

    $idRoadMap = newDelivery($_GET['idUser'],$_GET['idVehicle']);

    $req = setupCredentials()->prepare("SELECT polyline FROM ROADMAP WHERE idRoadMap = :idRoadMap");
    $req->bindParam(':idRoadMap', $idRoadMap, PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetch();

    $polylinePoint = $res['polyline'];
    // var_dump($polylinePoint);

    //$polylinePoint = $polylinePoint['routes'][0]['overview_polyline']['points'];

    $polylinePoint = json_encode($polylinePoint);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <script src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyCGojCmtnMT6-hBz-vUJ6eRrfIX_cjpERE"></script>
        <script type="text/javascript" src="./../js/map.js"></script>
        <link rel="stylesheet" href="./../css/map.css">
    </head>

    <body>
        <div class="mapContainer">
            <div id="map"></div>
        </div>

        <script type="text/javascript">
            initialize(<?php echo $polylinePoint ?>);
        </script>
    </body>
</html>
