<?php
	$title = "LTA-development - Delivery man ²Payment";
	
	if(!isset($_SESSION))
		session_start();
	
	if(!isset($_COOKIE['idOrder'])){
		header("location: ./home");
	}
	
	if(!isset($_SESSION['idUser']) || !isset($_SESSION['status']))
	{
		session_unset();
		header("location: ./connection");
	}
	
	include_once("./../config/config.php");
	
	$tab = billInformation($_COOKIE['idOrder']);
	
	if($tab[1]['deliveryStatus'] != 0){
		header("location: ./bill");
	}
	
	$totalPrice = round($tab[1]['total']*1.2, 2);
?>

<!DOCTYPE HTML>
<html lang="en" dir="ltr">
	<head>
		<?php include("./php/head.php"); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Ensures optimal rendering on mobile devices. -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- Optimal Internet Explorer compatibility -->
		<link rel="stylesheet" type="text/css" href="./../css/payment.css">
		<script src="https://js.stripe.com/v3/"></script>
	</head>
	
	<body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>
		<?php include("./php/header.php"); ?>
		<main>
			<button id="checkout-button">Checkout</button>
			
			<script type="text/javascript">
				// Create an instance of the Stripe object with your publishable API key
				let stripe = Stripe('pk_test_51ISmeYArfuXd3X7WMGHjCI11ZxOAtOUXsnwPGulmr6nKJUDNaL2OaNMvZ98E8kj2tVhoIIdSiD2LgVPNMbkjaXRC00b3PIBFx6');
				let checkoutButton = document.getElementById('checkout-button');
				
				checkoutButton.addEventListener('click', function() {
					// Create a new Checkout Session using the server-side endpoint you
					// created in step 3.
					fetch('/create-checkout-session', {
						method: 'POST',
					})
							.then(function(response) {
								return response.json();
							})
							.then(function(session) {
								return stripe.redirectToCheckout({ sessionId: session.id });
							})
							.then(function(result) {
								// If `redirectToCheckout` fails due to a browser or network
								// error, you should display the localized error message to your
								// customer using `error.message`.
								if (result.error) {
									alert(result.error.message);
								}
							})
							.catch(function(error) {
								console.error('Error:', error);
							});
				});
			</script>
		</main>
		<?php include("./php/footer.php"); ?>
	</body>
	<!-- <script type="module" src="./../js/deliveryGame.js"></script> -->
</html>

<?php
	// This example sets up an endpoint using the Slim framework.
	// Watch this video to get started: https://youtu.be/sGcNPFX1Ph4.
	
	use Slim\Http\Request;
	use Slim\Http\Response;
	use Stripe\Stripe;
	
	require 'vendor/autoload.php';
	
	$app = new \Slim\App;
	
	$app->add(function ($request, $response, $next) {
		\Stripe\Stripe::setApiKey('sk_test_51ISmeYArfuXd3X7WjraNIrhVFvls6tZcZQ5MO8AHjHEFK5UvM13BQTmhta6afxBsrJBesyEHoVHOHOM8i2SvGzbx00hwCIB03o');
		return $next($request, $response);
	});
	
	$app->post('/create-checkout-session', function (Request $request, Response $response) {
		$session = \Stripe\Checkout\Session::create([
															'payment_method_types' => ['card'],
															'line_items' => [[
																	'price_data' => [
																			'currency' => 'EUR',
																			'product_data' => [
																					'name' => 'Delivery',
																			],
																			'unit_amount' => $totalPrice,
																	],
																	'quantity' => 1,
															]],
															'mode' => 'payment',
															'success_url' => 'https://www.lta-development.fr/en/approvePayment',
															'cancel_url' => 'https://www.lta-development.fr/en/home',
													]);
		
		return $response->withJson([ 'id' => $session->id ])->withStatus(200);
	});
	
	$app->run();