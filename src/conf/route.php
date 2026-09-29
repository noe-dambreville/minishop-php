<?php
require_once __DIR__ . '/../models/Utilisateur.php';

$routes = [
    'admin_dashboard' => ['admin'],
    'mon_compte' => ['client'],
];

$rolesOk = $routes[$action] ?? null;

if ($rolesOk !== null) {
    $u = new Utilisateur($pdo);

    if (!$u->VerifSession() || !in_array($u->GetRole(), $rolesOk, true)) {
        if (in_array('admin', $rolesOk, true)) {
            // Masquer l'existance si le client ou public tente de se connecter sur la page admin
            header('Location: ?a=erreurs&code=404');
        } else {
            header('Location: ?a=connexion');
        }
        exit;
    }
}
