<?php
	if(!isset($_SESSION)){
		session_start();
	}
	
	if(!isset($_SESSION['idUser']) || !isset($_SESSION['status'])){
		session_unset();
		header("location: ./connection");
	}
	
	include_once("./../config/config.php");
	
	$req = setupCredentials()->prepare("SELECT idRoadMap,status FROM ROADMAP WHERE idUser=:idUser");
	$req->bindParam(':idUser', htmlspecialchars($_SESSION['idUser']), PDO::PARAM_INT);
	$req->execute();
	$res = $req->fetchAll(\PDO::FETCH_ASSOC);
	
	$trigger = 0;
	$idRoadMap = 0;
	foreach($res as $key => $value){
		if($value['status'] == 0){
			$trigger = 1;
			$idRoadMap = $value['idRoadMap'];
		}elseif($value['status'] == 1){
			$trigger = 2;
			$idRoadMap = $value['idRoadMap'];
		}elseif($value['status'] == 2){
			$trigger = 3;
			$idRoadMap = $value['idRoadMap'];
		}
	}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<?php include("./php/head.php"); ?>
		<title>LTA-development</title>
		<script src="./../js/apiOneSignal/setExternalUserIdMethod.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyCGojCmtnMT6-hBz-vUJ6eRrfIX_cjpERE"></script>
		<link rel="stylesheet" type="text/css" href="./../css/newDelivery.css">
		<script type="text/javascript" src="./../js/map.js"></script>
	</head>
	
	<body class="preload">
		<main>
            <div class="container">

                <div class="preparationAndRoadMap">

                    <div class="preparation" id="preparation">
                        <label for="selectVehicle">Select your vehicle for this delivery:</label>
                        <select class="select" id="selectVehicle">
                            <?php
                            $req = setupCredentials()->prepare("SELECT idVehicle,registration,volumeSize FROM VEHICLE WHERE idUser = :idUser");
                            $req->bindParam(':idUser', $_SESSION['idUser'], PDO::PARAM_INT);
                            $req->execute();
                            $res = $req->fetchAll(PDO::FETCH_ASSOC);

                            foreach($res as $key => $value){
                                ?>

                                <option value=<?php echo $value['idVehicle'] ?>><?php echo $value['registration'] . " volume size: " . $value['volumeSize'] ?></option>

                            <?php } ?>
                        </select>
                        <label onclick="setUpMap(<?php echo $_SESSION['idUser'] ?>)">Generate my roadmap</label>
                        <small>If you click on this button, you agree to finish the delivery</small>
                    </div>
                    <div class="mapAndChoice" id="mapAndChoice">

                        <div class="map" id="map"></div>
                        <div class="choice" id="choice">

                        </div>
                    </div>
                </div>
            </div>
		</main>
		<script>
			if(<?= $trigger ?> === 1){
				getPolyline(<?= $idRoadMap ?>);
				choiceButton(<?= $idRoadMap ?>)
			}else if(<?= $trigger ?> === 2){
				getPolyline(<?= $idRoadMap ?>);
				acceptDelivery(<?= $idRoadMap ?>);
			}else if(<?= $trigger ?> === 3){
				getPolyline(<?= $idRoadMap ?>);
				checkAllPackagesInVehicle(<?= $idRoadMap ?>);
			}
		</script>
	</body>

</html>
