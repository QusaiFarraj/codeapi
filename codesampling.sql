-- MySQL dump 10.13  Distrib 5.7.21, for Linux (x86_64)
--
-- Host: localhost    Database: codesampling
-- ------------------------------------------------------
-- Server version	5.7.21-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `user_permissions`
--

DROP TABLE IF EXISTS `user_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_permissions`
--

LOCK TABLES `user_permissions` WRITE;
/*!40000 ALTER TABLE `user_permissions` DISABLE KEYS */;
INSERT INTO `user_permissions` VALUES (2,71,0,'2018-03-31 05:56:47','2018-03-31 05:56:47'),(3,72,1,'2018-03-31 05:58:33','2018-03-31 05:58:33'),(4,73,0,'2018-03-31 05:59:48','2018-03-31 05:59:48'),(5,74,0,'2018-03-31 06:02:12','2018-03-31 06:02:12'),(6,75,0,'2018-04-01 02:02:52','2018-04-01 02:02:52'),(7,76,0,'2018-04-01 02:19:44','2018-04-01 02:19:44'),(8,77,0,'2018-04-01 02:21:18','2018-04-01 02:21:18'),(9,78,0,'2018-04-01 02:24:56','2018-04-01 02:24:56'),(10,79,0,'2018-04-01 02:35:05','2018-04-01 02:35:05'),(11,80,0,'2018-04-01 02:37:35','2018-04-01 02:37:35'),(12,81,0,'2018-04-01 02:38:19','2018-04-01 02:38:19'),(13,82,0,'2018-04-01 02:42:41','2018-04-01 02:42:41');
/*!40000 ALTER TABLE `user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `first_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `active_hash` varchar(255) DEFAULT NULL,
  `recover_hash` varchar(255) DEFAULT NULL,
  `remember_identifier` varchar(255) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (72,'qusai','qusai919@gmail.com',NULL,NULL,'$2y$10$eLGdo4QQF2Uij.6NCMx.N.G/W8VQ20LWbej81TZidtwrS7ovihBEq',1,NULL,NULL,'gYGi1HaSnhJ7DmNO1jUeT7wyFuuAqm7L/kRpYSax0Ba9Ks6QxUgq8PVZaJfsGCUyVLPaoAJnWO0bDYiQi6cFyrFL6digDlZxYGoN9uc+GZik/bzGpCEvwo8qmm/To4B7','c4a34915845169feb50a6a1a05a8a92a22a1074c8fc9eb0abdd66a01eac6d952','2018-03-31 06:13:49','2018-03-31 06:13:49'),(73,'qusai44','qusai919@gogog.com',NULL,NULL,'$2y$10$rpeMK0OBDQKxJqXGL2J5uun3B40drMZ4.6i5yMvOC3n.ynSC0SJ.O',0,'f1a1ef7170b80d98aaba76512334e8d3acfcde44934d70c38a95126e2cbd2908',NULL,NULL,NULL,'2018-03-31 05:59:48','2018-03-31 05:59:48'),(74,'qusai44ee','qusai919@rer.com',NULL,NULL,'$2y$10$tLq/Y0gzvKWTdED9Q3UCRuSbgyS5V8a6ZJhEIS.MidxgkOJkUIWpW',0,'f1f3733e34603c9cfd435d8e32803c0926dcb501847ac694c914d5c26051a640',NULL,NULL,NULL,'2018-03-31 06:02:12','2018-03-31 06:02:12'),(82,'qusai1','qusai919@icloud.com',NULL,NULL,'$2y$10$fN992ufZYjfR6uDTSLPZtutbTvbm1LakBHrowLyKSGSQ.WR8wqFTK',1,NULL,NULL,NULL,NULL,'2018-04-01 03:11:38','2018-04-01 03:11:38');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-02 16:05:27
