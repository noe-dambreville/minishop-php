<?php
require_once __DIR__ . '/../conf/pdo.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['adrMail']) && !empty($_POST['mdp'])) {

        $mail = trim($_POST['adrMail']);
        $mdp_saisi = trim($_POST['mdp']);
        $adrIp = $_SERVER['REMOTE_ADDR'];

        $c = new Client($pdo);

        $compteTrouve = $c->RechercheClient($mail);
        $idCible = $compteTrouve ? $c->GetIdUtilisateur() : null;

        $j = new Connexion($pdo, $adrIp, (new DateTime())->format('Y-m-d H:i:s'), 0, $idCible);

        if ($j->TropDeTentatives($adrIp, $idCible)) {
            $j->Enregistrer();

            $notif_erreur = "Trop de tentatives, réessayez plus tard";

        } elseif ($compteTrouve && $c->MdpVerif($mdp_saisi)) {
            $j->SetStatut(1);
            $j->Enregistrer();

            if ($c->GetStatut() !== 'valide') {
                $_SESSION['id_utilisateur_en_attente'] = $c->GetIdUtilisateur();

                header('Location: ?a=confirmation');
                exit;
            }

            // Si ok
            session_regenerate_id(true);

            $token = $c->GenerationToken();
            $tokenExpire = (new DateTime('+30 minutes'))->format('Y-m-d H:i:s');

            $c->SetToken($c->Hasher($token));
            $c->SetTokenExpire($tokenExpire);

            $c->MajClient($c->GetIdUtilisateur());

            $_SESSION['id_utilisateur'] = $c->GetIdUtilisateur();
            $_SESSION['token'] = $token;

            header('Location: ?a=mon_compte');
            exit;

        } else {
            $j->Enregistrer();
            $notif_erreur = "Identifiant ou mot de passe incorrect";
        }

    } else {
        $notif_erreur = "Veuillez remplir tous les champs";
    }
}
