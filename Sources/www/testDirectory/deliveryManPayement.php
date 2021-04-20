<?php
	
	require("./../lib/PayPal-PHP-SDK-master/lib");
	
	//*****Define payment*****
	use PayPal\Api\Amount;
	use PayPal\Api\Details;
	use PayPal\Api\Item;
	use PayPal\Api\ItemList;
	use PayPal\Api\Payer;
	use PayPal\Api\Payment;
	use PayPal\Api\RedirectUrls;
	use PayPal\Api\Transaction;
	
	// Create new payer and method
	$payer = new Payer();
	$payer->setPaymentMethod("paypal");
	
	// Set redirect URLs
	$redirectUrls = new RedirectUrls();
	$redirectUrls->setReturnUrl('https://www.lta-development.fr/en/connection')->setCancelUrl('https://www.lta-development.fr/en/cancel');
	
	// Set payment amount
	$amount = new Amount();
	$amount->setCurrency("EUR")->setTotal(10); //remplacer le total par $totalPrice = round($tab[1]['total']*1.2, 2);
	
	// Set transaction object
	$transaction = new Transaction();
	$transaction->setAmount($amount)->setDescription("Delivery Payment");
	
	// Create the full payment object
	$payment = new Payment();
	$payment->setIntent('sale')->setPayer($payer)->setRedirectUrls($redirectUrls)->setTransactions(array($transaction));
	
	//*****Create payment*****
	// Create payment with valid API context
	try {
		$payment->create($apiContext);
		
		// Get PayPal redirect URL and redirect the customer
		$approvalUrl = $payment->getApprovalLink();
		
		// Redirect the customer to $approvalUrl
	} catch (PayPal\Exception\PayPalConnectionException $ex) {
		echo $ex->getCode();
		echo $ex->getData();
		die($ex);
	} catch (Exception $ex) {
		die($ex);
	}
	
	//*****Execute payment*****
	require __DIR__ . '/../bootstrap.php';
	use PayPal\Api\Amount;
	use PayPal\Api\Details;
	use PayPal\Api\Payment;
	use PayPal\Api\Transaction;
	use PayPal\Api\ExecutePayment;
	use PayPal\Api\PaymentExecution;
	
	// Get payment object by passing paymentId
	$paymentId = $_GET['paymentId'];
	$payment = Payment::get($paymentId, $apiContext);
	$payerId = $_GET['PayerID'];
	
	// Execute payment with payer ID
	$execution = new PaymentExecution();
	$execution->setPayerId($payerId);
	
	try {
		// Execute payment
		$result = $payment->execute($execution, $apiContext);
		var_dump($result);
	} catch (PayPal\Exception\PayPalConnectionException $ex) {
		echo $ex->getCode();
		echo $ex->getData();
		die($ex);
	} catch (Exception $ex) {
		die($ex);
	}