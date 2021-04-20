<?php
    include_once("./../config/config.php");

    $paymentList = getPaymentList();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <div class="paymentContainer">
            <?php foreach ($paymentList as $payment) { ?>
                <li>
                    <span><?= $payment['firstName'] ?> <?= $payment['name'] ?></span>
                    <span><?= $payment['amountTotal'] ?></span>
                    <a href='https://www.lta-development.fr/sendPaymentTest/preparePayment?paymentAmmount=<?= $payment['amountTotal'] ?>&idRemuneration=<?= $payment['idRemuneration'] ?>'>Pay</a>
                    <span><?= $payment['idRemuneration'] ?></span>
                </li>
            <?php } ?>
        </div>
    </body>
</html>
