<?php
	if(!isset($_SESSION))
		session_start();
	
	if(!isset($_SESSION['idUser']) || !isset($_SESSION['status'])){
		session_unset();
		header("location: ./connection");
	}
	
	if($_SESSION['status'] == 2)
		header("Location: ./dashBoard");
	
	include_once("./../config/config.php");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<?php include("./php/head.php"); ?>
		<title>LTA-development</title>
		<script src="./../js/apiOneSignal/setExternalUserIdMethod.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/customerHistory.css">
        <link rel="stylesheet" type="text/css" href="/css/deliveryManHistory.css">
	</head>
	
	<body class="preload">
		<script type="text/javascript">
			$("body").removeClass("preload");
			if(<?=$_SESSION['setMethod'] ?? 0 ?> === 1){
				setExternalUserIdMethod(<?=$_SESSION['idUser']?>);
				<?php $_SESSION['setMethod'] = 0;?>
			}
		</script>
		
		<?php include("./php/header.php"); ?>
		<main>
            <?php
                if ($_SESSION['status'] == 0){
                    include("./php/customerHistory.php");
                }elseif ($_SESSION['status'] == 1){
                    include("./php/deliveryManHistory.php");
                }
            ?>
		</main>
		<?php include("./php/footer.php"); ?>
		<script src="../js/customerHistory.js"></script>
	</body>
</html>
