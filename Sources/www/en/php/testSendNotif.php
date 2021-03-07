<?php
    
    if(!isset($_GET['idUser'])){
        $_GET['idUser'] = 1;
    }
    
//	self.close(); ou windows.close(); pour fermet l'onglet actuel
?>

<?php
   /* function sendMessage(){
        $content = array(
            "en" => 'Send test envoie notif'
            );

        $fields = array(
            'app_id' => "304775f8-d0e7-4491-925d-cbd0110c11b5",
            'include_external_user_ids' => array("6392d91a-b206-4b7b-a620-cd68e32c3a76","76ece62b-bcfe-468c-8a78-839aeaa8c5fa","8e0f21fa-9a5a-4ae7-a9a6-ca1f24294b86"),
      'channel_for_external_user_ids' => 'push',
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
    print("\n");*/
?>

<?php
    function sendMessage(){
        $content = array(
            "en" => 'Message de test visant LUC !'
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
?>