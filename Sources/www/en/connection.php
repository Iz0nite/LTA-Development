<?php
    if(!isset($_SESSION))
        session_start();

    if(isset($_SESSION['idUser']) && isset($_SESSION['status']))
    {
        header("location: ./home");
    }
    else
        session_unset();

    include_once("./../config/config.php");
    include_once("./../config/configLanguage.php");

    $connectionTextLoad = loadConnectionText();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include("./php/head.php"); ?>
		<title>LTA-development - Connection</title>
        <link rel="stylesheet" type="text/css" href="./../css/connection.css">
    </head>

    <body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>

        <?php include("./php/header.php"); ?>

        <main>
            <div class="form">
                <input id="logForm" class="radioBox" type="radio" name="form" checked>
                <div class="logContainer">
                    <form action="./../config/config" method="post">
                        <h3><?= $connectionTextLoad['customersConnection'][$_COOKIE['language']]; ?></h3>

                        <input class="input" type="text" name="email"  placeholder="<?= $connectionTextLoad['email'][$_COOKIE['language']]; ?>">
                        <input class="input" type="password" name="password" placeholder="<?= $connectionTextLoad['password'][$_COOKIE['language']]; ?>">

                        <input type="hidden" name="formType" value="signIn">
                        <input class="submit" type="submit" value="<?= $connectionTextLoad['send'][$_COOKIE['language']]; ?>">

                        <hr>

                        <label><?= $connectionTextLoad['noAccountLbl'][$_COOKIE['language']]; ?> <label class="labelLink" for="signForm"><?= $connectionTextLoad['noAccountLink'][$_COOKIE['language']]; ?></label>.</label>
                    </form>
                </div>

                <input id="signForm" class="radioBox" type="radio" name="form">
                <div class="signContainer">
                    <input id="switchInput" class="radioBox" type="checkbox" name="slider" checked>
                    <div class="slider">
                        <form class="customerSignForm" action="./../../config/config" method="post">
                            <h3><?= $connectionTextLoad['customerInscription'][$_COOKIE['language']]; ?></h3>

                            <input class="input" type="text" name="email" placeholder="<?= $connectionTextLoad['email'][$_COOKIE['language']]; ?>">
                            <input class="input" type="password" name="password" placeholder="<?= $connectionTextLoad['password'][$_COOKIE['language']]; ?>">
                            <input class="input" type="password" name="confirmPassword" placeholder="<?= $connectionTextLoad['confirmPassword'][$_COOKIE['language']]; ?>">
                            <input class="input" type="text" name="companyName"  placeholder="<?= $connectionTextLoad['companyName'][$_COOKIE['language']]; ?>">
                            <input class="input" type="text" name="address" placeholder="<?= $connectionTextLoad['address'][$_COOKIE['language']]; ?>">
                            <input class="input" type="text" name="numTel" placeholder="<?= $connectionTextLoad['telNumber'][$_COOKIE['language']]; ?>">

                            <input type="hidden" name="formType" value="signUpCustomers">
                            <input class="submit" type="submit" value="<?= $connectionTextLoad['send'][$_COOKIE['language']]; ?>">

                            <hr>

                            <div class="switchSignForm">
                                <label><?= $connectionTextLoad['haveAccountLbl'][$_COOKIE['language']]; ?> <label class="labelLink" for="logForm"><?= $connectionTextLoad['haveAccountLink'][$_COOKIE['language']]; ?></label>.</label>
                                <label><?= $connectionTextLoad['deliveryAccountLbl'][$_COOKIE['language']]; ?> <label class="labelLink" for="switchInput"><?= $connectionTextLoad['signAccountLink'][$_COOKIE['language']]; ?></label>.</label>
                            </div>
                        </form>

                        <form class="deliverySignForm" action="./../../config/config" method="post">
                            <h3><?= $connectionTextLoad['deliveryInscription'][$_COOKIE['language']]; ?></h3>

                            <input class="input" type="text" name="email"  placeholder="<?= $connectionTextLoad['email'][$_COOKIE['language']]; ?>">
                            <input class="input" type="password" name="password" placeholder="<?= $connectionTextLoad['password'][$_COOKIE['language']]; ?>">
                            <input class="input" type="password" name="confirmPassword" placeholder="<?= $connectionTextLoad['confirmPassword'][$_COOKIE['language']]; ?>">
                            <input class="input" type="text" name="firstName" placeholder="<?= $connectionTextLoad['firstName'][$_COOKIE['language']]; ?>">
                            <input class="input" type="text" name="name" placeholder="<?= $connectionTextLoad['lastName'][$_COOKIE['language']]; ?>">
                            <input class="input" type="text" name="numTel" placeholder="<?= $connectionTextLoad['telNumber'][$_COOKIE['language']]; ?>">

                            <input type="hidden" name="formType" value="signUpDelivery">
                            <input class="submit" type="submit" value="<?= $connectionTextLoad['send'][$_COOKIE['language']]; ?>">

                            <hr>

                            <div class="switchSignForm">
                                <label><?= $connectionTextLoad['haveAccountLbl'][$_COOKIE['language']]; ?> <label class="labelLink" for="logForm"><?= $connectionTextLoad['haveAccountLink'][$_COOKIE['language']]; ?></label>.</label>
                                <label><?= $connectionTextLoad['customerAccountLbl'][$_COOKIE['language']]; ?> <label class="labelLink" for="switchInput"><?= $connectionTextLoad['signAccountLink'][$_COOKIE['language']]; ?></label>.</label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <?php include("./php/footer.php"); ?>
    </body>
</html>
