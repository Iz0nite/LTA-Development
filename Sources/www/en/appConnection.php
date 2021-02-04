<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>LTA-development</title>
        <link rel="icon" href="./../img/LTADevelopmentLogo.ico" />
        <link rel="stylesheet" type="text/css" href="./../css/appConnection.css">
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
            <form class="" action="./../config/config.php" method="post">
                <input type="text" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">

                <input type="hidden" name="formType" value="appConnection">
                <input type="submit" value="Sign In">
            </form>
        </main>
        <?php include("./../php/footer.php"); ?>
    </body>
</html>
