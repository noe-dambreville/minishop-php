<nav>
    <div class="logo">
        <a href="?a=accueil">Boutique de café</a>
    </div>
    <div class="nav-liens">
        <a href="?a=accueil">Accueil</a>
        <a href="?a=catalogue">Nos produits</a>
    </div>
    <div class="nav-compte">
        <?php if (isset($_SESSION['id_utilisateur'])): ?>
            <a href="?a=mon_compte">Mon profil</a>
            <a href="?a=deconnexion">Se déconnecter</a>
        <?php else: ?>
            <a href="?a=connexion">Se connecter</a>
        <?php endif; ?>
        <span>Panier</span>
    </div>
</nav>