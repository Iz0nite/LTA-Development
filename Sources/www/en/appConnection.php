<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php
        $title = "LTA-development - Application connection";
        include("./php/head.php");
        ?>
        <link rel="stylesheet" type="text/css" href="./../css/appConnection.css">
    </head>

    <body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
		</script>

        <?php include("./php/header.php"); ?>
        <main>
            <form class="" action="./../config/config.php" method="post">
                <input type="text" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">

                <input type="hidden" name="formType" value="appConnection">
                <input type="submit" value="Sign In">
            </form>
        </main>
        <?php include("./php/footer.php"); ?>
    </body>
</html>
