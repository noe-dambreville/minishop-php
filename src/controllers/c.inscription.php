<?php
require_once __DIR__ . '/../conf/pdo/pdo_auth.php';
require_once __DIR__ . '/../conf/mail.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Pin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['adrMail']) && !empty($_POST['mdp'])) {

        $mail = trim($_POST['adrMail']);
        $mdp_saisi = trim($_POST['mdp']);

        $c = new Client($pdo_auth);

        if (!$c->RechercheClient($mail)) {

            $c->SetAdrMail($mail);
            $c->SetIdUtilisateur($c->GenerationId());
            $c->SetMdp($c->Hasher($mdp_saisi));
            $c->SetStatut('nonValide');
            $c->SetDateCreation((new DateTime())->format('Y-m-d H:i:s'));

            if ($c->CreationClient()) {
                $codePin = $c->GenerationPin();

                $pin = new Pin($pdo_auth, $codePin, (new DateTime('+15 minutes'))->format('Y-m-d H:i:s'), 0, $c->GetIdUtilisateur());
                $pin->Enregistrer();

                envoyerCodePin($mail, $codePin);

                $_SESSION['id_utilisateur_en_attente'] = $c->GetIdUtilisateur();

                header('Location: ?a=confirmation');
                exit;
            }

        } else {
            $notif_erreur = "l'adresse mail existe";
        }

    } else {
        $notif_erreur = "Veuillez remplir tous les champs";
    }
}