<?php
require_once __DIR__ . '/../conf/pdo.php';
require_once __DIR__ . '/../models/Administrateur.php';
require_once __DIR__ . '/../models/Connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['identifiant']) && !empty($_POST['mdp'])) {

        $identifiant = trim($_POST['identifiant']);
        $mdp_saisi = trim($_POST['mdp']);
        $adrIp = $_SERVER['REMOTE_ADDR'];

        $a = new Administrateur($pdo);
        
        $compteTrouve = $a->RechercheAdmin($identifiant);
        $idCible = $compteTrouve ? $a->GetIdUtilisateur() : null;

        $j = new Connexion($pdo, $adrIp, (new DateTime())->format('Y-m-d H:i:s'), 0, $idCible);

        if ($j->TropDeTentatives($adrIp, $idCible)) {
            $j->Enregistrer();

            $notif_erreur = "Trop de tentatives, réessayez plus tard";

        } elseif ($compteTrouve && $a->MdpVerif($mdp_saisi)) {
            $j->SetStatut(1);
            $j->Enregistrer();

            // Si ok
            session_regenerate_id(true);

            $token = $a->GenerationToken();
            $tokenExpire = (new DateTime('+30 minutes'))->format('Y-m-d H:i:s');

            $a->SetToken($a->Hasher($token));
            $a->SetTokenExpire($tokenExpire);

            $a->MajAdmin($a->GetIdUtilisateur());

            $_SESSION['id_utilisateur'] = $a->GetIdUtilisateur();
            $_SESSION['token'] = $token;

            header('Location: ?a=admin_dashboard');
            exit;

        } else {
            $j->Enregistrer();
            $notif_erreur = "Identifiant ou mot de passe incorrect";
        }

    } else {
        $notif_erreur = "Veuillez remplir tous les champs";
    }
}