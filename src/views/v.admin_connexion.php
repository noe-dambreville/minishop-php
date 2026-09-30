<div>
    <h1>Connexion Admin</h1>

    <?php include_once 'componants/notif.php'; ?>

    <form action="?a=admin_connexion" method="POST">
        <div><label>Identifiant <input name="identifiant" type="text" required></label></div>
        <div><label>Mot de passe <input name="mdp" type="password" required></label></div>
        <br>
        <input type="submit" value="Se connecter">
    </form>
</div>