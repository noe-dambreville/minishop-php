<?php
require '../../conf/env.php';

trait Chiffrement
{
    // Chiffrer les données via aes-256-ecb + clef
    public function chiffrer($var)
    {
        $clef = $_ENV['APP_CLEF'] ?? '';
        return base64_encode(openssl_encrypt($var, 'aes-256-ecb', $clef, OPENSSL_RAW_DATA));
    }
    
    // Déhiffrer les données via aes-256-ecb + clef
    public function dechiffrer($var)
    {
        $clef = $_ENV['APP_CLEF'] ?? '';
        return openssl_decrypt(base64_decode($var), 'aes-256-ecb', $clef, OPENSSL_RAW_DATA);
    }
   
    // hasher -> sallage + poivrage    
    public function Hasher($mdp)
    {
        $poivre = $_ENV['APP_CLEF'] ?? '';
        $sel = password_hash($mdp . $poivre, PASSWORD_DEFAULT);
        return $sel;
    }

    public function MdpVerif($mdp)
    {
        $poivre = $_ENV['APP_CLEF'] ?? '';
        return password_verify($mdp . $poivre, $this->mdp);
    }
}