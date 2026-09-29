<?php
require_once __DIR__ . '/../models/Utilisateur.php';

$routes = [
    'admin_dashboard' => ['admin'],
    'mon_compte' => ['client'],
];

$rolesOk = $routes[$action] ?? null;

if ($rolesOk !== null) {
    $UtiConnecte = new Utilisateur($pdo);

    if (!$UtiConnecte->VerifSession() || !in_array($UtiConnecte->GetRole(), $rolesOk, true)) {
        header('Location: ?a=connexion');
        exit;
    }
}
