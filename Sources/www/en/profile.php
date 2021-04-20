<?php
    if(!isset($_SESSION))
        session_start();

    if(!isset($_SESSION['idUser']) || !isset($_SESSION['status']))
    {
        session_unset();
        header("location: ./connection");
    }

    include_once("./../config/config.php");
    include_once("./../config/configLanguage.php");

    $depositsList = getDepositsList();
    $profileTextLoad = loadProfileText();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include("./php/head.php"); ?>
		<title>LTA-development - Profile</title>
        <link rel="stylesheet" type="text/css" href="./../css/profile.css">
    </head>

    <body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>

        <?php
            include("./php/header.php");

            ?>
            <div class='profile'>
            <?php

            if($_SESSION['status'] == 0)
            {
        ?>
                    <h3><?= $profileTextLoad['modifyCustomer'][$_COOKIE['language']]; ?></h3>
                    <form class='companyInformation' action='./../config/config' method='post' id="companyInformation">
                        <div class='field'>
                            <label><?= $profileTextLoad['email'][$_COOKIE['language']]; ?></label>
                            <input type='text' name='email' placeholder='Your email' value='<?php echo getUserData($_SESSION['idUser'], 'email')?>' disabled>
                        </div>

                        <div class='field'>
                            <label><?= $profileTextLoad['address'][$_COOKIE['language']]; ?></label>
                            <input type='text' name='address' placeholder='Your address' value='<?php echo getUserData($_SESSION['idUser'], 'address')?>'>
                        </div>

                        <div class='field'>
                            <label><?= $profileTextLoad['telNumber'][$_COOKIE['language']]; ?></label>
                            <input type='text' name='phoneNumber' placeholder='Your phone number' value='<?php echo getUserData($_SESSION['idUser'], 'telNumber')?>'>
                        </div>

                        <div class='field'>
                            <label><?= $profileTextLoad['companyName'][$_COOKIE['language']]; ?></label>
                            <input type='text' name='companyName' placeholder='The name of your company' value="<?php echo getUserData($_SESSION['idUser'], 'companyName')?>">
                        </div>

                        <input type='hidden' name='formType' value='updateProfile'>
                    </form>
                    <input type='submit' value='<?= $profileTextLoad['save'][$_COOKIE['language']]; ?>' form="companyInformation">
        <?php
            }elseif($_SESSION['status'] == 1){ ?>

                    <h3><?= $profileTextLoad['ModifyDelivery'][$_COOKIE['language']]; ?></h3>
                    <form class='companyInformation' action='./../config/config' method='post' id="companyInformation">
                        <div class='field'>
                            <label><?= $profileTextLoad['email'][$_COOKIE['language']]; ?></label>
                            <input type='text' name='email' placeholder='Your email' value='<?php echo getUserData($_SESSION['idUser'], 'email')?>' disabled>
                        </div>

                        <div class='field'>
                            <label><?= $profileTextLoad['firstName'][$_COOKIE['language']]; ?></label>
                            <input type='text' name='firstName' placeholder='Your first name' value='<?php echo getUserData($_SESSION['idUser'], 'firstName')?>' disabled>
                        </div>

                        <div class='field'>
                            <label><?= $profileTextLoad['lastName'][$_COOKIE['language']]; ?></label>
                            <input type='text' name='lastName' placeholder='Your last name' value='<?php echo getUserData($_SESSION['idUser'], 'name')?>' disabled>
                        </div>

                        <div class='field'>
                            <label><?= $profileTextLoad['telNumber'][$_COOKIE['language']]; ?></label>
                            <input type='text' name='phoneNumber' placeholder='Your phone number' value='<?php echo getUserData($_SESSION['idUser'], 'telNumber')?>'>
                        </div>

                        <div class='field'>
                            <label><?= $profileTextLoad['AffiliateDeposit'][$_COOKIE['language']]; ?></label>
                            <select name='idDeposit'>
                        <?php
                        foreach($depositsList as $deposit)
                        {
                            if (getUserData($_SESSION['idUser'], 'idDeposit') == $deposit['idDeposit'])
                                echo "<option value='" . $deposit['idDeposit'] . "' selected>" . $deposit['address'] . ", " . $deposit['city'] . "</option>";
                            else
                                echo "<option value='" . $deposit['idDeposit'] . "'>" . $deposit['address'] . ", " . $deposit['city'] . "</option>";
                        }
                        ?>
                            </select>
                        </div>

                        <div class='field'>
                            <label><?= $profileTextLoad['geoArea'][$_COOKIE['language']]; ?></label>
                            <input type='number' name='geoArea' placeholder='Select a work area arround the deposit  ' value='<?php echo getUserData($_SESSION['idUser'], 'geoArea')?>'>
                        </div>

                        <input type='hidden' name='formType' value='updateProfile'>
                    </form>
                    <input type='submit' value='<?= $profileTextLoad['save'][$_COOKIE['language']]; ?>' form="companyInformation">

                    <hr>

                    <div class="vehiclesInformation"  id="vehiclesInformation">
                        <label><?= $profileTextLoad['listVehicle'][$_COOKIE['language']]; ?></label>

                        <div class="listVehicle" id="listVehicle">

                            <?php vehicleList(); ?>

                        </div>


                        <div class='field'>
                            <label for="registration"><?= $profileTextLoad['registration'][$_COOKIE['language']]; ?></label>
                            <input id="registration" type='text' name='registration' placeholder="<?= $profileTextLoad['plhRegistration'][$_COOKIE['language']]; ?>">
                        </div>

                        <div class='field'>
                            <label for="volumeSize"><?= $profileTextLoad['volumeMax'][$_COOKIE['language']]; ?></label>
                            <!--<input id="volumeSize" type='number' name='volumeSize' placeholder='The volume your vehicle can hold (in m3)'>-->
                            <select id="volumeSize"  class="select">
                                <option value=3 selected>3m3</option>
                                <option value=6>6m3</option>
                                <option value=8>8m3</option>
                                <option value=12>12m3</option>
                                <option value=20>20m3</option>
                                <option value=30>30m3</option>
                            </select>
                        </div>

                    </div>
                    <input type='submit' value='<?= $profileTextLoad['addVehicle'][$_COOKIE['language']]; ?>' onclick="addVehicle()">

                <?php
                }
                ?>

                    <hr>

                    <h3><?= $profileTextLoad['modifyPassword'][$_COOKIE['language']]; ?></h3>
                    <form class='passwordInformation' action='./../config/config' method='post' id="passwordInformation">

                        <div class='field'>
                            <label><?= $profileTextLoad['currentPassword'][$_COOKIE['language']]; ?></label>
                            <input type='password' name='currentPassword' placeholder='<?= $profileTextLoad['plhCurrentPassword'][$_COOKIE['language']]; ?>'>
                        </div>

                        <div class='field'>
                            <label><?= $profileTextLoad['newPassword'][$_COOKIE['language']]; ?></label>
                            <input type='password' name='newPassword' placeholder='<?= $profileTextLoad['plhNewPassword'][$_COOKIE['language']]; ?>'>
                        </div>

                        <div class='field'>
                            <label><?= $profileTextLoad['confirmNewPassword'][$_COOKIE['language']]; ?></label>
                            <input type='password' name='confirmPassword' placeholder='<?= $profileTextLoad['plhConfirmNewPassword'][$_COOKIE['language']]; ?>'>
                        </div>

                        <input type='hidden' name='formType' value='updatePassword'>
                    </form>
                    <input type='submit' value='<?= $profileTextLoad['save'][$_COOKIE['language']]; ?>' form="passwordInformation">

                </div>
        <?php
            include("./php/footer.php");
        ?>
        <script src="../js/profile.js"></script>
    </body>
</html>
