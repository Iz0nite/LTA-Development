<?php
    $title = "LTA-development - Connection";
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
        <?php include("./php/head.php"); ?>
        <link rel="stylesheet" type="text/css" href="./../css/connection.css">
        <link rel="stylesheet" type="text/css" href="./../css/signInSignUp.css">
    </head>

    <body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>

        <?php include("./php/header.php"); ?>

        <main>
            <?php include_once("./php/signIn.php"); ?>
        </main>

        <?php include("./php/footer.php"); ?>
    </body>
</html>
