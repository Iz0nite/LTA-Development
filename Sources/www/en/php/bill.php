<?php
	
	function calculatePrice(int $weight, int $deliveryType){
		
		$requestPrice = setupCredentials()->prepare("SELECT price FROM PRICE WHERE weight = :weight AND deliveryType = :deliveryType");
		$weightPrice = 0;
		
		if($deliveryType === 0){
			
			if($weight <= 500){
				$requestPrice->bindValue(':weight', 500, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 500 && $weight <= 1000){
				$requestPrice->bindValue(':weight', 1000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 1000 && $weight <= 2000){
				$requestPrice->bindValue(':weight', 2000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 2000 && $weight <= 3000){
				$requestPrice->bindValue(':weight', 3000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 3000 && $weight <= 5000){
				$requestPrice->bindValue(':weight', 5000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 5000 && $weight <= 7000){
				$requestPrice->bindValue(':weight', 7000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 7000 && $weight <= 10000){
				$requestPrice->bindValue(':weight', 10000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 10000 && $weight <= 15000){
				$requestPrice->bindValue(':weight', 15000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 15000 && $weight <= 30000){
				$requestPrice->bindValue(':weight', 30000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 30000){
				$requestPrice->bindValue(':weight', 31000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$iteration = $weight / 20000.;
				$iteration = ceil($iteration);
				$weightPrice = ($iteration * $price[0]);
			}
			
		}elseif($deliveryType === 1){
			
			if($weight <= 500){
				$requestPrice->bindValue(':weight', 500, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 500 && $weight <= 1000){
				$requestPrice->bindValue(':weight', 1000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 1000 && $weight <= 2000){
				$requestPrice->bindValue(':weight', 2000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 2000 && $weight <= 3000){
				$requestPrice->bindValue(':weight', 3000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 3000 && $weight <= 5000){
				$requestPrice->bindValue(':weight', 5000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 5000 && $weight <= 7000){
				$requestPrice->bindValue(':weight', 7000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 7000 && $weight <= 10000){
				$requestPrice->bindValue(':weight', 10000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 10000 && $weight <= 15000){
				$requestPrice->bindValue(':weight', 15000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 15000 && $weight <= 30000){
				$requestPrice->bindValue(':weight', 30000, PDO::PARAM_INT);
				$requestPrice->bindParam(':deliveryType', $deliveryType, PDO::PARAM_INT);
				$requestPrice->execute();
				$price = $requestPrice->fetch();
				$weightPrice = $price[0];
			}elseif($weight > 30000){
				$weightPrice = null;
			}
			
		}else{
			header("location: ./../home");
		}
		
		return $weightPrice;
	}
