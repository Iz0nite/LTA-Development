<?php
    include_once("./config/config.php");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>LTA-development</title>
        <link rel="icon" href="img/LTADevelopmentLogo.ico" />
        <link rel="stylesheet" type="text/css" href="./css/header.css">
        <link rel="stylesheet" type="text/css" href="./css/mainPage.css">
        <link rel="stylesheet" type="text/css" href="./css/signInSignUp.css">
        <link rel="stylesheet" type="text/css" href="./css/footer.css">
    </head>

    <body>
        <?php
            include("./php/header.php");

            include_once("./php/signIn.php");

            include("./php/footer.php");
        ?>
    </body>
</html>
