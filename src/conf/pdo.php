<?php
require_once __DIR__ . '/env.php';

try {
    $hst = $_ENV['BDD_HOTE'];
    $bdd = $_ENV['BDD_NOM'];

    $uti = $_ENV['BDD_UTI'];
    $mdp = $_ENV['BDD_MDP'];
    
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    $pdo = new PDO("mysql:host=$hst; dbname=$bdd; charset=utf8", $uti, $mdp, $opt);

} catch (Exception) {
    header('Location: ?a=erreurs&code=503');
    exit;
}
