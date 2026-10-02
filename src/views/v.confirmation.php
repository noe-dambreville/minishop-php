<div>
    <h1>Confirmation du compte</h1>

    <p>Code envoyé à <?= htmlspecialchars($emailMasque) ?></p>

    <?php include_once 'componants/notif.php'; ?>

    <form action="?a=confirmation" method="POST">
        <div><label>Code reçu par mail <input name="code_pin" type="text" maxlength="6" required></label></div>
        <br>
        <input type="submit" value="Valider">
    </form>

    <form action="?a=confirmation" method="POST">
        <input type="hidden" name="renvoyer" value="1">
        <button type="submit">Renvoyer le code</button>
    </form>
</div>
