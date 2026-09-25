<?php

class Contenir
{
    private $pdo;
    private $idCommande;
    private $idProduit;
    private $quantiteUnitaire;
    private $prixUnitaireVente;

    function __construct(PDO $pdo, $idCommande, $idProduit, $quantiteUnitaire, $prixUnitaireVente)
    {
        $this->pdo = $pdo;
        $this->idCommande = $idCommande;
        $this->idProduit = $idProduit;
        $this->quantiteUnitaire = $quantiteUnitaire;
        $this->prixUnitaireVente = $prixUnitaireVente;
    }

    public function GetIdCommande()
    {
        return $this->idCommande;
    }
    public function GetIdProduit()
    {
        return $this->idProduit;
    }
    public function GetQuantiteUnitaire()
    {
        return $this->quantiteUnitaire;
    }
    public function GetPrixUnitaireVente()
    {
        return $this->prixUnitaireVente;
    }

    public function SetIdCommande($idCommande)
    {
        $this->idCommande = $idCommande;
    }
    public function SetIdProduit($idProduit)
    {
        $this->idProduit = $idProduit;
    }
    public function SetQuantiteUnitaire($quantiteUnitaire)
    {
        $this->quantiteUnitaire = $quantiteUnitaire;
    }
    public function SetPrixUnitaireVente($prixUnitaireVente)
    {
        $this->prixUnitaireVente = $prixUnitaireVente;
    }

    function GetMax()
    {
        $stmt = $this->pdo->prepare("SELECT MAX(idCommande) AS max FROM Contenir");
        $stmt->execute();
        $max = $stmt->fetch(PDO::FETCH_ASSOC);
        return $max["max"] + 1;
    }

    public function Create()
    {
        try {
            $req = "INSERT INTO Contenir(idCommande, idProduit, quantiteUnitaire, prixUnitaireVente) VALUES (:idCommande, :idProduit, :quantiteUnitaire, :prixUnitaireVente)";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':idCommande', $this->GetMax());
            $stmt->bindParam(':idProduit', $this->idProduit);
            $stmt->bindParam(':quantiteUnitaire', $this->quantiteUnitaire);
            $stmt->bindParam(':prixUnitaireVente', $this->prixUnitaireVente);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la création des données : " . $e->GetMessage();
            return false;
        }
    }

    public function Delete($idCommande)
    {
        try {
            $req = "DELETE FROM Contenir WHERE idCommande = :idCommande";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':idCommande', $idCommande);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression des données : " . $e->GetMessage();
        }
    }
}
