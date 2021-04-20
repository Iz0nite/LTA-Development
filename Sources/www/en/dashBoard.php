<?php
	if(!isset($_SESSION)) session_start();

	if(!isset($_SESSION['idUser']) || !isset($_SESSION['status'])){
		session_unset();
		header("location: ./connection");
		exit();
	}

	if($_SESSION['status'] != 2){

		header("location: ./home");
		exit();
	}

	include_once("./../config/config.php");
	include_once("./../config/configLanguage.php");

	$dashboardTextLoad = loadDashboardText();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<?php include("./php/head.php"); ?>
		<title>LTA-development - Dashboard</title>
		<link rel="stylesheet" type="text/css" href="./../css/dashBoard.css">
	</head>
	<body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>

		<?php
			include("./php/header.php"); ?>
		<main>
			<div class="dashboardContainer">

				<div class="dashboardTabs">
					<label for="userList"><?=$dashboardTextLoad['userList'][$_COOKIE['language']];?></label>
					<label for="orderList"><?=$dashboardTextLoad['orderList'][$_COOKIE['language']];?></label>
					<label for="packagesList"><?=$dashboardTextLoad['packageList'][$_COOKIE['language']];?></label>
					<label for="addAdmin"><?=$dashboardTextLoad['addAdmin'][$_COOKIE['language']];?></label>
					<label for="transfer"><?=$dashboardTextLoad['payDeliveryMan'][$_COOKIE['language']];?></label>
				</div>

				<div class="dashboardModuleContainer">
					<!--module userList-->
					<input type="radio" name="modules" id="userList" checked>
					<div class="usersListModule">
						<div class="usersListOption">
							<div>
								<label for="userSelect"><?=$dashboardTextLoad['userFunction'][$_COOKIE['language']];?></label>
								<select id="userSelect" onchange="usersList()" class="select">
									<option value=-1 selected><?=$dashboardTextLoad['all'][$_COOKIE['language']];?></option>
									<option value=0><?=$dashboardTextLoad['customer'][$_COOKIE['language']];?></option>
									<option value=1><?=$dashboardTextLoad['deliveryMan'][$_COOKIE['language']];?></option>
								</select>
							</div>
							<input id="searchUser" type="search" name="searchUser" oninput="usersList()" placeholder="<?=$dashboardTextLoad['searchUser'][$_COOKIE['language']];?>">
						</div>
						<div id="usersList" class="usersList">
							<?php usersList(); ?>
						</div>
					</div>

					<!--module orderList-->
					<input type="radio" name="modules" id="orderList">
					<div class="orderListModule">
						<div class="ordersListOption">
							<div>
								<label for="orderSelect"><?=$dashboardTextLoad['userFunction'][$_COOKIE['language']];?></label>
								<select id="orderSelect" onchange="ordersList()" class="select">
									<option value=-1 selected><?=$dashboardTextLoad['all'][$_COOKIE['language']];?></option>
									<option value=0><?=$dashboardTextLoad['waitPayment'][$_COOKIE['language']];?></option>
									<option value=1><?=$dashboardTextLoad['preparation'][$_COOKIE['language']];?></option>
									<option value=2><?=$dashboardTextLoad['finish'][$_COOKIE['language']];?></option>
								</select>
							</div>
							<input id="searchOrder" type="search" name="searchOrder" oninput="ordersList()" placeholder="<?=$dashboardTextLoad['searchOrder'][$_COOKIE['language']];?>">
						</div>
						<div id="ordersList" class="ordersList">
							<?php
								ordersList(); ?>
						</div>
					</div>

					<!--module packagesList-->
					<input type="radio" name="modules" id="packagesList">
					<div class="packageListModule">
						<div class="packagesListOption">
							<div>
								<label for="orderSelect"><?=$dashboardTextLoad['packageStatus'][$_COOKIE['language']];?></label>
								<select id="packageSelect" onchange="packagesList()" class="select">
									<option value=-1 selected><?=$dashboardTextLoad['all'][$_COOKIE['language']];?></option>
									<option value=0><?=$dashboardTextLoad['inDeposit'][$_COOKIE['language']];?></option>
									<option value=1><?=$dashboardTextLoad['inDelivery'][$_COOKIE['language']];?></option>
									<option value=2><?=$dashboardTextLoad['delivered'][$_COOKIE['language']];?></option>
								</select>
							</div>
							<input id="searchPackage" type="search" name="searchPackage" oninput="packagesList()" placeholder="<?=$dashboardTextLoad['searchPackage'][$_COOKIE['language']];?>">
						</div>
						<div id="packagesList" class="packagesList">
							<?php
								packagesList(); ?>
						</div>
					</div>

					<!--module addAdmin-->
					<input type="radio" name="modules" id="addAdmin">
					<div class="addAdminModule">
						<form class="addAdminForm" action="./../config/config" method="post">
							<input type="text" name="adminEmail" placeholder="<?=$dashboardTextLoad['emailAdmin'][$_COOKIE['language']];?>">
							<input type="password" name="adminPassword" placeholder="<?=$dashboardTextLoad['password'][$_COOKIE['language']];?>">
							<input type="password" name="adminConfirmPassword" placeholder="<?=$dashboardTextLoad['confirmPassword'][$_COOKIE['language']];?>">

							<input type="hidden" name="formType" value="addAdmin">
							<input type="submit" class="addAdminButton" value="<?=$dashboardTextLoad['send'][$_COOKIE['language']];?>">
						</form>
					</div>

					<!--module transfer-->
					<input type="radio" name="modules" id="transfer">
					<div class="transferModule">
						<div>
				            <?php foreach ($paymentList as $payment) { ?>
				                <li>
				                    <span><?= $payment['firstName'] ?> <?= $payment['name'] ?></span>
				                    <span><?= $payment['amountTotal'] ?></span>
				                    <a href='https://www.lta-development.fr/sendPaymentTest/preparePayment?paymentAmmount=<?= $payment['amountTotal'] ?>&idRemuneration=<?= $payment['idRemuneration'] ?>'>Pay</a>
				                    <span><?= $payment['idRemuneration'] ?></span>
				                </li>
				            <?php } ?>
						</div>
					</div>
				</div>
			</div>
		</main>
		<?php
			include("./php/footer.php"); ?>
		<script src="../js/dashboard.js"></script>
		<!--        <script src="../js/config.js"></script>-->
	</body>
</html>
