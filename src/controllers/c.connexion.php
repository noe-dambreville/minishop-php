<?php
require '../../conf/pdo.php';
require '../models/Utilisateur.php';
require '../models/Client.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['adrMail']) && empty($_POST['mdp'])) {

        $mail = trim($_POST['adrMail']);
        $mdp = trim($_POST['mdp']);

        $c = new Utilisateur($pdo);
        $c = new Client($pdo);

        $c->SetadrMail($mail);
        $c->SetMdp($mdp);


        if ($c->RechercheClient($mail) && password_verify($mdpSaisi, $c->GetMdp())) {}


    } else {

    }
}