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
}