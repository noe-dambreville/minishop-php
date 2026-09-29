<?php
require_once __DIR__ . '/../conf/pdo.php';
require_once __DIR__ . '/../models/Client.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['adrMail']) && !empty($_POST['mdp'])) {

        $mail = trim($_POST['adrMail']);
        $mdp_saisi = trim($_POST['mdp']);

        $c = new Client($pdo);

        if ($c->RechercheClient($mail) && $c->MdpVerif($mdp_saisi)) {
            // Si ok
            session_regenerate_id(true);

            $token = $c->GenerationToken();
            $tokenExpire = (new DateTime('+30 minutes'))->format('Y-m-d H:i:s');

            $c->SetToken($token);
            $c->SetTokenExpire($tokenExpire);

            $c->MajClient($c->GetIdUtilisateur());

            $_SESSION['id_utilisateur'] = $c->GetIdUtilisateur();
            $_SESSION['token'] = $token;

            header('Location: ?a=mon_compte');
            exit;

        } else {
            $notif_erreur = "Identifiant ou mot de passe incorrect";
        }

    } else {
        $notif_erreur = "Veuillez remplir tous les champs";
    }
}