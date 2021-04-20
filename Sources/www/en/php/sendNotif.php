<?php
    
//    if(!isset($_GET['idUser'])){
//        $_GET['idUser'] = 1;
//    }
//
//    if(!isset($_GET['idOder'])){
//        $_GET['idOrder'] = 666;
//    }
    
//	self.close(); ou windows.close(); pour fermet l'onglet actuel
	
	function sendNotif(){
		function sendMessage(){
			$message = 'You have received all the packages of your order number: ' . $_GET['idOrder'];
			$content = array(
				"en" => $message
			);
			
			$fields = array(
				'app_id' => "304775f8-d0e7-4491-925d-cbd0110c11b5",
				'filters' => array(array("field" => "tag", "key" => "idUser", "relation" => "=", "value" => $_GET['idUser'])),
				'data' => array("foo" => "bar"),
				'contents' => $content
			);
			
			$fields = json_encode($fields);
			print("\nJSON sent:\n");
			print($fields);
			
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
		
		$response = sendMessage();
		$return["allresponses"] = $response;
		$return = json_encode( $return);
		
		print("\n\nJSON received:\n");
		print($return);
		print("\n");
	}
