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
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feedback` (
  `id_feedback` int NOT NULL AUTO_INCREMENT,
  `id_film` int NOT NULL,
  `continut` text,
  `nr_stele` int DEFAULT NULL,
  `nume_autor` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_feedback`),
  KEY `id_film` (`id_film`),
  CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`) ON DELETE CASCADE,
  CONSTRAINT `feedback_chk_1` CHECK (((`nr_stele` >= 1) and (`nr_stele` <= 10)))
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
INSERT INTO `feedback` VALUES (1,1,'Vizual impresionant, dar povestea e complicata.',7,'Ion Popescu'),(2,3,'Cel mai bun film horror din spatiu!',10,'AlienFan99'),(3,4,'O poveste emotionanta si trista.',9,'Maria D.'),(4,5,'Anthony Hopkins joaca genial.',10,'Cinefilul'),(7,4,'Filmul meu preferat frate...',10,'VINTREX'),(8,5,'mi a placut mult :)',8,'IOANA'),(9,4,'Prea misto frate, e bombita',9,'YULULU'),(10,7,'a little more horror and a little less love please',6,'hr_giger'),(11,10,'The late great David Lynch\'s Mulholland Drive starts with a car crash on- wait for it- Mulholland Drive. With picturesque views of LA at night, the sole survivor Rita (Laura Harring) trundles down the valley into the city. Police soon learn that a witness might have fled from the crash and a search begins. It\'s not the search that\'s important, per se, but more the fact that it\'s happening at all. It\'s a plot device to set mood; the mood itself is the goal.\r\n\r\nNext, Naomi Watts\' character Betty arrives at LA International Airport on the arm of an older couple whom she\'d met on the flight. She mutters no words beyond, \"Oh, I can\'t believe it\" as she\'s welcomed by the \"Welcome to Los Angeles!\" banner at the foot of the escalator. We don\'t know much about where she\'s headed or why- but we totally do: starlet lands in Hollywood in search of fame and fortune. Lynch appreciates how there\'s scarce need for dialogue. This story\'s been told enough times that we can fill in the holes ourselves. He lets the movie breathe.\r\n\r\nRita and Betty eventually cross paths and the narrative takes shape from there, alongside a passel of other characters and storylines. Everyone\'s either being chased/watched, feels like they are, or is just generally discomfited by their predicament. There\'s an active force in the background that we can\'t see despite our eyes being glued to the screen. It takes some time before we make sense of the many abstractions. True to form, Lynch moves artfully between what\'s real, what\'s vivid dream, and what\'s pure fantasy- it\'s Hollywood, remember- but we remain confident in the story based on clues provided by a director who\'s long earned our trust as moviegoers.\r\n\r\nOpulent orchestral music (City of Prague Philharmonic) animates the monster of the city. The gently pulsating score gives texture to the mood, depth to the drama, and ultimately heart to the film. Periodic shots of the Hollywood sign serve as a visual reminder of where we are. Bird\'s eye views of the heliports downtown reinforce the same. Only later do we realize the story has not much to do with LA- yet it does. It\'s a movie about making movies, after all. If Quentin Tarantino\'s Once Upon a Time... in Hollywood was a love letter to Los Angeles in general, Lynch\'s Mulholland Drive is a hostile rebuke of Hollywood in particular (which might be a good way to approach this ambitious film if you\'re seeing it for the first time).\r\n\r\nOn balance, Mulholland Drive means different things to different people- maybe even different things to the same people! We come to Hollywood to realize our dreams, and its winding road leads some to success and others over the edge. It may lead to a crash from which we can escape- literally or figuratively- but our ultimate fate is decided by strangers, some of whom lie in our own heads. We may start by looking outwardly for answers but by the end we\'re transfixed on what\'s happening within. So while Mulholland Drive does exist on a map, it\'s the Mulholland Drive in our minds that may dictate actual outcomes. As one character declares to another halfway through the film, \"Man\'s attitude will determine to a large extent how his life will be.\"\r\n\r\nWell if that\'s true, Lynch must have had a wonderful attitude because he led an exemplary life, and this film was surely among the peaks of his career. Essential viewing.\r\n\r\n---\r\n\r\n\"In work and in life, we\'re all supposed to get along. We\'re supposed to have fun, like puppy dogs with our tails wagging. It\'s supposed to be great living; it\'s supposed to be fantastic.\" - David Lynch.',9,'greatandimproving'),(12,10,'I feel it\'s hypocritical to call a movie a \"masterpiece\" (which this is), while at the same time slapping it a bit for the very essence of what it is and tries to achieve... but that\'s what keeps this from being a \"10\" for me. Because, after watching and then exhaustingly reading about the film (and understanding more about it that way), it\'s pretty obvious that many/most people won\'t \"get it\" fully the first time... and that detracts a little... even though the complexities are what ultimately makes it great. Does that make sense?\r\n\r\nIt\'s a Catch-22. You can\'t KNOW about it before you start... that would ruin the presentation... and yet there\'s a very legit chance you won\'t fully understand it either if you go in cold. It needs either a 2nd viewing, or the post-movie research to understand (if you\'re willing to do that, and even if you DO, you\'re going to want to watch it again anyway). All the clues are there and it all makes sense, once you know. But it is so intentionally deceptive, it\'s hard to know what you don\'t know.\r\n\r\nBut it\'s brilliant, artistic, evocative, and clever. It slaps hard at Hollywood and the dream machine, and the disillusionment of aspiration. There is quite simply nothing like it. It has been called \"the best film of the 21st Century,\" and I won\'t dispute that. But it IS hard to follow and understand and demands more of the viewer than almost any film I\'ve ever seen. So I\'d say watch it, draw your own conclusions, read about it, hear what others think and believe... and then watch it again. You will be rewarded\r\n\r\nBut there is no denying that it is absolute brilliance, and David Lynch\'s crowning achievement.',9,'bk753');
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-14  1:46:54
