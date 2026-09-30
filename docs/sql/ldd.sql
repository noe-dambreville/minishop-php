-- Data Definition Language

CREATE TABLE Utilisateur(
   id_utilisateur VARCHAR(16),
   mdp VARCHAR(255),
   est_valide TINYINT(1),
   token VARCHAR(64),
   token_expire DATETIME,
   date_creation DATETIME,
   role ENUM('client','admin') NOT NULL DEFAULT 'client'
   PRIMARY KEY(id_utilisateur)
);

CREATE TABLE Client(
   id_utilisateur VARCHAR(16),
   adr_mail VARCHAR(255),
   prenom VARCHAR(30),
   nom VARCHAR(20),
   PRIMARY KEY(id_utilisateur),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Administrateur(
   id_utilisateur VARCHAR(16),
   identifiant VARCHAR(20),
   niveau_acces VARCHAR(50),
   PRIMARY KEY(id_utilisateur),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Connexion(
   id_connexion VARCHAR(16),
   adr_ip VARCHAR(45),
   date_heure DATETIME,
   statut TINYINT(1),
   id_utilisateur VARCHAR(16),
   PRIMARY KEY(id_connexion),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Commande(
   id_commande VARCHAR(16),
   date_heure DATETIME,
   statut TINYINT(1),
   id_utilisateur VARCHAR(16) NOT NULL,
   PRIMARY KEY(id_commande),
   FOREIGN KEY(id_utilisateur) REFERENCES Client(id_utilisateur)
);

CREATE TABLE Produit(
   id_produit VARCHAR(16),
   nom VARCHAR(50),
   description TEXT,
   prix_unitaire DECIMAL(15,2),
   quantite_stock INT,
   PRIMARY KEY(id_produit)
);

CREATE TABLE Panier(
   id_panier VARCHAR(16),
   date_creation DATETIME,
   date_maj DATETIME,
   id_utilisateur VARCHAR(16) NOT NULL,
   PRIMARY KEY(id_panier),
   UNIQUE(id_utilisateur),
   FOREIGN KEY(id_utilisateur) REFERENCES Client(id_utilisateur)
);

CREATE TABLE Contenir(
   id_commande VARCHAR(16),
   id_produit VARCHAR(16),
   quantite_commandee INT,
   prix_unitaire_vente DECIMAL(15,2),
   PRIMARY KEY(id_commande, id_produit),
   FOREIGN KEY(id_commande) REFERENCES Commande(id_commande),
   FOREIGN KEY(id_produit) REFERENCES Produit(id_produit)
);

CREATE TABLE Gerer(
   id_utilisateur VARCHAR(16),
   id_produit VARCHAR(16),
   type_action VARCHAR(50),
   date_heure DATETIME,
   PRIMARY KEY(id_utilisateur, id_produit),
   FOREIGN KEY(id_utilisateur) REFERENCES Administrateur(id_utilisateur),
   FOREIGN KEY(id_produit) REFERENCES Produit(id_produit)
);

CREATE TABLE Valider(
   id_commande VARCHAR(16),
   date_validation DATETIME,
   id_utilisateur VARCHAR(16) NOT NULL,
   PRIMARY KEY(id_commande),
   FOREIGN KEY(id_commande) REFERENCES Commande(id_commande),
   FOREIGN KEY(id_utilisateur) REFERENCES Administrateur(id_utilisateur)
);

CREATE TABLE Composer(
   id_produit VARCHAR(16),
   id_panier VARCHAR(16),
   quantite INT,
   PRIMARY KEY(id_produit, id_panier),
   FOREIGN KEY(id_produit) REFERENCES Produit(id_produit),
   FOREIGN KEY(id_panier) REFERENCES Panier(id_panier)
);
  