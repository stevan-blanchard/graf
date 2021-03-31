-- MySQL dump 10.18  Distrib 10.3.27-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: graf
-- ------------------------------------------------------
-- Server version	10.3.27-MariaDB-0+deb10u1

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
-- Table structure for table `RAF`
--

DROP TABLE IF EXISTS `RAF`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RAF` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `duration` decimal(11,2) NOT NULL,
  `priority` int(4) NOT NULL,
  `deadline` date NOT NULL,
  `includeInProject_id` int(11) NOT NULL,
  `un_tiers` int(1) DEFAULT NULL,
  `deux_tiers` int(1) DEFAULT NULL,
  `trois_tiers` int(1) DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  KEY `includeInProject_id` (`includeInProject_id`),
  CONSTRAINT `RAF_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`),
  CONSTRAINT `RAF_ibfk_2` FOREIGN KEY (`includeInProject_id`) REFERENCES `includeInProjects` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RAF`
--

LOCK TABLES `RAF` WRITE;
/*!40000 ALTER TABLE `RAF` DISABLE KEYS */;
INSERT INTO `RAF` VALUES (2,1,'Afficher',9.00,2,'2021-04-02',4,1,1,1,NULL),(3,1,'déconnecter l’utilisateur si il supprime son nom d’auteur.',9.00,3,'2021-04-02',4,1,1,1,'A la suppression d’un nom d’auteur, revoir le fichier del-author.php pour déconnecter l’utilisateur si il supprime son nom d’auteur test test test test test test test test test test test test test test test test test test test test test test test'),(5,3,'trier les colonnes par ordre alphabétique',10.00,3,'2021-02-18',4,1,1,1,NULL),(7,4,'Faire de la pub',5.00,1,'2021-02-23',2,1,1,1,NULL),(8,1,'Stage de BTS',15.00,4,'2021-04-02',5,1,NULL,NULL,NULL),(9,1,'gérer l\'exportation vers un fichier .pdf',9.00,4,'2021-04-02',4,1,1,NULL,'les lignes ne s\'affiche pas correctement'),(10,1,'faire la documentation installation',8.00,1,'2021-04-02',4,1,1,NULL,NULL),(11,1,'faire la documentation utilisation',8.00,2,'2021-04-02',4,NULL,NULL,NULL,NULL),(12,1,'faire la documentation de codage',8.00,1,'2021-04-02',4,1,1,NULL,NULL),(13,1,'test',1.00,1,'2021-03-26',4,1,1,NULL,NULL),(14,1,'test',2.00,2,'2021-03-26',4,1,NULL,NULL,NULL),(15,1,'test',1.00,3,'2021-03-23',4,1,NULL,NULL,NULL),(16,1,'test',6.00,2,'2021-03-23',4,NULL,NULL,NULL,NULL),(17,1,'test 7',5.00,1,'2021-03-23',4,1,1,NULL,NULL),(18,1,'$pdf-&gt;AddPage(\'L\', \'A4\', 0);',1.00,3,'2021-03-22',4,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `RAF` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `author`
--

DROP TABLE IF EXISTS `author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `author`
--

LOCK TABLES `author` WRITE;
/*!40000 ALTER TABLE `author` DISABLE KEYS */;
INSERT INTO `author` VALUES (1,'stevan'),(3,'Denis'),(4,'Laura');
/*!40000 ALTER TABLE `author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `img_id` int(11) NOT NULL AUTO_INCREMENT,
  `img_nom` varchar(50) NOT NULL,
  `img_taille` varchar(25) NOT NULL,
  `img_type` varchar(25) NOT NULL,
  `img_desc` varchar(100) NOT NULL,
  `img_blob` blob NOT NULL,
  PRIMARY KEY (`img_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `includeInProjects`
--

DROP TABLE IF EXISTS `includeInProjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `includeInProjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `includeInProjects`
--

LOCK TABLES `includeInProjects` WRITE;
/*!40000 ALTER TABLE `includeInProjects` DISABLE KEYS */;
INSERT INTO `includeInProjects` VALUES (1,'aucune','#0000ff'),(2,'communication','#FF00FC'),(3,'administratif','#0eff00'),(4,'GRAF','#0521ff'),(5,'Stage','#b40808');
/*!40000 ALTER TABLE `includeInProjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membre`
--

DROP TABLE IF EXISTS `membre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `membre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(1) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `membre_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membre`
--

LOCK TABLES `membre` WRITE;
/*!40000 ALTER TABLE `membre` DISABLE KEYS */;
INSERT INTO `membre` VALUES (1,1,'Terassque','stevan.blanchard@bts-malraux.net','$2y$10$bu36vQ0c2HrHMkwihZjkhuM8kXVn/zA3zMX6tc3pWY218dCKDrzwW'),(3,3,'Denis','stevan.blanchard@gmail.com','$2y$10$YnunU66vV2cPg4s8DMStVe4se2rdcZxTuxhsc.Gw1x6ThbjcS6Lqy'),(4,4,'Camille','stevan@gmail.com','$2y$10$ysbl4HbbcsSCyDxZw1xkB.SDrEnfi3egRnsiQLzjBW0Y8KS/aFJKm');
/*!40000 ALTER TABLE `membre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `old_RAF`
--

DROP TABLE IF EXISTS `old_RAF`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `old_RAF` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `raf_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `duration` decimal(11,2) NOT NULL,
  `priority` int(4) NOT NULL,
  `deadline` date NOT NULL,
  `includeInProject_id` int(11) NOT NULL,
  `un_tiers` int(1) DEFAULT NULL,
  `deux_tiers` int(1) DEFAULT NULL,
  `trois_tiers` int(1) DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL,
  `date_modif` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `old_RAF`
--

LOCK TABLES `old_RAF` WRITE;
/*!40000 ALTER TABLE `old_RAF` DISABLE KEYS */;
INSERT INTO `old_RAF` VALUES (1,2,2,'test',9.00,4,'2021-02-22',4,1,1,1,'',''),(2,2,1,'Afficher',9.00,3,'2021-04-02',4,1,1,1,'','2021-02-22 15:31:34'),(3,2,1,'non disponible',8.00,4,'2021-04-02',4,1,1,1,'','2021-02-22 15:50:10'),(4,3,1,'non disponible',18.00,2,'2021-04-02',4,1,1,1,'A la suppression d’un nom d’auteur, revoir le fichier del-author.php pour déconnecter l’utilisateur si il supprime son nom d’auteur','2021-02-22 15:54:41'),(5,2,1,'non disponible',5.00,2,'2021-04-02',4,1,1,1,'','2021-03-09 11:15:16'),(6,5,3,'non disponible',8.00,3,'2021-02-18',4,1,1,1,'','2021-03-09 11:17:51'),(7,7,4,'non disponible',15.00,1,'2021-02-23',2,1,1,1,'','2021-03-09 11:18:28'),(8,8,1,'non disponible',8.00,4,'2021-04-02',5,1,0,0,'','2021-03-09 14:08:58'),(9,8,1,'Stage de BTS',9.00,4,'2021-04-02',5,1,0,0,'','2021-03-09 14:11:23'),(10,2,1,'Afficher',10.00,2,'2021-04-02',4,1,1,1,'','2021-03-10 10:16:47'),(11,9,1,'gérer l\'exportation vers un fichier .pdf',10.00,4,'2021-04-02',4,1,1,0,'les lignes ne s\'affiche pas correctement','2021-03-10 10:38:57'),(12,3,1,'déconnecter l’utilisateur si il supprime son nom d’auteur.',9.00,3,'2021-04-02',4,1,1,1,'A la suppression d’un nom d’auteur, revoir le fichier del-author.php pour déconnecter l’utilisateur si il supprime son nom d’auteur','2021-03-12 14:31:19'),(13,3,1,'déconnecter l’utilisateur si il supprime son nom d’auteur.',9.00,3,'2021-04-02',4,1,1,1,'A la suppression d’un nom d’auteur, revoir le fichier del-author.php pour déconnecter l’utilisateur si il supprime son nom d’auteur','2021-03-12 14:31:37'),(14,3,1,'déconnecter l’utilisateur si il supprime son nom d’auteur.',9.00,3,'2021-04-02',4,1,1,1,'A la suppression d’un nom d’auteur, revoir le fichier del-author.php pour déconnecter l’utilisateur si il supprime son nom d’auteur','2021-03-12 14:31:57'),(15,3,1,'déconnecter l’utilisateur si il supprime son nom d’auteur.',9.00,3,'2021-04-02',4,1,1,1,'A la suppression d’un nom d’auteur, revoir le fichier del-author.php pour déconnecter l’utilisateur si il supprime son nom d’auteur','2021-03-12 14:32:37'),(16,3,1,'déconnecter l’utilisateur si il supprime son nom d’auteur.',9.00,3,'2021-04-02',4,1,1,1,'A la suppression d’un nom d’auteur, revoir le fichier del-author.php pour déconnecter l’utilisateur si il supprime son nom d’auteur test test test test test test test test test','2021-03-12 14:32:49'),(17,3,1,'déconnecter l’utilisateur si il supprime son nom d’auteur.',9.00,3,'2021-04-02',4,1,1,1,'A la suppression d’un nom d’auteur, revoir le fichier del-author.php pour déconnecter l’utilisateur si il supprime son nom d’auteur test test test test test test test test test test test test test test test','2021-03-12 14:33:03'),(18,3,1,'déconnecter l’utilisateur si il supprime son nom d’auteur.',9.00,3,'2021-04-02',4,1,1,1,'A la suppression d’un nom d’auteur, revoir le fichier del-author.php pour déconnecter l’utilisateur si il supprime son nom d’auteur test test test test test test test test test test test test test test test test test test','2021-03-12 14:33:32'),(19,3,1,'déconnecter l’utilisateur si il supprime son nom d’auteur.',9.00,3,'2021-04-02',4,1,1,1,'A la suppression d’un nom d’auteur, revoir le fichier del-author.php pour déconnecter l’utilisateur si il supprime son nom d’auteur test test test test test test test test test test test test test test test test test test','2021-03-12 14:33:52'),(20,10,1,'faire la documentation installation',4.00,1,'2021-04-02',4,1,1,0,NULL,'2021-03-29 11:25:12');
/*!40000 ALTER TABLE `old_RAF` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-03-31 14:16:12
