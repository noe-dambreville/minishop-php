<?php
trait Generateur
{
    // Générer un id
    public function GenerationId()
    {
        return substr(md5(time() . random_bytes(16)), 0, 16);
    }

    // Génération du token
    public function GenerationToken()
    {
        return bin2hex(random_bytes(32));
    }

    // Génération d'un code PIN à 6 chiffres
    public function GenerationPin()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}