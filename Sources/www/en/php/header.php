<?php
	include_once("./../config/configLanguage.php");
	
	isSetCookieLanguage();
	$headerTextLoad = loadHeaderText();
?>

<header>
	<div class="divHeader">
		<a href="./home"><img src="./../../img/Logo_Fond_Blanc.svg" class="logo" alt="LogoQuickBaluchon"></a>
		<?php if(isset($_SESSION['idUser']) && $_SESSION['status'] != 2){ ?>
			<div>
				<a href="./home"><?=$headerTextLoad['home'][$_COOKIE['language']];?></a>
				<a href="./approvePayment">onSaitPasTrop</a>
				<a href="./test">TEST</a>
				<a href="./php/sendNotif">Notif</a>
				<a href="profile"><?=$headerTextLoad['profil'][$_COOKIE['language']];?></a>
				<div>
					<form action="./../../config/config" method="POST" style="width: auto;">
						<input type="hidden" name="formType" value="logout">
						<button class="logout" type="submit" name="logout"><?=$headerTextLoad['logout'][$_COOKIE['language']];?></button>
					</form>
				</div>
			</div>
		<?php }elseif($_SESSION['status'] == 2){ ?>
			<div class="logoutButton">
				<form action="./../../config/config" method="POST">
					<input type="hidden" name="formType" value="logout">
					<button class="logout" type="submit" name="logout"><?=$headerTextLoad['logout'][$_COOKIE['language']];?></button>
				</form>
			</div>
		<?php }else{ ?>
			<div id="inscriptionConnexion">
				<div><label for="signForm"><?=$headerTextLoad['signUp'][$_COOKIE['language']];?></label></div>
				<div id="barHeader">|</div>
				<div><label for="logForm"><?=$headerTextLoad['logIn'][$_COOKIE['language']];?></label></div>
			</div>
		<?php } ?>
	</div>
</header>
