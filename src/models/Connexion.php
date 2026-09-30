<?php
require_once __DIR__ . '/../traits/Generateur.php';

class Connexion
{
    use Generateur;

    private $pdo;
    private $idConnexion;
    private $adrIp;
    private $dateHeure;
    private $statut;
    private $idUtilisateur;

    function __construct(PDO $pdo, $adrIp = null, $dateHeure = null, $statut = null, $idUtilisateur = null)
    {
        $this->pdo = $pdo;
        $this->adrIp = $adrIp;
        $this->dateHeure = $dateHeure;
        $this->statut = $statut;
        $this->idUtilisateur = $idUtilisateur;
    }

    // GETTERS
    public function GetIdConnexion()
    {
        return $this->idConnexion;
    }
    public function GetAdrIp()
    {
        return $this->adrIp;
    }
    public function GetDateHeure()
    {
        return $this->dateHeure;
    }
    public function GetStatut()
    {
        return $this->statut;
    }
    public function GetIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    // SETTERS
    public function SetAdrIp($adrIp)
    {
        $this->adrIp = $adrIp;
    }
    public function SetDateHeure($dateHeure)
    {
        $this->dateHeure = $dateHeure;
    }
    public function SetStatut($statut)
    {
        $this->statut = $statut;
    }
    public function SetIdUtilisateur($idUtilisateur)
    {
        $this->idUtilisateur = $idUtilisateur;
    }
    
     // Vrai si l'IP OU le compte a atteint le seuil d'échecs sur la fenêtre récente
    public function TropDeTentatives($adrIp, $idUtilisateur = null, $minutes = 15, $seuil = 5)
    {
        if ($this->NombreEchecsParIp($adrIp, $minutes) >= $seuil) {
            return true;
        }

        return $idUtilisateur !== null && $this->NombreEchecsParCompte($idUtilisateur, $minutes) >= $seuil;
    }

    // CREATE
    public function Enregistrer()
    {
        try {
            $req = "INSERT INTO Connexion (id_connexion, adr_ip, date_heure, statut, id_utilisateur)
                VALUES (:id_connexion, :adr_ip, :date_heure, :statut, :id_utilisateur)";

            $this->idConnexion = $this->GenerationId();

            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':id_connexion', $this->idConnexion);
            $stmt->bindParam(':adr_ip', $this->adrIp);
            $stmt->bindParam(':date_heure', $this->dateHeure);
            $stmt->bindParam(':statut', $this->statut);
            $stmt->bindParam(':id_utilisateur', $this->idUtilisateur);

            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    // READ
    private function NombreEchecsParIp($adrIp, $minutes)
    {
        try {
            $debutFenetre = (new DateTime())->modify("-{$minutes} minutes")->format('Y-m-d H:i:s');

            $req = "SELECT COUNT(*) AS nombre FROM Connexion
                WHERE statut = 0 AND adr_ip = :adr_ip
                AND date_heure >= :debut_fenetre";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':adr_ip', $adrIp);
            $stmt->bindValue(':debut_fenetre', $debutFenetre);
            $stmt->execute();

            return (int) $stmt->fetch(PDO::FETCH_ASSOC)['nombre'];
        } catch (PDOException) {
            return 0;
        }
    }

    private function NombreEchecsParCompte($idUtilisateur, $minutes)
    {
        try {
            $debutFenetre = (new DateTime())->modify("-{$minutes} minutes")->format('Y-m-d H:i:s');

            $req = "SELECT COUNT(*) AS nombre FROM Connexion
                WHERE statut = 0 AND id_utilisateur = :id_utilisateur
                AND date_heure >= :debut_fenetre";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':id_utilisateur', $idUtilisateur);
            $stmt->bindValue(':debut_fenetre', $debutFenetre);
            $stmt->execute();

            return (int) $stmt->fetch(PDO::FETCH_ASSOC)['nombre'];
        } catch (PDOException) {
            return 0;
        }
    }

    // DELETE
    public function Supprimer($idConnexion)
    {
        try {
            $req = "DELETE FROM Connexion WHERE id_connexion = :id_connexion";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':id_connexion', $idConnexion);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }
}
