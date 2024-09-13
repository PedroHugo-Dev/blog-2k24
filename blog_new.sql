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
  `numero_deslikes` varchar(10) NOT NULL,
  `numero_likes` varchar(10) NOT NULL,
  `data_criacao` date NOT NULL,
  `data_modificacao` date NOT NULL,
  PRIMARY KEY (`id_comentario`,`id_post`,`id_topico`,`id_user`),
  UNIQUE KEY `id_comentario_UNIQUE` (`id_comentario`),
  KEY `fk_comentario_post1_idx` (`id_post`,`id_topico`),
  KEY `fk_comentario_tb_user1_idx` (`id_user`),
  CONSTRAINT `fk_comentario_post1` FOREIGN KEY (`id_post`, `id_topico`) REFERENCES `post` (`id_post`, `id_topico`) ON DELETE CASCADE,
  CONSTRAINT `fk_comentario_tb_user1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentario`
--

LOCK TABLES `comentario` WRITE;
/*!40000 ALTER TABLE `comentario` DISABLE KEYS */;
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
  `numero_likes` varchar(10) NOT NULL,
  `numero_deslikes` varchar(10) NOT NULL,
  `numero_comentarios` varchar(10) NOT NULL,
  PRIMARY KEY (`id_post`,`id_topico`,`id_user`),
  UNIQUE KEY `id_post_UNIQUE` (`id_post`),
  KEY `fk_post_topico_idx` (`id_topico`),
  KEY `fk_post_tb_user1_idx` (`id_user`),
  CONSTRAINT `fk_post_tb_user1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `fk_post_topico` FOREIGN KEY (`id_topico`) REFERENCES `topico` (`id_topico`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` VALUES (1,2,22,'teste','aasdfasdf','2024-09-03 07:41:47',NULL,'3','4','2'),(2,2,22,'aasd','asda','2024-09-13 08:25:33',NULL,'0','0','0'),(3,2,22,'ajdfj','asd','2024-09-13 08:25:49',NULL,'0','0','0'),(4,2,22,'asdf','adsf','2024-09-13 08:26:20',NULL,'0','0','0'),(8,2,22,'tes','asd','2024-09-13 09:56:58',NULL,'0','0','0'),(9,2,22,'teste2','asd','2024-09-13 10:18:35',NULL,'0','0','0'),(11,2,22,'asdas','adsd','2024-09-13 10:50:36',NULL,'0','0','0'),(12,4,22,'teste222','2','2024-09-13 10:51:25',NULL,'0','0','0'),(13,4,22,'asda','2asd','2024-09-13 10:53:45',NULL,'0','0','0'),(14,5,22,'hello!!!','this will be lorem ipsum','2024-09-13 10:58:33',NULL,'0','0','0'),(15,5,22,'asda','asdas','2024-09-13 11:22:31',NULL,'0','0','0'),(16,4,22,'heh','heh','2024-09-13 11:23:40',NULL,'0','0','0'),(17,5,22,'heh heh  Postado em: 2024-09-13 11:23:40','heh\r\nheh\r\n\r\nPostado em: 2024-09-13 11:23:40','2024-09-13 11:24:01',NULL,'0','0','0'),(18,5,22,'asd','asd','2024-09-13 11:28:19',NULL,'0','0','0'),(19,5,22,'asd','asd','2024-09-13 11:30:13',NULL,'0','0','0'),(20,5,22,'asd','gasf','2024-09-13 11:30:38',NULL,'0','0','0'),(21,5,22,'fasd','fdas','2024-09-13 11:30:47',NULL,'0','0','0');
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_user`
--

LOCK TABLES `tb_user` WRITE;
/*!40000 ALTER TABLE `tb_user` DISABLE KEYS */;
INSERT INTO `tb_user` VALUES (21,'adasd','asdas','asdasd','4gsdf','2024-08-29',0),(22,'avatar-padrao.png','a','a@a','$2y$10$Lf6yDCVAP2ToHSAWnoJHO.B/SIQmcqY5zcqHET.ZseQYxls.JDXjC','2024-08-30',0),(23,'avatar-padrao.png','b','b@b','$2y$10$pihqNg0p/gXWHOEawk51he.NmBBvIdcre9DcysBW83KGgX/2z6fOG','2024-09-13',0),(24,'avatar-padrao.png','c','c@c','$2y$10$6wV04Swb/m7LxtzuGPW22.fMLEWzhCpIwvhE5SC9W.Lxhhrb1hx56','2024-09-13',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topico`
--

LOCK TABLES `topico` WRITE;
/*!40000 ALTER TABLE `topico` DISABLE KEYS */;
INSERT INTO `topico` VALUES (2,'Teste','abcasdfasd','2024-09-03 07:37:03','1',22,''),(4,'teste2','tw','2024-09-13 10:33:33','1',22,'sa'),(5,'teste3','lorem ipsum dolores','2024-09-13 10:58:15','1',22,'as');
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

-- Dump completed on 2024-09-13 11:38:23
