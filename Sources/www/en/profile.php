<?php
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
        <meta charset="utf-8">
        <title>LTA-development</title>
        <link rel="icon" href="./../img/LTADevelopmentLogo.ico" />
        <link rel="stylesheet" type="text/css" href="./../css/profile.css">
        <link rel="stylesheet" type="text/css" href="./../css/header.css">
        <link rel="stylesheet" type="text/css" href="./../css/footer.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
    </head>

    <body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>

        <?php
            include("./../php/header.php");

            if ($_SESSION['status'] == 0)
            {
        ?>
                <div class='profile'>

                    <h3>Modify company information</h3>
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

                        <div class='field'>
                            <label>Affiliate Deposit</label>
                            <select name='idDeposit'>
        <?php
            }
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

                        <input type='hidden' name='formType' value='updateProfile'>
                    </form>
                    <input type='submit' value='save' form="companyInformation">

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
            include("./../php/footer.php");
        ?>
    </body>
</html>
