DROP TABLE IF EXISTS `SUIVRE`;
DROP TABLE IF EXISTS `ETUDIANT`;
DROP TABLE IF EXISTS `COURS`;
DROP TABLE IF EXISTS `ENSEIGNANT`;
DROP TABLE IF EXISTS `CLASSE`;
DROP TABLE IF EXISTS `FILIERE`;

CREATE TABLE `FILIERE` (
  `Code_fil` char(6) NOT NULL,
  `Libelle_fil` varchar(223) NOT NULL,
  PRIMARY KEY (`Code_fil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `FILIERE` VALUES 
('BIO','Biologie'),
('CHIM','Chimie');
('MATH','Mathématique');
('STIC',"Sciences et Technologies de l'Information et de la Communication");

CREATE TABLE `CLASSE` (
  `Code_cl` char(7) NOT NULL,
  `c_fil` char(6) NOT NULL,
  `Libelle` varchar(223) DEFAULT NULL,
  `Effectif` int(11) NOT NULL,
  PRIMARY KEY (`Code_cl`),
  KEY `c_fil` (`c_fil`),
  CONSTRAINT `CLASSE_ibfk_1` FOREIGN KEY (`c_fil`) REFERENCES `FILIERE` (`Code_fil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `CLASSE` VALUES 
('CL001','BIO','Biologie 1',40),
('CL002','CHIM','Chimie 1',30);

CREATE TABLE `ENSEIGNANT` (
  `Code_ens` char(7) NOT NULL,
  `Nom` varchar(30) NOT NULL,
  `Prenom` varchar(30) NOT NULL,
  `Contact` varchar(11) NOT NULL,
  `password` varchar(223) NOT NULL,
  `status` char(10) NOT NULL,
  PRIMARY KEY (`Code_ens`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `ENSEIGNANT` VALUES 
('ENS000','Ali','Kone','0102032405',SHA2('string', 512),'ad'),
('ENS001','Dupont','Jean','0102030405',SHA2('string', 512),'pp');

CREATE TABLE `COURS` (
  `Code_cours` char(15) NOT NULL,
  `c_ens` char(7) NOT NULL,
  `Intitule` varchar(44) NOT NULL,
  PRIMARY KEY (`Code_cours`),
  KEY `c_ens` (`c_ens`),
  CONSTRAINT `COURS_ibfk_1` FOREIGN KEY (`c_ens`) REFERENCES `ENSEIGNANT` (`Code_ens`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `COURS` VALUES 
('ALG_MATH','ENS000','Algèbre'),
('GEN_BIO','ENS001','Génétique');

CREATE TABLE `ETUDIANT` (
  `Matricule` char(10) NOT NULL,
  `c_cl` char(7) NOT NULL,
  `Nom` varchar(30) NOT NULL,
  `Prenom` varchar(30) NOT NULL,
  `Date_naiss` date NOT NULL,
  `Lieu_naiss` varchar(50) NOT NULL,
  `password` varchar(223) NOT NULL,
  PRIMARY KEY (`Matricule`),
  KEY `c_cl` (`c_cl`),
  CONSTRAINT `ETUDIANT_ibfk_1` FOREIGN KEY (`c_cl`) REFERENCES `CLASSE` (`Code_cl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `ETUDIANT` VALUES 
('24INP00001','CL001','MOULO','OHOLO JEAN NOEL','2000-12-25','BASSAM',SHA2('string', 512)),
('INP00001','CL002','Petit','Jacques','2000-01-01','Paris',SHA2('string', 512));

CREATE TABLE `SUIVRE` (
  `Matricule` char(10) NOT NULL,
  `Code_cours` char(15) NOT NULL,
  `Notes` float DEFAULT NULL,
  `Date_ob` datetime NOT NULL,
  PRIMARY KEY (`Matricule`,`Code_cours`),
  KEY `SUIVRE_ibfk_2` (`Code_cours`),
  CONSTRAINT `SUIVRE_ibfk_1` FOREIGN KEY (`Matricule`) REFERENCES `ETUDIANT` (`Matricule`),
  CONSTRAINT `SUIVRE_ibfk_2` FOREIGN KEY (`Code_cours`) REFERENCES `COURS` (`Code_cours`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `SUIVRE` VALUES 
('INP00001','ALG_MATH',15.5,'2024-05-12 14:09:54'),
('24INP00001','GEN_BIO',16.5,'2024-05-12 14:09:54');
