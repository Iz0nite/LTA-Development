<?php
    $title = "LTA-development - Profile";
    if(!isset($_SESSION))
        session_start();

    if(!isset($_SESSION['idUser']) || !isset($_SESSION['status']))
    {
        session_unset();
        header("location: ./connection");
    }

    include_once("./../config/config.php");

    $depositsList = getDepositsList();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include("./php/head.php"); ?>
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

                    <h3>Modify company informations</h3>
                    <form class='companyInformation' action='./../config/config.php' method='post' id="companyInformation">
                        <div class='field'>
                            <label>Email</label>
                            <input type='text' name='email' placeholder='Your email' value='<?php echo getUserData($_SESSION['idUser'], 'email')?>' disabled>
                        </div>

                        <div class='field'>
                            <label>Address</label>
                            <input type='text' name='address' placeholder='Your address' value='<?php echo getUserData($_SESSION['idUser'], 'address')?>'>
                        </div>

                        <div class='field'>
                            <label>Phone number</label>
                            <input type='text' name='phoneNumber' placeholder='Your phone number' value='<?php echo getUserData($_SESSION['idUser'], 'telNumber')?>'>
                        </div>

                        <div class='field'>
                            <label>Company name</label>
                            <input type='text' name='companyName' placeholder='The name of your company' value="<?php echo getUserData($_SESSION['idUser'], 'companyName')?>">
                        </div>

                        <input type='hidden' name='formType' value='updateProfile'>
                    </form>
                    <input type='submit' value='save' form="companyInformation">
        <?php
            }elseif($_SESSION['status'] == 1){ ?>

                    <h3>Modify delivery informations</h3>
                    <form class='companyInformation' action='./../config/config.php' method='post' id="companyInformation">
                        <div class='field'>
                            <label>Email</label>
                            <input type='text' name='email' placeholder='Your email' value='<?php echo getUserData($_SESSION['idUser'], 'email')?>' disabled>
                        </div>

                        <div class='field'>
                            <label>First name</label>
                            <input type='text' name='firstName' placeholder='Your first name' value='<?php echo getUserData($_SESSION['idUser'], 'firstName')?>' disabled>
                        </div>

                        <div class='field'>
                            <label>Last name</label>
                            <input type='text' name='lastName' placeholder='Your last name' value='<?php echo getUserData($_SESSION['idUser'], 'name')?>' disabled>
                        </div>

                        <div class='field'>
                            <label>Phone number</label>
                            <input type='text' name='phoneNumber' placeholder='Your phone number' value='<?php echo getUserData($_SESSION['idUser'], 'telNumber')?>'>
                        </div>

                        <div class='field'>
                            <label>Affiliate Deposit</label>
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
                            <label>Geo area</label>
                            <input type='number' name='geoArea' placeholder='Select a work area arround the deposit  ' value='<?php echo getUserData($_SESSION['idUser'], 'geoArea')?>'>
                        </div>

                        <input type='hidden' name='formType' value='updateProfile'>
                    </form>
                    <input type='submit' value='save' form="companyInformation">

                    <hr>

                    <div class="vehiclesInformation"  id="vehiclesInformation">
                        <label>List of your vehicle</label>

                        <div class="listVehicle" id="listVehicle">

                            <?php vehicleList(); ?>

                        </div>


                        <div class='field'>
                            <label>Registration</label>
                            <input id="registration" type='text' name='registration' placeholder='The registration of your vehicle'>
                        </div>

                        <div class='field'>
                            <label>Volume size</label>
                            <input id="volumeSize" type='number' name='volumeSize' placeholder='The volume your vehicle can hold (in m3)'>
                        </div>

                    </div>
                    <input type='submit' value='Add' onclick="addVehicle()">

                <?php
                }
                ?>

                    <hr>

                    <h3>Modify password</h3>
                    <form class='passwordInformation' action='./../config/config.php' method='post' id="passwordInformation">

                        <div class='field'>
                            <label>Current password</label>
                            <input type='password' name='currentPassword' placeholder='Your current password'>
                        </div>

                        <div class='field'>
                            <label>New password</label>
                            <input type='password' name='newPassword' placeholder='Your new password'>
                        </div>

                        <div class='field'>
                            <label>Confirm new password</label>
                            <input type='password' name='confirmPassword' placeholder='Confirm your new password'>
                        </div>

                        <input type='hidden' name='formType' value='updatePassword'>
                    </form>
                    <input type='submit' value='save' form="passwordInformation">

                </div>
        <?php
            include("./php/footer.php");
        ?>
        <script src="../js/profile.js"></script>
    </body>
</html>
