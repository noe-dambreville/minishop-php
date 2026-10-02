<?php
require_once __DIR__ . '/../conf/pdo.php';
require_once __DIR__ . '/../conf/mail.php';
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Pin.php';

$idAttente = $_SESSION['id_utilisateur_en_attente'] ?? null;

if ($idAttente === null) {
    header('Location: ?a=connexion');
    exit;
}

$ca = new Client($pdo);

if (!$ca->RechercheClientParId($idAttente)) {
    unset($_SESSION['id_utilisateur_en_attente']);
    header('Location: ?a=connexion');
    exit;
}

$emailMasque = $ca->GetAdrMailMasque();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['renvoyer'])) {

    $codePin = $ca->GenerationPin();

    $p = new Pin($pdo, $codePin, (new DateTime('+15 minutes'))->format('Y-m-d H:i:s'), 0, $idAttente);
    $p->Enregistrer();

    envoyerCodePin($ca->GetAdrMail(), $codePin);

    $notif_erreur = "Un nouveau code vous a été envoyé";

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['code_pin'])) {

    $codeSaisi = trim($_POST['code_pin']);

    $p = new Pin($pdo);

    if (!$p->RechercheParUtilisateur($idAttente)) {
        $notif_erreur = "Aucun code en attente, veuillez en demander un nouveau";

    } elseif ($p->TropDeTentatives()) {
        $notif_erreur = "Trop de tentatives, veuillez demander un nouveau code";

    } elseif ($p->EstExpire()) {
        $notif_erreur = "Code expiré, veuillez en demander un nouveau";

    } elseif ($p->EstCorrect($codeSaisi)) {

        $u = new Utilisateur($pdo);
        
        $u->RechercheUtilisateur($idAttente);
        $u->SetStatut('valide');
        $u->MajUtilisateur($idAttente);

        $p->Supprimer();

        unset($_SESSION['id_utilisateur_en_attente']);

        header('Location: ?a=connexion');
        exit;

    } else {
        $p->IncrementerTentatives();
        $notif_erreur = "Code incorrect";
    }
}
