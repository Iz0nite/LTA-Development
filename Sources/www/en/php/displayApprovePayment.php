<?php

    require("./../../config/config.php");

    if(!isset($_GET['idOrder'])){
        header('location: ./../home');
    }

    $requestOrder = setupCredentials()->prepare("SELECT deliveryStatus FROM `ORDER` WHERE idOrder = :idOrder");
    $requestOrder->bindParam(':idOrder', $_GET['idOrder'], PDO::PARAM_INT);
    $requestOrder->execute();
    $order = $requestOrder->fetch();

    if($order['deliveryStatus'] == 0){
        $requestOrder = setupCredentials()->prepare("UPDATE `ORDER` SET deliveryStatus = 1 WHERE idOrder = :idOrder");
        $requestOrder->bindParam(':idOrder', $_GET['idOrder'], PDO::PARAM_INT);
        $requestOrder->execute();
    }

    ?>
    <p>Congratulations! Your order has been successfully completed!</p>
    <p>An order confirmation was sent to you by e-mail.</p>

    <div class="paymentButton">
        <a href="./../en/bill" target="_blank" class="button">Check your invoice</a>
        <a href="./../en/home" class="button">Order history</a>
    </div>

    <p>Thank you for choosing Quick Baluchon!</p>

    <div id="container">
        <label id="rules"></label>
    </div>

    <div id="blocker">
        <div id="instructions">
            <span style="font-size:36px">Click to play</span>
            <br/><br/>
            Move: ZQSD<br/>
            <!-- Jump: SPACE<br/> -->
            Look: MOUSE
        </div>
    </div>
