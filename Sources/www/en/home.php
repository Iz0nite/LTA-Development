<?php
    $title = "LTA-development";

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
        <?php include("./php/head.php"); ?>
        <link rel="stylesheet" type="text/css" href="./../../css/customerHistory.css">
    </head>

    <body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>

        <?php include("./php/header.php"); ?>
        <main>
            <?php include("./php/customerHistory.php"); ?>
        </main>
        <?php include("./php/footer.php"); ?>
        <script src="../js/customerHistory.js"></script>
    </body>
</html>
