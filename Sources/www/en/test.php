<?php
    $title = "LTA-development - Test";
    require("./../config/config.php");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include("./php/head.php"); ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- Optimal Internet Explorer compatibility -->
        <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<link type="text/css" rel="stylesheet" href="./../js/three.js-master/examples/main.css">
        <link rel="stylesheet" type="text/css" href="./../css/payment.css">
    </head>

    <body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>
        <?php include("./php/header.php"); ?>
        <main>

            <div id="approvePayment">
                <p>Congratulations! Your order has been successfully completed!</p>
                <p>An order confirmation was sent to you by e-mail.</p>

                <div class="paymentButton">
                    <a href="./../en/bill" target="_blank" class="button">Check your invoice</a>
                    <a href="./../en/home" class="button">Order history</a>
                </div>

                <p>Thank you for choosing Quick Baluchon!</p>
            </div>

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

        </main>
        <?php include("./php/footer.php"); ?>
    </body>
    <script type="module" src="./../js/deliveryGame.js"></script>
</html>
