<?php
    if(!isset($_SESSION))
        session_start();

	include_once('./../config/config.php');
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>LTA-Development</title>
    </head>

    <body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>

        <div class="content">
            <form class="" action="./../config/config.php" method="post">
                <input type="text" name="weight" placeholder="Poids du colis (en g)" required>
                <input type="text" name="dimension" placeholder="Dimension du colis (en m3)" required>
                <input type="text" name="address" placeholder="Adresse de l'entrepôt">

                <input type="hidden" name="formType" value="addPackage">
                <input type="submit">
            </form>
        </div>
    </body>
</html>
