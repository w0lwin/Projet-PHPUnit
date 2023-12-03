-- MariaDB dump 10.19-11.1.2-MariaDB, for osx10.19 (arm64)
--
-- Host: 91.134.89.49    Database: appCuisine
-- ------------------------------------------------------
-- Server version	10.11.4-MariaDB-1~deb12u1

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `categorie_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`categorie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(2,'plat'),
(3,'desserts'),
(6,'entrée'),
(10,'cocktail'),
(12,'aperetif');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_ingredient` varchar(255) DEFAULT NULL,
  `unite_mesure` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ingredient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredients`
--

LOCK TABLES `ingredients` WRITE;
/*!40000 ALTER TABLE `ingredients` DISABLE KEYS */;
INSERT INTO `ingredients` VALUES
(1,'tomate','g'),
(2,'pomme de terre','g'),
(3,'laitue','g'),
(4,'tome','g'),
(5,'caviar','g'),
(6,'gruyère','g'),
(7,'courgette','g'),
(8,'aubergine','g'),
(9,'pate','g'),
(10,'cote de boeuf','g'),
(11,'poulet','g');
/*!40000 ALTER TABLE `ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recette_ingredients`
--

DROP TABLE IF EXISTS `recette_ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recette_ingredients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recette_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `Quantite` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recette_ingredients`
--

LOCK TABLES `recette_ingredients` WRITE;
/*!40000 ALTER TABLE `recette_ingredients` DISABLE KEYS */;
INSERT INTO `recette_ingredients` VALUES
(39,54,1,1),
(40,54,2,1),
(41,55,9,11),
(42,56,7,1),
(43,56,8,1),
(44,56,9,1),
(45,56,10,1),
(46,57,3,1),
(47,57,4,1),
(48,58,5,1),
(49,58,10,1),
(50,59,6,1),
(51,60,3,4),
(52,61,6,14),
(53,62,6,1),
(54,63,1,4);
/*!40000 ALTER TABLE `recette_ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recettes`
--

DROP TABLE IF EXISTS `recettes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recettes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_recette` varchar(255) NOT NULL,
  `instruction` text NOT NULL,
  `temps_preparation` int(11) NOT NULL,
  `temps_cuisson` int(11) NOT NULL,
  `difficulte` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recettes`
--

LOCK TABLES `recettes` WRITE;
/*!40000 ALTER TABLE `recettes` DISABLE KEYS */;
INSERT INTO `recettes` VALUES
(54,'salade césar','1.faire la vinégraite\r\n2.Mélanger la salde',20,1,1,2),
(55,'carbonara','zeaeaz',10,20,1,2),
(56,'ramen','1.coupé la viande\r\n2.ajouté le bouillon',20,20,2,2),
(57,'pizza','1.récupérer la pate\r\n2.mettre la crème fraiche',20,10,1,2),
(58,'tiramisu','1.Ajouter la café\r\n2.Souppoudré de coco',50,120,3,3),
(59,'Mojito','1.ecraser la menthe\r\n2.Mélanger avec le rhum',5,1,1,10),
(60,'bolognaise','rzerz\r\nezrz',10,20,2,2),
(61,'mille feuille','ez\r\nea\r\neaee',20,5,1,3),
(62,'planteur','ezaea',10,20,1,10),
(63,'Oeufs mimosa','Placez les œufs dans une casserole et couvrez-les d\'eau froide.\r\nPortez l\'eau à ébullition, puis réduisez le feu et laissez cuire les œufs pendant environ 10 minutes.\r\nRetirez les œufs de l\'eau chaude et placez-les dans un bol d\'eau froide pour les refroidir rapidement.\r\nUne fois refroidis, écalez les œufs et coupez-les en deux dans le sens de la longueur.',10,20,2,6);
/*!40000 ALTER TABLE `recettes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-12-03 22:50:42
