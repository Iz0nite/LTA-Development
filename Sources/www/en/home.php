<?php
    if(!isset($_SESSION))
        session_start();

    if(!isset($_SESSION['idUser']) || !isset($_SESSION['status']))
    {
        session_unset();
        header("location: ./connection");
    }

    include_once("./../config/config.php");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>LTA-development</title>
        <link rel="icon" href="./../img/LTADevelopmentLogo.ico" />
        <link rel="stylesheet" type="text/css" href="./../css/home.css">
        <link rel="stylesheet" type="text/css" href="./../css/header.css">
        <link rel="stylesheet" type="text/css" href="./../css/footer.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
    </head>

    <body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>

        <?php include("./../php/header.php"); ?>
        <main>
            <?php include("./../php/customerHistory.php"); ?>
        </main>
        <?php include("./../php/footer.php"); ?>
    </body>
</html>
