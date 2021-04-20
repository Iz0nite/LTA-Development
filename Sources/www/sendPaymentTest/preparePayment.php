<?php
require './../lib/paypal/vendor/autoload.php';

$ids = require('./configPayPal.php');

$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        $ids['id'],
        $ids['password']
    )
);

$payment = new \PayPal\Api\Payment();

$payment->setIntent('SALE');

$redirectUrls = new \PayPal\Api\RedirectUrls();
$redirectUrls->setReturnUrl('https://www.lta-development.fr/sendPaymentTest/executePayment');
$redirectUrls->setCancelUrl('https://www.lta-development.fr/index');
$payment->setRedirectUrls($redirectUrls);
$payment->setPayer((new \PayPal\Api\Payer())->setPaymentMethod('paypal'));

$list = new \PayPal\Api\ItemList();

/* Prepare item 1 */
$item = (new \PayPal\Api\Item())
    ->setName('Item 1')
    ->setPrice($_GET['paymentAmmount'])
    ->setCurrency('EUR')
    ->setQuantity(1);
$list->addItem($item);

$details = (new \PayPal\Api\Details())
    ->setSubTotal($_GET['paymentAmmount']);

$amount = (new \PayPal\Api\Amount())
    ->setTotal($_GET['paymentAmmount'])
    ->setCurrency('EUR')
    ->setDetails($details);

$transaction = (new \PayPal\Api\Transaction())
    ->setItemList($list)
    ->setDescription('Salary payment on www.lta-development.fr')
    ->setAmount($amount)
    ->setCustom($_GET['idRemuneration']);

$payment->setTransactions([$transaction]);

try
{
    $payment->create($apiContext);
    header('Location: ' . $payment->getApprovalLink());
}
catch (\PayPal\Exception\PayPalConnectionException $e)
{
    echo '<pre>';
    echo $e->getData();
    echo '</pre>';
}
