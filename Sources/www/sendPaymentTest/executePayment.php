<?php

include_once("./../config/config.php");

require './../lib/paypal/vendor/autoload.php';

$ids = require('./configPayPal.php');

$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        $ids['id'],
        $ids['password']
    )
);

$payment = \PayPal\Api\Payment::get($_GET['paymentId'], $apiContext);

$execution = (new \PayPal\Api\PaymentExecution())
    ->setPayerId($_GET['PayerID'])
    ->setTransactions($payment->getTransactions());

try
{
    $payment->execute($execution, $apiContext);
    // var_dump($payment->getTransactions()[0]->getCustom());
    // var_dump($payment->state);
    // echo '<pre>';
    // var_dump($payment);
    // echo '</pre>';

    if (!strcmp($payment->state, "approved"))
    {
        changeStatePayment($payment->getTransactions()[0]->getCustom());
        header('Location: ./test');
    }
    else
        echo "An error has been ocurred !";
}
catch (\PayPal\Exception\PayPalConnectionException $e)
{
    echo '<pre>';
    echo $e->getData();
    echo '</pre>';
}
