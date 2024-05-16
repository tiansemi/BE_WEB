-- MariaDB dump 10.19  Distrib 10.6.11-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: be_web
-- ------------------------------------------------------
-- Server version	10.6.11-MariaDB-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `CLASSE`
--

DROP TABLE IF EXISTS `CLASSE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CLASSE` (
  `Code_cl` char(7) NOT NULL,
  `c_fil` char(6) NOT NULL,
  `Libelle` varchar(223) DEFAULT NULL,
  `Effectif` int(11) NOT NULL,
  PRIMARY KEY (`Code_cl`),
  KEY `c_fil` (`c_fil`),
  CONSTRAINT `CLASSE_ibfk_1` FOREIGN KEY (`c_fil`) REFERENCES `FILIERE` (`Code_fil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CLASSE`
--

LOCK TABLES `CLASSE` WRITE;
/*!40000 ALTER TABLE `CLASSE` DISABLE KEYS */;
INSERT INTO `CLASSE` VALUES ('CL001','INFO','Informatique 1',40),('CL002','MATH','Mathématiques 1',30),('CL003','PHY','Physique 1',30),('CL004','CHIM','Chimie 1',11),('CL005','BIO','Biologie 1',10),('STIC_1A','STIC','',25),('STIC_1B','STIC','',26);
/*!40000 ALTER TABLE `CLASSE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `COURS`
--

DROP TABLE IF EXISTS `COURS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `COURS` (
  `Code_cours` char(15) NOT NULL,
  `c_ens` char(7) NOT NULL,
  `Intitule` varchar(44) NOT NULL,
  PRIMARY KEY (`Code_cours`),
  KEY `c_ens` (`c_ens`),
  CONSTRAINT `COURS_ibfk_1` FOREIGN KEY (`c_ens`) REFERENCES `ENSEIGNANT` (`Code_ens`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `COURS`
--

LOCK TABLES `COURS` WRITE;
/*!40000 ALTER TABLE `COURS` DISABLE KEYS */;
INSERT INTO `COURS` VALUES ('ALG_MATH','ENS002','Algèbre'),('GEN_BIO','ENS005','Génétique'),('MECA_PHY','ENS003','Mécanique'),('PROG_INFO','ENS001','Programmation'),('TERMO_CH','ENS004','Thermochimie');
/*!40000 ALTER TABLE `COURS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ENSEIGNANT`
--

DROP TABLE IF EXISTS `ENSEIGNANT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ENSEIGNANT` (
  `Code_ens` char(7) NOT NULL,
  `Nom` varchar(30) NOT NULL,
  `Prenom` varchar(30) NOT NULL,
  `Contact` varchar(11) NOT NULL,
  `password` varchar(223) NOT NULL,
  `status` char(10) NOT NULL,
  PRIMARY KEY (`Code_ens`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ENSEIGNANT`
--

LOCK TABLES `ENSEIGNANT` WRITE;
/*!40000 ALTER TABLE `ENSEIGNANT` DISABLE KEYS */;
INSERT INTO `ENSEIGNANT` VALUES ('ENS000','Ali','Kone','0102032405','b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86','ad'),('ENS001','Dupont','Jean','0102030405','bc547750b92797f955b36112cc9bdd5cddf7d0862151d03a167ada8995aa24a9ad24610b36a68bc02da24141ee51670aea13ed6469099a4453f335cb239db5da','pp'),('ENS002','Martin','Pierre','0607080910','92a891f888e79d1c2e8b82663c0f37cc6d61466c508ec62b8132588afe354712b20bb75429aa20aa3ab7cfcc58836c734306b43efd368080a2250831bf7f363f','actif'),('ENS003','Lefevre','Claire','1112131415','2a64d6563d9729493f91bf5b143365c0a7bec4025e1fb0ae67e307a0c3bed1c28cfb259fc6be768ab0a962b1e2c9527c5f21a1090a9b9b2d956487eb97ad4209','actif'),('ENS004','Roux','Marie','1617181920','11961811bd4e11f23648afbd2d5c251d2784827147f1731be010adaf0ab38ae18e5567c6fd1bee50a4cd35fb544b3c594e7d677efa7ca01c2a2cb47f8fb12b17','actif'),('ENS005','Simon','Paul','2122232425','39c6f5329e959b2af0deb0f8dacbcdf5418204f46baed50388f62b047c9223c66ff470031ecd653a49f6eff6fa876811e46f0c269390a8bf61f4f983729e1083','actif');
/*!40000 ALTER TABLE `ENSEIGNANT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ETUDIANT`
--

DROP TABLE IF EXISTS `ETUDIANT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ETUDIANT`
--

LOCK TABLES `ETUDIANT` WRITE;
/*!40000 ALTER TABLE `ETUDIANT` DISABLE KEYS */;
INSERT INTO `ETUDIANT` VALUES ('24INP00001','STIC_1B','MOULO','OHOLO JEAN NOEL','2000-12-25','BASSAM','b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86'),('INP00001','CL001','Petit','Jacques','2000-01-01','Paris','b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86'),('INP00002','CL001','Grand','Sophie','2000-02-02','Lyon','b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86'),('INP00003','CL002','Leblanc','Nicolas','2000-03-03','Marseille','b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86'),('INP00004','CL002','Leroy','Julie','2000-04-04','Toulouse','b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86'),('INP00005','CL003','Moreau','Lucas','2000-05-05','Nice','b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86');
/*!40000 ALTER TABLE `ETUDIANT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `FILIERE`
--

DROP TABLE IF EXISTS `FILIERE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FILIERE` (
  `Code_fil` char(6) NOT NULL,
  `Libelle_fil` varchar(223) NOT NULL,
  PRIMARY KEY (`Code_fil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `FILIERE`
--

LOCK TABLES `FILIERE` WRITE;
/*!40000 ALTER TABLE `FILIERE` DISABLE KEYS */;
INSERT INTO `FILIERE` VALUES ('BIO','Biologie'),('CHIM','Chimie'),('FR','Français'),('INFO','Informatique'),('MATH','Mathématiques'),('PHY','Physique'),('STIC','Sciences et Technologies de l\'Information et de la Communication');
/*!40000 ALTER TABLE `FILIERE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SUIVRE`
--

DROP TABLE IF EXISTS `SUIVRE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SUIVRE`
--

LOCK TABLES `SUIVRE` WRITE;
/*!40000 ALTER TABLE `SUIVRE` DISABLE KEYS */;
INSERT INTO `SUIVRE` VALUES ('INP00001','PROG_INFO',15.5,'2024-05-12 14:09:54'),('INP00002','PROG_INFO',16.5,'2024-05-12 14:09:54'),('INP00003','ALG_MATH',14,'2024-05-12 14:09:54'),('INP00004','ALG_MATH',13.5,'2024-05-12 14:09:54'),('INP00005','GEN_BIO',12,'2024-05-12 14:09:54');
/*!40000 ALTER TABLE `SUIVRE` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-16 15:20:49
