<?php
require __DIR__ . '/../conf/pdo.php';
require __DIR__ . '/../models/Client.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['adrMail']) && !empty($_POST['mdp'])) {

        $mail = trim($_POST['adrMail']);
        $mdp_saisi = trim($_POST['mdp']);

        $c = new Client($pdo);

        if (!$c->RechercheClient($mail)) {

            $c->SetAdrMail($mail);
            $c->SetIdUtilisateur($c->GenerationId());
            $c->SetMdp($c->Hasher($mdp_saisi));
            // $c->SetEstValide(1);
            $c->SetDateCreation((new DateTime())->format('Y-m-d H:i:s'));

            if ($c->CreationClient()) {
                header('Location: ?a=connexion');
                exit;
            }
            
        } else {
            $notif_erreur = "l'adresse mail existe";
        }

    } else {
        $notif_erreur = "Veuillez remplir tous les champs";
    }
}