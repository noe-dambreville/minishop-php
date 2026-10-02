<?php
require_once __DIR__ . '/env.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Envoie un mail personnalisé via SMTP avec PHPMailer
function envoyerMail($destinataire, $sujet, $contenuHTML, $nomDestinataire = '', $nomExpediteur = '')
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $_ENV['APP_MAIL_HST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['APP_MAIL_UTI'];
        $mail->Password = $_ENV['APP_MAIL_MDP'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['APP_MAIL_PRT'];
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($_ENV['APP_MAIL_UTI'], $nomExpediteur);
        $mail->addAddress($destinataire, $nomDestinataire);

        $mail->isHTML(true);
        $mail->Subject = $sujet;
        $mail->Body = $contenuHTML;

        return $mail->send();
    } catch (Exception) {
        return false;
    }
}

// Envoie le code PIN de confirmation d'inscription
function envoyerCodePin($mail, $pin)
{
    $sujet = "Votre code est : $pin";
    $contenuHTML = "
        <html>
            <body>
                <p>Votre code de vérification :</p>
                <p>$pin</p>
                <p>Ce code expire dans 15 minutes</p>
            </body>
            <footer>Boutique de café</footer>
        </html>
    ";
    return envoyerMail($mail, $sujet, $contenuHTML, '', 'Boutique de café');
}
