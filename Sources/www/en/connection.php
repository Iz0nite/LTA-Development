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
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>LTA-development</title>
        <link rel="icon" href="./../img/LTADevelopmentLogo.ico" />
        <link rel="stylesheet" type="text/css" href="./../css/connection.css">
        <link rel="stylesheet" type="text/css" href="./../css/header.css">
        <link rel="stylesheet" type="text/css" href="./../css/footer.css">
        <link rel="stylesheet" type="text/css" href="./../css/signInSignUp.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
    </head>

    <body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>

        <?php include("./../php/header.php"); ?>

        <main>
            <?php include_once("./../php/signIn.php"); ?>
        </main>

        <?php include("./../php/footer.php"); ?>
    </body>
</html>
