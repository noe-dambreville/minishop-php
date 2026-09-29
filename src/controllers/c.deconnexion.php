<?php
require __DIR__ . '/../conf/pdo.php';
require __DIR__ . '/../models/Utilisateur.php';

if (isset($_SESSION['id_utilisateur'])) {
    $u = new Utilisateur($pdo);

    if ($u->RechercheUtilisateur($_SESSION['id_utilisateur'])) {
        $u->SetToken(null);
        $u->SetTokenExpire(null);

        $u->MajUtilisateur($_SESSION['id_utilisateur']);
    }
}
session_unset();
session_destroy();

header('Location: ?a=accueil');
exit;
