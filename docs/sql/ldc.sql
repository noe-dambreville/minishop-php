-- Data Control Language

CREATE USER 'app_auth'@'localhost' IDENTIFIED BY 'motdepasse_auth';
GRANT SELECT, INSERT, UPDATE ON minishop_bdd.Utilisateur TO 'app_auth'@'localhost';
GRANT SELECT, INSERT, UPDATE ON minishop_bdd.Client TO 'app_auth'@'localhost';
GRANT SELECT, INSERT, UPDATE ON minishop_bdd.Connexion TO 'app_auth'@'localhost';
GRANT SELECT, UPDATE ON minishop_bdd.administrateur TO 'app_auth'@'localhost';

-- CREATE USER 'app_stock'@'localhost' IDENTIFIED BY 'motdepasse_stock';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON minishop_bdd.Produit TO 'app_stock'@'localhost';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON minishop_bdd.Gerer TO 'app_stock'@'localhost';

FLUSH PRIVILEGES;