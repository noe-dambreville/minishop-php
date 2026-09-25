<?php
require '../models/Utilisateur.php';
require '../models/Client.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['adrMail']) && empty($_POST['mdp'])) {

        $mail = trim($_POST['adrMail']);
        $mdp = trim($_POST['mdp']);

        $c = new Client($pdo);

        $c->SetAdr_mail($mail);
        $c->SetMdp($mdp);
        $c->


    } else {

    }
}