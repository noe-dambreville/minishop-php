<?php
class Utilisateur
{
    private $pdo;
    private $idUtilisateur;
    private $mdp;
    private $est_valide;
    private $token;
    private $token_valide;
    private $date_creation;

    function __construct(PDO $pdo, $idUtilisateur, $mdp, $est_valide, $token, $token_valide, $date_creation = null)
    {
        $this->pdo = $pdo;
        $this->idUtilisateur = $idUtilisateur;
        $this->mdp = $mdp;
        $this->est_valide = $est_valide;
        $this->token = $token;
        $this->token_valide = $token_valide;
        $this->date_creation = $date_creation;
    }

    public function GetIdUtilisateur()
    {
        return $this->idUtilisateur;
    }
    public function GetMdp()
    {
        return $this->mdp;
    }
    public function GetEst_valide()
    {
        return $this->est_valide;
    }
    public function GetToken()
    {
        return $this->token;
    }
    public function GetToken_valide()
    {
        return $this->token_valide;
    }
    public function GetDate_creation()
    {
        return $this->date_creation;
    }

    public function SetIdUtilisateur($idUtilisateur)
    {
        $this->idUtilisateur = $idUtilisateur;
    }
    public function SetMdp($mdp)
    {
        $this->mdp = $mdp;
    }
    public function SetEst_valide($est_valide)
    {
        $this->est_valide = $est_valide;
    }
    public function SetToken($token)
    {
        $this->token = $token;
    }
    public function SetToken_valide($token_valide)
    {
        $this->token_valide = $token_valide;
    }
    public function SetDate_creation($date_creation)
    {
        $this->date_creation = $date_creation;
    }
}