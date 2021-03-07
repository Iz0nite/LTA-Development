<?php
    $title = "LTA-development - Bill";
    if(!isset($_SESSION))
        session_start();

    if(!isset($_SESSION['idUser']) || !isset($_SESSION['status'])){
        session_unset();
        header("location: ./connection");
    }
    include_once("./../config/config.php");

    if(!isset($_COOKIE['idOrder'])){
    	header("location: ./home");
    }

    $tab = billInformation($_COOKIE['idOrder']);

    if($tab[1]['deliveryStatus'] == 0){
        header("location: ./payment");
    }

    $counter = 1;   //To count the package number

    $subTotalPrice = 0;
    $totalTax = 0;
    $totalPrice = 0;

    $input = $tab[1]['creationDate']; $date = strtotime($input);
    $dateNumber = date('Ym', $date);
    $nameOrder = 'F' . $dateNumber . '0' . $_COOKIE['idOrder'];

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include("./php/head.php"); ?>
        <link rel="stylesheet" type="text/css" href="./../css/bill.css">
    </head>

    <body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>

        <?php include("./php/header.php"); ?>
        <main>
            <?php include("./php/bill.php"); ?>
            <div class="bill">

                <div class="headerBill">
                    <div>
                        <div class="">
                            <h1>Invoice n° <?=$nameOrder?></h1>
                            <h3><?php
                            $input = $tab[1]['creationDate'];
                            $date = strtotime($input);
                            echo date('d/m/Y', $date)
                            ?></h3>
                        </div>
                        <img src="../img/logoQuickBaluchon.png" alt="logoQuickBaluchon">
                    </div>
                </div>

                <div class="srcdstBill">
                    <div class="fromBill">
                        <h3>From:</h3>
                        <p>Quick Baluchon</p>
                        <p>contact@quickbaluchon.fr</p>
                        <p>242 Rue du Faubourg Saint-Antoine, Paris</p>
                    </div>

                    <div class="toBill">
                        <div class="fromBill">
                            <h3>To:</h3>
                            <p><?=$tab[2]['companyName'];?></p>
                            <p><?=$tab[2]['email'];?></p>
                            <p><?=$tab[2]['address'];?></p>
                            <p><?=$tab[2]['telNumber'];?></p>
                        </div>
                    </div>
                </div>

                <div class="corpsBill">
                    <table>
                        <thead>
                            <tr>
                                <th colspan="1">Type</th>
                                <th colspan="1">Label</th>
                                <th colspan="1">Quantity</th>
                                <th colspan="1">Price HT</th>
                                <th colspan="1">Amount TTC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($tab[0] as $package){ ?>
                                <tr>
                                    <td>Delivery</td>
                                    <td>Package number <?=$counter?></td>
                                    <td>1</td>
                                    <td><?=calculatePrice($package['weight'], $tab[1]['deliveryType']); ?></td>
                                    <?php $subTotalPrice += calculatePrice($package['weight'], $tab[1]['deliveryType']);?>
                                    <?php $totalTax += calculatePrice($package['weight'], $tab[1]['deliveryType'])*0.2;?>
                                    <td><?=calculatePrice($package['weight'], $tab[1]['deliveryType'])*1.2; ?></td>
                                    <?php $totalPrice += calculatePrice($package['weight'], $tab[1]['deliveryType'])*1.2;?>
                                </tr>
                                <?php $counter++; ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="priceBill">
                    <div class="leftSidePrice">
                        <p>Subtotal: €</p>
                        <p>Tax rate: %</p>
                        <p>Total tax: €</p>
                        <p>Total (TTC): €</p>
                    </div>
                    <div class="rightSidePrice">
                        <p><?=$subTotalPrice?></p>
                        <p>20.0</p>
                        <p><?=$totalTax?></p>
                        <p><?=$totalPrice?></p>
                    </div>
                </div>

                <div class="footerBill">
                    <h2>Terms</h2>
                    <p><b>Type of payment:</b> <?=($tab[1]['paymentType'] == 0) ? 'Credit card' : 'Bank transfer' ?></p>
                    <p><b>Payment due date:</b> 45 days end of month</p>
                </div>
            </div>

            <div class="paymentButton">
                <a href="generatedPDF" target="_blank" class="button">Download</a>
            </div>

        </main>
        <?php include("./php/footer.php"); ?>
    </body>
</html>
