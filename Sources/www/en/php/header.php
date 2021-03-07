<header id="header">
    <nav>
        <ul>
            <li><a href="./home"><img src="./../../img/Logo_Fond_Blanc.svg" style="width: 150px"></a></li>
            <div class="headerLinks">
                <?php if (isset($_SESSION['idUser']) && $_SESSION['status'] != 2) { ?>
                    <li><a href="./home">Home</a></li>
                    <li><a href="./approvePayment">onSaitPasTrop</a></li>
                    <li><a href="./php/testSendNotif">Notif</a></li>
                <?php } ?>
                <?php if(isset($_SESSION['idUser'])):?>
                    <?php if ($_SESSION['status'] != 2) { ?>
                        <li><a href="profile">Profile</a></li>
                    <?php } ?>
                    <li>
                        <form action="./../../config/config.php" method="POST">
                            <input type="hidden" name="formType" value="logout">
                            <button class="logout" type="submit" name="logout">Log Out</button>
                        </form>
                    </li>
                <?php else: ?>
                    <div id="inscriptionconnexion">
                        <li><a href="./home" id="textHeader12">Sign Up</a></li>
                        <li id="barHeader">|</li>
                        <li><a href="./home" id="textHeader12">Log In</a></li>
                    </div>
                <?php endif; ?>
            </div>
        </ul>
    </nav>
</header>
