-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: feedback_clasificare_filme
-- ------------------------------------------------------
-- Server version	9.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `film`
--

DROP TABLE IF EXISTS `film`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `film` (
  `id_film` int NOT NULL AUTO_INCREMENT,
  `nume_film` varchar(150) NOT NULL,
  `an_aparitie` int DEFAULT NULL,
  `durata` int DEFAULT NULL,
  `id_regizor` int DEFAULT NULL,
  `descriere` text,
  PRIMARY KEY (`id_film`),
  KEY `id_regizor` (`id_regizor`),
  CONSTRAINT `film_ibfk_1` FOREIGN KEY (`id_regizor`) REFERENCES `regizor` (`id_regizor`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `film`
--

LOCK TABLES `film` WRITE;
/*!40000 ALTER TABLE `film` DISABLE KEYS */;
INSERT INTO `film` VALUES (1,'Prometheus',2012,124,1,NULL),(2,'Arrival',2016,116,2,'O lingvista este recrutata de armata pentru a comunica cu formele de viata extraterestre dupa ce douasprezece nave spatiale misterioase aterizeaza jn jurul lumii.'),(3,'Alien',1979,117,1,'Echipajul unei nave spatiale comerciale intalneste o forma de viata mortală după ce investigheaza o transmisie necunoscuta de pe o luna din apropiere.'),(4,'Call Me by Your Name',2017,132,3,'In Italia anilor 1980, o poveste de dragoste înfloreste intre un student de saptesprezece ani si asistentul de cercetare al tatalui sau.'),(5,'The Silence of the Lambs',1991,118,4,'O tanara cadeta F.B.I. trebuie să ceara ajutorul unui criminal canibal încarcerat si manipulator pentru a prinde un alt criminal în serie. '),(7,'Interstellar',2014,169,7,'DA'),(8,'The Killing of a Sacred Deer',2017,121,8,'A devious teen starts to insinuate himself into the life of a renowned surgeon and his family.'),(9,'Mysterious Skin',2004,105,9,'Misterele tinereții este un film dramatic independent american-olandeză din 2004 adaptat după romanul Mysterious Skin de Scott Heim. Al optulea film regizat de Araki, acesta a avut premiera la Festivalul Internațional de Film de la Veneția în 2004, fiind distribuit publicului în 2005.'),(10,'Mulholland Drive',2001,147,10,'After a car wreck on Mulholland Drive renders a woman amnesiac, she and a Hollywood-hopeful search for clues and answers across Los Angeles.'),(11,'Scream',1996,111,11,'In the small town of Woodsboro, California, a masked killer known as Ghostface begins murdering high school students, and a group of friends must use their knowledge of horror movies to unmask the killer.');
/*!40000 ALTER TABLE `film` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-14  1:46:52
