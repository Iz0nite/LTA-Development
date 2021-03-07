<?php
    $title = "LTA-development - Dashboard";
    if(!isset($_SESSION))
        session_start();

    if(!isset($_SESSION['idUser']) || !isset($_SESSION['status']))
    {
        session_unset();
        header("location: ./connection");
        exit();
    }

    if ($_SESSION['status']!=2) {

        header("location: ./home");
        exit();
    }

    include_once("./../config/config.php");
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include("./php/head.php"); ?>
        <link rel="stylesheet" type="text/css" href="./../css/dashBoard.css">
    </head>
    <body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>

        <?php include("./php/header.php"); ?>
        <main>
            <?php include("./php/usersList.php"); ?>
            <?php include("./php/ordersList.php"); ?>
            <?php include("./php/packagesList.php"); ?>
            <?php include("./php/addAdmin.php"); ?>
        </main>
        <?php include("./php/footer.php"); ?>
        <script src="../js/usersList.js"></script>
        <script src="../js/ordersList.js"></script>
        <script src="../js/packagesList.js"></script>
        <script src="../js/config.js"></script>
    </body>
</html>
