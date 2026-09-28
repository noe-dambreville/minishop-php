<div>
    <h1>Connexion</h1>

    <?php include_once 'componants/notif.php'; ?>

    <form action="?a=connexion" method="POST">
        <div><label>Adresse mail <input name="adrMail" type="email" required></label></div>
        <div><label>Mot de passe <input name="mdp" type="password" required></label></div>
        <br>
        <input type="submit" value="Se connecter">
    </form>
    <a href="?a=inscription">S'inscrire</a>

</div>