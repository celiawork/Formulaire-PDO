
CREATE TABLE utilisateur (
    id int(255) unsigned NOT NULL AUTO_INCREMENT,
    nom varchar(50) DEFAULT NULL,
    prenom varchar(50) DEFAULT NULL,
    mail varchar(191) DEFAULT NULL,
    mdp text,
    date_creation_compte datetime DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY mail (mail)
)