<div>
    <h1>Inscription</h1>
    
    <?php include_once 'componants/notif.php'; ?>

    <form action="?a=inscription" method="POST">
        <div><label>Adresse mail <input name="adrMail" type="email" required></label></div>
        <div><label>Mot de passe <input name="mdp" type="password" required></label></div>
        <br>
        <input type="submit" value="S'inscrire">
    </form>
</div>
