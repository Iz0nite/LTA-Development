<header>
    <nav>
        <ul>
            <li><a href="./home"><img src="./../img/logo.png"></a></li>
            <li><a href="./home">Home</a></li>
            <li><a href="#">onSaitPasTrop</a></li>
            <?php if(isset($_SESSION['idUser'])):?>
                <li><a href="profile">Profile</a></li>
                <li>
                    <form action="./../config/config.php" method="POST">
                        <input type="hidden" name="formType" value="logout">
                        <button class="logout" type="submit" name="logout">Log Out</button>
                    </form>
                </li>
            <?php else: ?>
                <div id="inscriptionconnexion">
                    <li><a href="#" id="textHeader12">Sign Up</a></li>
                    <li id="barHeader">|</li>
                    <li><a href="#" id="textHeader12">Log In</a></li>
                </div>
            <?php endif; ?>
        </ul>
    </nav>
</header>
