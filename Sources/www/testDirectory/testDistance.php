<?php
	include_once("./../config/config.php");
	
	/**
	 * @param float $lat1	//Latitude of your first address
	 * @param float $lng1	//Longitude of your first address
	 * @param float $lat2	//Latitude of your second address
	 * @param float $lng2	//Longitude of your second address
	 * @return float		//returns the distance between the two locations
	 */
	function getDistance(float $lat1, float $lng1, float $lat2, float $lng2): float{
		$R = 6371e3; // metres
		$pi = pi();
		
		$phi1 = $lat1 * $pi / 180; // phi, lambda in radians
		$phi2 = $lat2 * $pi / 180;
		$deltaPhi = ($lat2 - $lat1) * $pi / 180;
		$deltaLambda = ($lng2 - $lng1) * $pi / 180;
		
		$a = sin($deltaPhi / 2) * sin($deltaPhi / 2) + cos($phi1) * cos($phi2) * sin($deltaLambda / 2) * sin($deltaLambda / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		
		return $R * $c; //in metres
	}
	
	/**
	 * @param string $address	//Package address
	 * @param string $zipCode	//Package zipCode
	 * @param string $city		//Package city
	 * @return array			//Returns an associative array containing the latitude and the longitude of your package
	 */
	function getGeocode(string $address, string $zipCode, string $city): array{
		
		$address = str_replace(' ', '', $address);
		$query = $address . ',' . $zipCode . $city;
		$query .= '&apiKey=yCsQwOGlTy59bMp4e38Q6ylPsF_Htz-IFEVhGU6nPK0';
		
		$ch = curl_init(sprintf('https://geocode.search.hereapi.com/v1/geocode?q=' . $query));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$json = curl_exec($ch);
		curl_close($ch);
		$apiResult = json_decode($json, true);
		return $location = [
			'lat' => $apiResult['items'][0]['position']['lat'],
			'lng' => $apiResult['items'][0]['position']['lng']
		];
	}
	
	/**
	 * @param int $volumeSize //Define the maximum volume that your delivery can contain
	 * @return array|null		//Returns an array containing the package ids of the delivery
	 */
	function fillUp(int $volumeSize): ?array{
		$remainingVolume = $volumeSize * 100;
		$tabDistance = [];
		$tabVolume = [];
		$idPackageList = [];
		$waypointCounter = 0;
		$tabDistanceCounter = [];
		
		$requestPackage = setupCredentials()->prepare("SELECT idPackage, volumeSize, address, postalCode, city  FROM PACKAGES INNER JOIN `ORDER` on PACKAGES.idOrder = `ORDER`.idOrder WHERE status = 0 AND deliveryStatus = 1");
		$requestPackage->execute();
		$package = $requestPackage->fetch();
		
		$location = getGeocode($package['address'], $package['postalCode'], $package['city']);
		
		$requestPackages = setupCredentials()->prepare("SELECT idPackage, volumeSize, address, city, postalCode FROM PACKAGES INNER JOIN `ORDER` on PACKAGES.idOrder = `ORDER`.idOrder WHERE status = 0 AND deliveryStatus = 1");
		$requestPackages->execute();
		$packagesLocation = $requestPackages->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($packagesLocation as $packageLocation){
			if($package['idPackage'] != $packageLocation['idPackage']){
				$location2 = getGeocode($packageLocation['address'], $packageLocation['postalCode'], $packageLocation['city']);
				$distance = getDistance($location['lat'], $location['lng'], $location2['lat'], $location2['lng']);
				
				$mergingDistance = array('_'.$packageLocation['idPackage'] => $distance);
				$tabDistance = array_merge($tabDistance, $mergingDistance);
				
				$mergingVolume = array('_'.$packageLocation['idPackage'] => $packageLocation['volumeSize']);
				$tabVolume = array_merge($tabVolume, $mergingVolume);
			}
		}
		asort($tabDistance);
		
		foreach($tabDistance as $idPackage => $distance){
			if($remainingVolume-$tabVolume[$idPackage] >= 0){
				if($waypointCounter == 10)
					break;
				$remainingVolume -= $tabVolume[$idPackage];
				$idPackage = str_replace('_', '', $idPackage);
				array_push($idPackageList, $idPackage);
				array_push($tabDistanceCounter, $distance);
				if(!in_array($distance, $tabDistanceCounter))
					$waypointCounter++;
			}else
				break;
		}
		
		return $idPackageList;
	}