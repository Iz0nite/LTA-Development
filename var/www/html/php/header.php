<header>
    <nav>
        <ul>
            <li><a href="index"><img src="img/logo.png"></a></li>
            <li><a href="index">Accueil</a></li>
            <li><a href="#">onSaitPasTrop</a></li>
            <?php if(isset($_SESSION['idUser'])):?>
                <li><a href="profil">Profil</a></li>
                <li><a onclick="logOut()" style="cursor: pointer">Déconnexion</a></li>
            <?php else: ?>
                <div id="inscriptionconnexion">
                    <li><a href="#" id="textHeader12">Inscription</a></li>
                    <li id="barHeader">|</li>
                    <li><a href="#" id="textHeader12">Connexion</a></li>
                </div>
            <?php endif; ?>
        </ul>
    </nav>
</header>
