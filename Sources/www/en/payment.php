<?php
    $title = "LTA-development - Payment";

    if(!isset($_SESSION))
        session_start();

    if(!isset($_SESSION['idUser']) || !isset($_SESSION['status']))
    {
        session_unset();
        header("location: ./connection");
    }

    if(!isset($_COOKIE['idOrder'])){
    	header("location: ./home");
    }

    include_once("./../config/config.php");

    $tab = billInformation($_COOKIE['idOrder']);

    if($tab[1]['deliveryStatus'] != 0){
        header("location: ./bill");
    }

    $totalPrice = round($tab[1]['total']*1.2, 2);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include("./php/head.php"); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Ensures optimal rendering on mobile devices. -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- Optimal Internet Explorer compatibility -->
        <link rel="stylesheet" type="text/css" href="./../css/payment.css">
    </head>

    <body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>
        <?php include("./php/header.php"); ?>
        <main>

            <script src="https://www.paypal.com/sdk/js?client-id=ASXCs8-S37AEIDA8s3oJs_E1xfc1BaHnHYWb-YtjDkwCqbi_D3Z-zQGjDKWPUCTw3wL-UNNuvDgPofXa&currency=EUR">
                // Add your client ID and secret
                var PAYPAL_CLIENT = 'ASXCs8-S37AEIDA8s3oJs_E1xfc1BaHnHYWb-YtjDkwCqbi_D3Z-zQGjDKWPUCTw3wL-UNNuvDgPofXa';
                var PAYPAL_SECRET = 'EJdB9p1ythp3A4ZcnNliUYFKbeU_h26vxmuh6KpNC5ioekXPsfELtFVOg3bIBMxBYhbz89FnOKMG_CXd';

                // Point your server to the PayPal API
                var PAYPAL_ORDER_API = 'https://api-m.paypal.com/v2/checkout/orders/';
            </script>

            <div id="paypal-button-container"></div>
            <div id="approvePayment"></div>

            <script>
                paypal.Buttons({
                    createOrder: function(data, actions){
                        // This function sets up the details of the transaction, including the amount and line item details.
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: <?=$totalPrice?>
                                }
                            }]
                        });
                    },
                    onApprove: function(data, actions){
                        // This function captures the funds from the transaction.
                        return actions.order.capture().then(function(details){
                            // This function shows a transaction success message to your buyer.
                            alert('Transaction completed by ' + details.payer.name.given_name);
                            // document.getElementById("paypal-button-container").style.display = "none";
                            //
                            displayApprovePayment(<?=$_COOKIE['idOrder']?>);

                            function displayApprovePayment(idOrder){
                                const requestApprovePayment = new XMLHttpRequest();
                                requestApprovePayment.open('GET', './../php/displayApprovePayment.php?idOrder='+idOrder);
                                requestApprovePayment.onreadystatechange = function(){
                                    if(requestApprovePayment.readyState === 4){ // la requete est terminée
                                        // let divApprovePayment = document.getElementById('approvePayment');
                                        // divApprovePayment.innerHTML = requestApprovePayment.responseText;
                                    }
                                }
                                requestApprovePayment.send();
                            }

                            window.location = "approvePayment.php";

                        });
                    }
                }).render('#paypal-button-container');
                //This function displays Smart Payment Buttons on your web page.
            </script>
        </main>
        <?php include("./php/footer.php"); ?>
    </body>
    <!-- <script type="module" src="./../js/deliveryGame.js"></script> -->
</html>
