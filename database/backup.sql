-- MySQL dump 10.13  Distrib 8.0.19, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: sso
-- ------------------------------------------------------
-- Server version	8.0.19

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `app`
--

DROP TABLE IF EXISTS `app`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `login_redirect` text COLLATE utf8_unicode_ci,
  `logout_redirect` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app`
--

LOCK TABLES `app` WRITE;
/*!40000 ALTER TABLE `app` DISABLE KEYS */;
INSERT INTO `app` VALUES (1,'SSO Admin local','sso-admin.cttd.tk','http://sso-admin.cttd.tk/backend/dashboard',NULL),(4,'sso-admin.cttd.tk','sso-admin.cttd.tk2','sso-admin.cttd.tk','sso-admin.cttd.tk');
/*!40000 ALTER TABLE `app` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `challenge`
--

DROP TABLE IF EXISTS `challenge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `challenge` (
  `id` int NOT NULL AUTO_INCREMENT,
  `hacker_id` int DEFAULT NULL,
  `difficulty_level` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71056 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `challenge`
--

LOCK TABLES `challenge` WRITE;
/*!40000 ALTER TABLE `challenge` DISABLE KEYS */;
INSERT INTO `challenge` VALUES (4810,77726,4),(21089,27205,1),(36566,5580,7),(66730,52243,6),(71055,52243,2);
/*!40000 ALTER TABLE `challenge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `difficulty`
--

DROP TABLE IF EXISTS `difficulty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `difficulty` (
  `level` int DEFAULT NULL,
  `score` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `difficulty`
--

LOCK TABLES `difficulty` WRITE;
/*!40000 ALTER TABLE `difficulty` DISABLE KEYS */;
INSERT INTO `difficulty` VALUES (1,20),(2,30),(3,40),(4,60),(5,80),(6,100),(7,120);
/*!40000 ALTER TABLE `difficulty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hacker`
--

DROP TABLE IF EXISTS `hacker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hacker` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90412 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hacker`
--

LOCK TABLES `hacker` WRITE;
/*!40000 ALTER TABLE `hacker` DISABLE KEYS */;
INSERT INTO `hacker` VALUES (5580,'Rose'),(8439,'Angela'),(27205,'Frank'),(52243,'Patrick'),(52348,'Lisa'),(57645,'Kimberly'),(77726,'Bonnie'),(83082,'Michael'),(86870,'Todd'),(90411,'Joe');
/*!40000 ALTER TABLE `hacker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_log`
--

DROP TABLE IF EXISTS `login_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_log`
--

LOCK TABLES `login_log` WRITE;
/*!40000 ALTER TABLE `login_log` DISABLE KEYS */;
INSERT INTO `login_log` VALUES (1,'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) SFive/80.0 Chrome/80.0.3987.123 Safari/537.36','127.0.0.1','2020-03-25 03:22:54',1),(2,'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) SFive/80.0 Chrome/80.0.3987.123 Safari/537.36','127.0.0.1','2020-03-25 10:25:48',1),(3,'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) SFive/80.0 Chrome/80.0.3987.123 Safari/537.36','127.0.0.1','2020-03-25 10:27:55',1),(4,'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) SFive/80.0 Chrome/80.0.3987.123 Safari/537.36','127.0.0.1','2020-03-25 10:46:19',1),(5,'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) SFive/80.0 Chrome/80.0.3987.123 Safari/537.36','127.0.0.1','2020-03-25 10:46:39',1),(6,'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) SFive/80.0 Chrome/80.0.3987.123 Safari/537.36','127.0.0.1','2020-03-26 01:54:10',1),(7,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-26 11:37:01',1),(8,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-26 11:37:20',1),(9,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-28 09:30:11',1),(10,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-28 09:36:23',1),(11,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-28 09:36:30',1),(12,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-28 09:36:35',1),(13,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-28 09:36:39',1),(14,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-28 09:42:20',1),(15,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-28 09:43:51',1),(16,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-28 09:43:56',1),(17,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-28 10:22:35',1),(18,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-28 10:22:42',1),(19,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-28 10:22:59',1),(20,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-28 10:23:14',1),(21,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-28 10:45:05',1),(22,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.87 Chrome/80.0.3987.87 Safari/537.36','127.0.0.1','2020-03-28 10:45:10',1);
/*!40000 ALTER TABLE `login_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `message` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `user_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification`
--

LOCK TABLES `notification` WRITE;
/*!40000 ALTER TABLE `notification` DISABLE KEYS */;
INSERT INTO `notification` VALUES (112,'Illum atque quos accusantium sit. Facere voluptatem qui cupiditate ipsa. Vel quasi inventore illum. Modi est repellendus et dolorum.','2020-04-12 13:35:15',NULL,106),(113,'Perferendis dolore officia exercitationem nihil mollitia eos. Iure laudantium enim corrupti. Dolorem et error et odit.','2020-04-12 13:36:19',NULL,106),(114,'Nihil occaecati delectus aut animi natus ipsa vero. Id occaecati neque aliquam odio. Molestias sit incidunt et asperiores est in.','2020-04-12 13:36:24',NULL,106);
/*!40000 ALTER TABLE `notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permission` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission`
--

LOCK TABLES `permission` WRITE;
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
INSERT INTO `permission` VALUES (2,'user.add','Thêm người dùng'),(3,'user.menu','Vào menu người dùng'),(4,'user.delete','Xóa người dùng'),(5,'user.lock','Khóa người dùng'),(6,'role.menu','Vào menu vai trò'),(7,'role.add','Thêm mới vai trò'),(8,'role.delete','Xóa vai trò'),(9,'app.menu','Vào menu ứng dụng'),(10,'app.add','Thêm mới ứng dụng'),(11,'app.delete','Xóa ứng dụng'),(12,'permission.menu','Vào menu quyền'),(13,'permission.add','Thêm mới quyền'),(14,'permission.delete','Xóa quyền'),(15,'permission.delete1','permission.delete');
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (2,'admin','Quản trị'),(3,'user','Người dùng thường'),(4,'admin1','admin');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permission`
--

DROP TABLE IF EXISTS `role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permission` (
  `role_id` int unsigned NOT NULL,
  `permission_id` int unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permission`
--

LOCK TABLES `role_permission` WRITE;
/*!40000 ALTER TABLE `role_permission` DISABLE KEYS */;
INSERT INTO `role_permission` VALUES (2,10),(2,11),(3,9),(3,13);
/*!40000 ALTER TABLE `role_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `submission`
--

DROP TABLE IF EXISTS `submission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `submission` (
  `id` int NOT NULL AUTO_INCREMENT,
  `hacker_id` int DEFAULT NULL,
  `challenge_id` int DEFAULT NULL,
  `score` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `submission`
--

LOCK TABLES `submission` WRITE;
/*!40000 ALTER TABLE `submission` DISABLE KEYS */;
INSERT INTO `submission` VALUES (1,77726,36566,30),(2,77726,21089,10),(3,52243,36566,77),(4,27205,4810,4),(5,77726,66730,30),(6,52243,66730,0),(7,52348,71055,20),(8,27205,71055,23),(9,86870,71055,30),(10,52348,36566,0),(11,86870,36566,30),(12,8439,66730,92),(13,8439,4810,36),(14,5580,71055,4),(15,52348,66730,0),(16,57645,66730,80),(17,27205,66730,35),(18,8439,36566,80),(19,90411,66730,100),(20,83082,4810,40),(21,90411,71055,30);
/*!40000 ALTER TABLE `submission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'lockex1987','lockex1988@gmail.com','$2y$10$p649zdCgdwN8RV.E5OkRn.yB43KzN6IrRGY8ectlHruplGswM6D1q','Nguyễn Văn Huyên 123','1_avatar_1585222688.jpg',NULL),(3,'lavina54','nelson.crooks@example.com','$2y$10$wMuK8Pv.06bSCzSaAO48XuyTmT01CNVu7Iqk6JvcEcqGjRRBFMZ6y','Clinton Kuhlman V',NULL,NULL),(4,'emanuel72','bins.vincenza@example.net','$2y$10$7Nqpp5ULypvaC25/3N/zDuXJOe.swdRSkI9teIhP2myD8enzljBoy','Prof. Gideon McDermott V',NULL,NULL),(5,'darby.torp','chris05@example.com','$2y$10$7xiDSOZMa20mBY13TRbPjuG24eGByprvNX03oHGqBthuBazli.b9O','Marilou Gaylord',NULL,NULL),(6,'arno96','kole.zemlak@example.org','$2y$10$gcnZODDxnHqgtw14mfS5QOF3S817xLPouN1YAdUqky6vj51jkXmaS','Hannah Mayer',NULL,NULL),(7,'darlene.abbott','hagenes.roscoe@example.net','$2y$10$riF2jt1vuQiAR7zi2M21XOgFYXe0ABUp5PomTS/hb5qxxUYfnx1hm','Sincere Thiel',NULL,NULL),(8,'chanel.grady','lonie.runolfsson@example.net','$2y$10$IUOVrp0gqXiaqRY7gBxuYu28jLWk0R0F0LxmtdWM4mJbxeCmTmUQ6','Anita Sauer',NULL,NULL),(9,'cconroy','lacey60@example.net','$2y$10$ObViFw08.GL3EVTykmaDCurUgDVfTF4CJACGf0Mz8HlllSVDaB2qW','Allen Zieme DDS',NULL,NULL),(10,'watsica.burdette','mossie.price@example.net','$2y$10$v.o2jISQ/C2vY6BwfdxrW.O4RIM5LbrNIoQLzniT/N8Fq36W369IK','Else Cummerata',NULL,NULL),(11,'jamie49','haley.anibal@example.net','$2y$10$0PvQCyOHnU7P.gaH4WwG2Okrqigcc0ajXvq8CUM/ha3FnXmwqJz7.','Keshaun Murazik',NULL,NULL),(12,'jacinto.murazik','tcremin@example.net','$2y$10$Gbh7e7LGaTr7nhVG0yCuAuDkBBXbmxsqlPHzfM5wzmDccQiDd4a2y','Rylan Mayer',NULL,NULL),(13,'powlowski.kristoffer','elva64@example.com','$2y$10$kvBs3dXX/PN9gxw8R4NZuuUABT4n2G0p5SgNlXCcsq0c810VvhbOC','Juliana Carroll',NULL,NULL),(14,'carmel23','turcotte.manley@example.com','$2y$10$G8hJJC/TcbSiNM7azKGYSuzQtM0b.L8n/OWD0IxuojBWBvY6rtJLK','Ida Stracke',NULL,NULL),(15,'macie66','goodwin.oswaldo@example.com','$2y$10$bW23k2Gd1Zo.w3AOlcBh3.U3aBrSUw8Rh/Sh2KsfU3DxlYMuD6jI.','Mrs. Juliet Roob DVM',NULL,NULL),(16,'hgerlach','mraz.rhianna@example.org','$2y$10$xq6DWGGsvOgsyos/CMqMG.03N3F6RlmPGWWTFVx35px0bK3nQrdTC','Augusta Will',NULL,NULL),(17,'jkilback','otis26@example.com','$2y$10$AWq/N7PAtcyG2eHu29BKvO7q1aaE2rKU..L9z5vAE50wiFvwLXVyy','Tiara Jones',NULL,NULL),(18,'joseph53','anderson.terry@example.net','$2y$10$DRyayX5dG7pZqBjBHuuPi.uMKKjNweAYNU789npl6CCGEAParF/ZK','Dr. Brett Price',NULL,NULL),(19,'collins.hillary','dturner@example.com','$2y$10$63A7R16e85t81yrXqoljh.MkrS4DhCmBDL5B8mpF0Wm1FL3OE/xjy','Darby Bauch',NULL,NULL),(20,'kunde.adrain','ahermann@example.org','$2y$10$6s02YaODvN0rClyGVRcxE.LPAEJtgdQm2UEnKKeOwM1768NJUHGyu','Katarina Hodkiewicz',NULL,NULL),(21,'nico.hoppe','delta83@example.net','$2y$10$KNXshD/xeXw54FzFTpOM6uspic.8.DK9mHlUftf0mvjkHSu5HT0W6','Dr. Llewellyn Ortiz Jr.',NULL,NULL),(22,'donnie39','ctreutel@example.net','$2y$10$SWBZyfwBZr06p8IV.1ZXeuTH32F1xM6Y6G94qXJd.LGzcXpnX/..O','Ms. Amelia Mraz',NULL,NULL),(23,'casper.mazie','sophie59@example.com','$2y$10$7KYLuFOqPFAMMOZop8.BSOUQMLDSZLzYcDhEDOIsjX8Rstt02ygrG','Erika Gorczany V',NULL,NULL),(24,'gustave.botsford','ystamm@example.net','$2y$10$y8k8mtE4kKYin25lM7czIuLqkYa/d2aiVjLDUFt4SYqBQrUSSqQMW','Orville Eichmann',NULL,NULL),(25,'cydney53','rhett33@example.com','$2y$10$TN.ofwLsbgdtk/XMbxO.OuRA0P202WdATx1Yh3CVuq4F/cdQ8F32i','Savion Bechtelar Sr.',NULL,NULL),(26,'istokes','ycremin@example.org','$2y$10$PXr3O42zSJw5m1lSfgI0EOW5v3PTiwkLohsTE4dnWizIKmeOgr9HG','Milton Crona',NULL,NULL),(27,'lon71','winston50@example.org','$2y$10$4C4Qsr/Pm64V1oHCK.iyEO54jBTWkoyMiEGCvxo8ecQze/YjFNpTe','Gayle Kuvalis II',NULL,NULL),(28,'colby50','elvis.west@example.com','$2y$10$rlNIIWC6UEpbGebOPCFAauIigTfjVkeXT.s5eXsmtSeJrwj12d10u','Mattie Auer',NULL,NULL),(29,'pdonnelly','elliott37@example.com','$2y$10$4N0Dzrulxih2CtzVq89Fmu/T8bnHWMNCiKTGo0iWgE6LBDk4fMet6','Marques Johnson',NULL,NULL),(30,'tracy.moen','jarrett.botsford@example.org','$2y$10$eeV4dOc9zOoeFFJ8C4BPa.EPnp3UI0K7Er70OQXWuURXGW2tWK.Mi','Rebeca Koch',NULL,NULL),(31,'awill','lennie.considine@example.net','$2y$10$RgCUUDREDFVbbfOoZwAS..bhCyM1s2Zwvd/TyjMxnbaMn79s7RAVm','Dr. Beverly Torphy IV',NULL,NULL),(32,'fpurdy','nia24@example.net','$2y$10$Ri1GOOXHYWWnfLYxpU5EBOLHjLSi4s2xlh4mKIJLtAvf6m20u7ySe','Prof. Jamarcus Ward',NULL,NULL),(33,'ymills','dare.bernhard@example.org','$2y$10$cvwZ.5KL9oJrwaguQ.lZ8OXd4IAn/IicJuXeuNLrooTRp5XrkVmG.','Julio Lehner',NULL,NULL),(34,'fdenesik','dibbert.adolph@example.org','$2y$10$8zvPHL1ZW58oCJ92dUB7Xu105fhHghlNGrHNULNmfJNoSmrWHcmUu','Prof. Alek Jaskolski',NULL,NULL),(35,'geo.casper','wdaniel@example.net','$2y$10$t49UBP9XBZo2joyM/gDzde/uDAvhzc2XYVnGGbOL9GiDM5918pgla','Emmanuelle Doyle MD',NULL,NULL),(36,'grady.funk','ortiz.hallie@example.com','$2y$10$T9IkWwJGFoM1UBrEpLDjY..oXB/Cj9XYj5nyuEuhvQivkMBWuil..','Bernhard Rath',NULL,NULL),(37,'krau','taurean80@example.org','$2y$10$dHD.8A0eg5Sm1d.7Si4OHO13OIqqwP7DMdOwKopkjCUE78CJV8u8W','Ernestina Bode',NULL,NULL),(38,'nedra.bernhard','mohammad.strosin@example.com','$2y$10$XGuTJA0gZMW0OA9CYXtvP.zyH8.ig8dO17EK.oGvQuG.MR.spMKzS','Orville Satterfield',NULL,NULL),(39,'chodkiewicz','carolyne02@example.net','$2y$10$Iz9rfJxb2DlWa01./taA2ORt4X22ZyFscKBxpJvbLgAJznOL43/e2','Rodrick Kub IV',NULL,NULL),(40,'alexandrine.connelly','pfeffer.hazle@example.com','$2y$10$bkaRvNrQsCtbKmu63FcmMeZYo7IUKnVxf8B3jpNEEAO0vm6VqP3eu','Dereck Gislason',NULL,NULL),(41,'miller.ahmad','ivy.emard@example.com','$2y$10$3mOxGyMzPIK7TaCK5zq3JuZsjvaNbcONLZPhFk6ooZX21b13.vOLy','Piper Aufderhar',NULL,NULL),(42,'adolfo.kris','lisa62@example.com','$2y$10$u7IaumqBsklMczLTWP1laeBJHpC/8P9NsnAxcRsAnyBbDDOCM5I8i','Mr. Hudson Kuhlman V',NULL,NULL),(43,'koepp.thelma','tweber@example.com','$2y$10$Pn0be4NaYlF4y3tgBvCWUe8KlGHq.XHu9QbessJQrRaQ423qhojcK','Jayda Strosin',NULL,NULL),(44,'ngreen','schmeler.rowena@example.com','$2y$10$sJmHiUGc8/fRYsVQwVLGPOtm8QzcfhslRv5BnArp6fHV13mTXxe1.','Mr. Taylor Ritchie',NULL,NULL),(45,'afranecki','aurelie25@example.net','$2y$10$q1IduHdaU5IhJDWrB8ho7OfgbYcJ.wA2LtUKJlcc1SQIhs2iWCNz.','Ms. Winona Dickinson',NULL,NULL),(46,'adam.muller','gaston.romaguera@example.com','$2y$10$b.S1of4RId.tBQJCltVBJOvoeE5X2duCU7YPnTOMbP2DtDTWQzAiC','Josefa Rippin',NULL,NULL),(47,'asa.erdman','elda78@example.net','$2y$10$xfseClHLHDds5BqB480gReMYaLqtUHgJmMbyMC3QUrzxQoM78oSw.','Manuel Schroeder',NULL,NULL),(48,'osborne45','xbeer@example.com','$2y$10$9Edh1.mmp7755suaO32CcOSTjB9p5F6SQjFhMcLt4qdQmKuqNjkTm','Melvina Wintheiser',NULL,NULL),(49,'jacobi.julianne','marie73@example.org','$2y$10$NzGdP2F94CDMGkY4ffmDT.anX35omzPzlfSo9AtQ4Bu69tg00X2y6','Prof. Aracely Bode',NULL,NULL),(50,'uchamplin','rosanna.cruickshank@example.com','$2y$10$EAXiVK6iyXgk3XZ/YDeetuExcjZsGPnvq2lxYS1FZLpVFwFO1spaO','Sister Upton',NULL,NULL),(51,'rswaniawski','adah69@example.net','$2y$10$T7CFZsVHMgTILKHxgYqdMe/1F/95L0PPZGfXz/LUOEFLL5WJLxTGq','Zelma Abbott',NULL,NULL),(52,'odare','cwalker@example.org','$2y$10$bAAyZBUXxraleHbOydLrYus2vC5MFeDr3dfrO9gzez65XoDYSQNIG','Delmer McDermott',NULL,NULL),(53,'kirlin.blair','kattie83@example.net','$2y$10$9uiy6cQ/7m206BAIG4Dge.X7N3p.Y2QW/3zJ4PXUGtIuOlT1dZmSe','Louisa Mitchell',NULL,NULL),(54,'iwatsica','zackery.blanda@example.org','$2y$10$rU9xrgfMq0Bp7AcKd8DV8OcDkcX9Doqyd1J5mn0ABTeLTtDxfZT0G','Ervin Quigley III',NULL,NULL),(55,'greenholt.cedrick','lela51@example.com','$2y$10$PcX9UeWzXapsplFraAexHOh4MuJWtfaUPAaztCZ5YwmgwOiltcXz6','Prof. Vern Turner IV',NULL,NULL),(56,'savannah10','durgan.jaren@example.com','$2y$10$H7RMeGT.TkUHUHXy7pHPoeydsO/9glm/NEo57Im.5mB0hoyfxrJlu','Ms. Kayli Gaylord III',NULL,NULL),(57,'ardella.kuhic','kcrist@example.com','$2y$10$i/n4ulRVuHaLhoSZ2XLI0eKncnxOkUo0oTOzxblQJlgXtkggPYFj6','Velva Greenfelder',NULL,NULL),(58,'jacobson.keshaun','block.jocelyn@example.com','$2y$10$BBH3Gngt9g.MagvbCtrsr.DwvX6sK2uT.z07c2WW49qJ7kvzXp4UC','Gerard Oberbrunner DDS',NULL,NULL),(59,'cleffler','whitney75@example.org','$2y$10$nhfL7i1Z5x8jKyXN9PgYfu3pYgy2VIcLnq35KPxnHZEE3AyHcZZpe','Janiya Shields',NULL,NULL),(60,'sporer.rebecca','larkin.margie@example.org','$2y$10$Z6pTdOvKOee0UW7m6mI1Xuj/Isrvt2V4Aq3Q51TK0sf3VB8mrhIqO','Libbie Stroman',NULL,NULL),(61,'maia.dickinson','romaguera.tabitha@example.org','$2y$10$JvhM1ZrhCmhAz9FKK4YWTeqNG4WwkHMqZnp3uQK6ljsHqaq4k7zfG','Ms. Destany Hartmann DVM',NULL,NULL),(62,'denesik.tiffany','trantow.tierra@example.net','$2y$10$CuiNXAjMxqZ3C1WYLmd50eddKxfK49UJr0R4Y.1YwzZwfnujQjGp2','Edwin Hagenes',NULL,NULL),(63,'golda.buckridge','halvorson.peggie@example.com','$2y$10$/e5ARkdzwVAKan67kn.KcOi9JIZKODwuRkOyq2XTxSZ8I1Sl7I15q','Dr. Macey Bayer',NULL,NULL),(64,'brody78','greenholt.kendra@example.org','$2y$10$zx2pXUprRGRX7sd./vzGGOPaF8JuUQAhLbNxorGu1C3Fwy6Ievgzi','Mr. Eladio Kshlerin',NULL,NULL),(65,'jacobs.waylon','alba66@example.net','$2y$10$fnGQIurYP/wEboH93NzBnu3Uuqa7R0N/d8hqKHij2T6SKhtr5qrFq','Ashlee Vandervort',NULL,NULL),(66,'paxton.abshire','zflatley@example.org','$2y$10$EdgVXtdAY1gB4hkqnSTEMO.ZNHwEWQh9MOLmMKU/hukHrZNMvaxRi','Rossie Schuppe',NULL,NULL),(67,'ryan.quigley','cummerata.reggie@example.net','$2y$10$j78ISQRj3Sji2bgUJKF7ZevdjQWek.bmRLjB7gN2DdiEwu6Sa.MfO','Madisen Fisher DVM',NULL,NULL),(68,'zackary.rodriguez','oking@example.net','$2y$10$6cVk6oh.h5mmnjFQ7r1kJ.w.asoEJfWatWjelg0tiR6xEnq9PJ20e','Miss Destiney Wolf',NULL,NULL),(69,'zakary.schuster','mary06@example.net','$2y$10$qMXtv7gPkz1dto8XLQCa3OWIFmNpjX8jt0FJa0LFnEbAuOC2ZoJ4a','Flo Mitchell',NULL,NULL),(70,'corbin.schowalter','sbechtelar@example.org','$2y$10$PICtuQPsGrPixsv8FPKMA.Mg4VoAY3Uxf5K7pvl1EAE2w9Kas.USi','Dr. Cordell Roob DDS',NULL,NULL),(71,'darius08','margaret94@example.net','$2y$10$yibTz1IAr/7zwVsXV.L8zuFbyTG844UJZI7VH/ztfsJwNEl7shwY.','Tressie Thiel',NULL,NULL),(72,'taya57','mclaughlin.clara@example.net','$2y$10$aN2nI41D6KKtj125eupxn.gyRneFq1eHhDqOZHXdUHCQSfw1fNVZ2','Abe Durgan',NULL,NULL),(73,'elynch','eino81@example.net','$2y$10$9D5bICyfTg8vmBqB7XObvOe0hPSZv/fh2G7bbIjCdR3aj5qwcg5Mu','Agustina Emmerich',NULL,NULL),(74,'charlie72','mpadberg@example.com','$2y$10$6eZomS8MLgxdzQxjjoD6qOiA5/VLE6WoYDvO.GqlXrIrAQgKi2yDG','Ms. Nicolette Price Sr.',NULL,NULL),(75,'pwilderman','lindgren.ismael@example.com','$2y$10$bN7JQdwUNVN4n6Hg2LUfCe.e9pcgHWrwgxK4uoRL.E9.r26KKjK5S','Micaela Smith',NULL,NULL),(76,'dasia41','roy03@example.org','$2y$10$cFJPkGwxbK8uwuI8SWpaIuxNWdjmI/bnm8kPqAt0Q.6HcCmXU0y0C','Whitney Cronin',NULL,NULL),(77,'travon78','oliver.ondricka@example.net','$2y$10$ZFd2ltxbqSl1uJfl/TmrMOwk8qSr/v.q2TuE1FXCktqe8mWTPmxjy','Camilla Mitchell',NULL,NULL),(78,'valentine.mann','jkris@example.com','$2y$10$NuKiEcJx96SQ9LRibCA2ne5qUuvqgFKUb9IaKNRmwpETnRPgADMnG','Ms. Shannon Purdy PhD',NULL,NULL),(79,'kenneth.fay','fkutch@example.com','$2y$10$LrwzhafWhqJVZGlV5AUoJu.Hfp2W1awqZK6PcYHdagQUq0kRco4Ui','Nikko Sipes',NULL,NULL),(80,'blanda.bryon','vbailey@example.org','$2y$10$u2yct11vpR5NEXrAloldSOdab2iU2JW1LVytz4/SYNCxwVasYrX9W','Ms. Verda Gleason',NULL,NULL),(81,'witting.adela','purdy.cora@example.com','$2y$10$BUvua.lRIyqWfdOwwJQNMOF1niLilRKTO4d2yy7aPmRmoo960PXUa','Christop Dach I',NULL,NULL),(82,'marisa.cronin','teagan90@example.net','$2y$10$1qtSilC3AljKMRGa/k3EjetBfZIbuxJEaHKB.Nl5Zy3uQ337hMJwm','Ward Dicki III',NULL,NULL),(83,'jasper.weber','bernhard.betty@example.org','$2y$10$72Jv5xjM8pWEwh.9RRVuQ.vOFgssJgm6OyOJDvGeYDRZDN3KYV9iC','Angelica Lakin',NULL,NULL),(84,'mnikolaus','hermiston.gracie@example.com','$2y$10$ns8GWVy/9f318SY.ap9bEO/gIB8iSElbZsl2D2vvUASZS3RScgypm','Damaris Hauck',NULL,NULL),(85,'glover.myrtie','kody.sanford@example.net','$2y$10$rLrwW8TkNAY574HMrQasDetIIXfJXxwFGt5HeNJw378r0J4rk3fDi','Aliza Bergstrom',NULL,NULL),(86,'bogisich.judy','kevon06@example.net','$2y$10$02z7BubKJRQjaSnOQFl7TOmNnshaoUVxM0nJ2wORQ/y8z09oMLS5K','Keyshawn Quigley MD',NULL,NULL),(87,'tyrese.runolfsson','zoe.beier@example.com','$2y$10$VUrUl2/JUJEmtXz6JNxUGubRK6MoscX56z4uL9uumtpK.Xax9qz8C','Dr. Irwin Eichmann I',NULL,NULL),(88,'howell.danyka','terence68@example.com','$2y$10$jv12LdGLLi.gPhs5sqphV..vG2MsaxxEfsY9KHNDTLIodCd4hRlG2','Wellington Smitham Jr.',NULL,NULL),(89,'emard.mattie','hillary38@example.com','$2y$10$QpvRx5aT6.2C7jU2pL3Qe.xp2dGWImhEGrhAsKMG836wXgLqZhDt.','Bertram Wintheiser',NULL,NULL),(90,'michelle.kuhlman','jamel32@example.org','$2y$10$ZNVva5CgcqqfpN5uvsBBy.by5d.PXM1ulP00o.wlBdteR2.b9JMXu','Janie McLaughlin',NULL,NULL),(91,'rosalyn.hermiston','jking@example.org','$2y$10$yvaHcRoSqECG7D86zb69Puqd7WAY7ALArCQEWxLjWnbEt9fOXm/3O','Miss Kaylah Jenkins',NULL,NULL),(92,'pfeffer.ebony','favian.effertz@example.com','$2y$10$vh80UHtdi8v2jyNzH1mK0uEpgaBGdMmx4Uw8FOnau8r9jV6ZpYe8S','Mr. Ransom Lynch Jr.',NULL,NULL),(93,'verlie.schamberger','vance.breitenberg@example.com','$2y$10$bxpzsqWXFQdt8DmEegNyX.fAZWmx0cl8vGXzIhIDX6iQw9N6AmYbO','Ms. Belle Senger DDS',NULL,NULL),(94,'nichole.harber','howe.lance@example.com','$2y$10$tmZyHe90OxCUO8H4sVtVcu17yEBjTtl3R87SEpDGf0qGe0QaTHmp2','Merle DuBuque',NULL,NULL),(95,'danika.frami','witting.karlie@example.org','$2y$10$8NhvoJySZ0kuUyySu4mz9e8D2Dvt5hhWXLTZgooby3aUCCXR8iWRO','Elmira Brekke',NULL,NULL),(96,'toney.hessel','susana66@example.org','$2y$10$KRxHa7bD12CYgEGxml2Zx.nuiBFOlY3NtE8uhL7KBZYVUkfnWuQxK','Justen Wunsch',NULL,NULL),(97,'natalie06','ndooley@example.net','$2y$10$aM95NgmxzaIE7/hM2tRsouRl7klq901BykfQVMbU7ZKZlv3nfrRsG','Dr. Madge Zemlak MD',NULL,NULL),(98,'darrin50','windler.erika@example.org','$2y$10$p93YfTMN0tYmcZLkagQQ0uNrU0IOzeriK6DTgchHMe/CO7Hzuezna','Heaven Kilback',NULL,NULL),(99,'ignacio67','waters.rosamond@example.com','$2y$10$afan1ykYNE6a0YIBEpo2R.jGWGGjI/zhyv2FdAozCQSdyThMvlYJ6','Dr. Torey Grimes MD',NULL,NULL),(100,'qkreiger','dlind@example.net','$2y$10$nuL6Psw9Jw1DB3JqxKC12Ov8YQ8ZrYVXVRpUowJS5e7FVPNuh91h2','Amari Simonis',NULL,NULL),(101,'margarete75','wyman.irving@example.net','$2y$10$2SxVwYJGIZf.wvUqTqdRcOrzCBVb6fTfyx6id7MXE4/hc8ua688qG','Christiana O\'Conner',NULL,NULL),(102,'benny.conn','bmorar@example.org','$2y$10$Gs1tzdIwfIJx0Xbjq9g6beVlpVFfEVzXeAIYKzyFfk8IjzcyYZpXG','Geovanny Terry',NULL,NULL),(103,'gjast','maximilian.lubowitz@example.com','$2y$10$Wny8ZfnDlGExDAUCa/ALsuASnZ44oJhv2y1ZDvwF0J0ksZJreR/Au','Wiley McGlynn',NULL,NULL),(105,'margarete751','margarete75@gmail.com','$2y$10$RQS92rrCdwfopdLqh/IlXuHMqFm6Mg8bRKGSKcX905SscK3HC6xcm','margarete75',NULL,1),(106,'nat19','nat19@gmail.com','$2y$10$tQqTAMfz6Nfl/.9BVGGh..JkuOFjPTSqyVE5XPbLGiQOn8hR6IeDO','Nguyễn Anh Tuấn',NULL,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_app`
--

DROP TABLE IF EXISTS `user_app`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_app` (
  `app_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  PRIMARY KEY (`app_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_app`
--

LOCK TABLES `user_app` WRITE;
/*!40000 ALTER TABLE `user_app` DISABLE KEYS */;
INSERT INTO `user_app` VALUES (1,1),(1,103),(1,106);
/*!40000 ALTER TABLE `user_app` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_role` (
  `role_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (2,101),(2,103),(3,102),(3,103);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-12 20:55:22
