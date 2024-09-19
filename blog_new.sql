-- MySQL dump 10.13  Distrib 8.0.39, for Linux (x86_64)
--
-- Host: localhost    Database: blog_new
-- ------------------------------------------------------
-- Server version	8.0.39-0ubuntu0.22.04.1

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
-- Table structure for table `comentario`
--

DROP TABLE IF EXISTS `comentario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comentario` (
  `id_comentario` int NOT NULL AUTO_INCREMENT,
  `corpo` text NOT NULL,
  `id_post` int NOT NULL,
  `id_topico` int NOT NULL,
  `id_user` int NOT NULL,
  `data_criacao` date NOT NULL,
  `data_modificacao` date DEFAULT NULL,
  PRIMARY KEY (`id_comentario`,`id_post`,`id_topico`,`id_user`),
  UNIQUE KEY `id_comentario_UNIQUE` (`id_comentario`),
  KEY `fk_comentario_post1_idx` (`id_post`,`id_topico`),
  KEY `fk_comentario_tb_user1_idx` (`id_user`),
  CONSTRAINT `fk_comentario_post1` FOREIGN KEY (`id_post`, `id_topico`) REFERENCES `post` (`id_post`, `id_topico`) ON DELETE CASCADE,
  CONSTRAINT `fk_comentario_tb_user1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentario`
--

LOCK TABLES `comentario` WRITE;
/*!40000 ALTER TABLE `comentario` DISABLE KEYS */;
INSERT INTO `comentario` VALUES (16,'oi',65,4,22,'2024-09-19',NULL),(17,'oi',65,4,22,'2024-09-19',NULL),(18,'ol',65,4,22,'2024-09-19',NULL),(19,'oioi',66,2,22,'2024-09-19',NULL);
/*!40000 ALTER TABLE `comentario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participa`
--

DROP TABLE IF EXISTS `participa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participa` (
  `id_topico` int NOT NULL,
  `id_user` int NOT NULL,
  `data_entrada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `administrador` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_topico`,`id_user`),
  KEY `fk_topico_has_tb_user_tb_user1_idx` (`id_user`),
  KEY `fk_topico_has_tb_user_topico1_idx` (`id_topico`),
  CONSTRAINT `fk_topico_has_tb_user_tb_user1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `fk_topico_has_tb_user_topico1` FOREIGN KEY (`id_topico`) REFERENCES `topico` (`id_topico`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participa`
--

LOCK TABLES `participa` WRITE;
/*!40000 ALTER TABLE `participa` DISABLE KEYS */;
/*!40000 ALTER TABLE `participa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post` (
  `id_post` int NOT NULL AUTO_INCREMENT,
  `id_topico` int NOT NULL,
  `id_user` int NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `corpo` text NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `numero_comentarios` varchar(10) NOT NULL,
  PRIMARY KEY (`id_post`,`id_topico`,`id_user`),
  UNIQUE KEY `id_post_UNIQUE` (`id_post`),
  KEY `fk_post_topico_idx` (`id_topico`),
  KEY `fk_post_tb_user1_idx` (`id_user`),
  CONSTRAINT `fk_post_tb_user1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `fk_post_topico` FOREIGN KEY (`id_topico`) REFERENCES `topico` (`id_topico`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` VALUES (57,5,22,'a','a','2024-09-19 10:13:37',NULL,'0'),(58,5,22,'a','a','2024-09-19 10:13:41',NULL,'0'),(59,5,22,'a','a','2024-09-19 10:13:48',NULL,'0'),(60,5,22,'a','a','2024-09-19 10:13:51',NULL,'0'),(61,5,22,'b','b','2024-09-19 10:13:54',NULL,'0'),(63,5,22,'d','d','2024-09-19 10:14:16',NULL,'0'),(65,4,22,'OLA MUNDO','OLA','2024-09-19 10:43:42',NULL,'0'),(66,2,22,'fasdsa','asd','2024-09-19 10:49:54',NULL,'0'),(67,2,22,'fafds','gsafdg','2024-09-19 11:06:12',NULL,'0');
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_user`
--

DROP TABLE IF EXISTS `tb_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `foto_user` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nome_user` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email_user` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `senha_user` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `data_cadastro` date DEFAULT NULL,
  `administrador` tinyint NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `id_user_UNIQUE` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_user`
--

LOCK TABLES `tb_user` WRITE;
/*!40000 ALTER TABLE `tb_user` DISABLE KEYS */;
INSERT INTO `tb_user` VALUES (21,'adasd','asdas','asdasd','4gsdf','2024-08-29',0),(22,'avatar-padrao.png','a','a@a','$2y$10$Lf6yDCVAP2ToHSAWnoJHO.B/SIQmcqY5zcqHET.ZseQYxls.JDXjC','2024-08-30',0),(23,'avatar-padrao.png','b','b@b','$2y$10$pihqNg0p/gXWHOEawk51he.NmBBvIdcre9DcysBW83KGgX/2z6fOG','2024-09-13',0),(24,'avatar-padrao.png','c','c@c','$2y$10$6wV04Swb/m7LxtzuGPW22.fMLEWzhCpIwvhE5SC9W.Lxhhrb1hx56','2024-09-13',0),(25,'avatar-padrao.png','b','b@b','$2y$10$t3RR8gjxqzNXYt.ikAtDYekhwPo4ZSGxSCnAfrrnz0.CxZKe3Q9.i','2024-09-19',0);
/*!40000 ALTER TABLE `tb_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topico`
--

DROP TABLE IF EXISTS `topico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `topico` (
  `id_topico` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `descricao` text NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `numero_users` varchar(10) NOT NULL,
  `id_criador` int NOT NULL,
  `foto_topico` varchar(45) NOT NULL,
  PRIMARY KEY (`id_topico`),
  UNIQUE KEY `id_topico_UNIQUE` (`id_topico`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topico`
--

LOCK TABLES `topico` WRITE;
/*!40000 ALTER TABLE `topico` DISABLE KEYS */;
INSERT INTO `topico` VALUES (2,'Teste','abcasdfasd','2024-09-03 07:37:03','1',22,''),(4,'teste2','tw','2024-09-13 10:33:33','1',22,'sa'),(5,'teste3','lorem ipsum dolores','2024-09-13 10:58:15','1',22,'as'),(6,'Gabriel','hello world','2024-09-19 11:25:00','1',22,'oi');
/*!40000 ALTER TABLE `topico` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-19 11:35:58
