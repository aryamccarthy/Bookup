-- MySQL dump 10.13  Distrib 5.5.38, for osx10.6 (i386)
--
-- Host: 54.69.55.132    Database: BookUp
-- ------------------------------------------------------
-- Server version	5.5.40-0ubuntu0.14.04.1

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
-- Table structure for table `Account`
--

DROP TABLE IF EXISTS `Account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Account` (
  `email` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  PRIMARY KEY (`email`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Account`
--

LOCK TABLES `Account` WRITE;
/*!40000 ALTER TABLE `Account` DISABLE KEYS */;
INSERT INTO `Account` VALUES ('a@a','asdfasdf'),('a@b.com','wordswords'),('a@c','l21u34c11'),('amccarthy@bookup.com','Candles'),('b@b','asdfasdf'),('BrandonG481@gmail.com','notgivingyoumypwarya1'),('dhwong@smu.edu','f11i23b58'),('drizzuto@bookup.com','Candles'),('ebusbee@bookup.com','Candles'),('hub@lyle.smu.edu','icdattc10'),('hubbard@lyle.smu.edu','lyleschool'),('john@smith.com','password'),('jordan@kayse.com','iamjordan'),('khabeck@bookup.com','Candles'),('khubbard@lyle.smu.edu','Icd@ttc91'),('loglesbee@bookup.com','Candles'),('new@bookup.com','password'),('newuser@newuser.com','password'),('ngatmaitin@bookup.com','Candles'),('other@bookup.com','Forkusmaximus1'),('somebody@somewhere.com','game2013'),('someone@example.com','somethingsomething'),('ummmm@what','asdfasdf'),('user2@bookup.com','password'),('whatisyourproblem@smu.edu','password'),('will@me.com','adsfasdf'),('will@test.com','asdfasdf'),('zfout@bookup.com','Candles');
/*!40000 ALTER TABLE `Account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AccountHash`
--

DROP TABLE IF EXISTS `AccountHash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AccountHash` (
  `email` varchar(30) DEFAULT NULL,
  `hash_val` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`hash_val`),
  KEY `email` (`email`),
  CONSTRAINT `AccountHash_ibfk_1` FOREIGN KEY (`email`) REFERENCES `Account` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AccountHash`
--

LOCK TABLES `AccountHash` WRITE;
/*!40000 ALTER TABLE `AccountHash` DISABLE KEYS */;
/*!40000 ALTER TABLE `AccountHash` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BookHash`
--

DROP TABLE IF EXISTS `BookHash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BookHash` (
  `isbn_num` varchar(15) DEFAULT NULL,
  `hash_val` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`hash_val`),
  KEY `isbn_num` (`isbn_num`),
  CONSTRAINT `BookHash_ibfk_1` FOREIGN KEY (`isbn_num`) REFERENCES `BookList` (`isbn_num`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BookHash`
--

LOCK TABLES `BookHash` WRITE;
/*!40000 ALTER TABLE `BookHash` DISABLE KEYS */;
/*!40000 ALTER TABLE `BookHash` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BookList`
--

DROP TABLE IF EXISTS `BookList`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BookList` (
  `isbn_num` varchar(15) NOT NULL,
  PRIMARY KEY (`isbn_num`),
  UNIQUE KEY `isbn_num` (`isbn_num`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BookList`
--

LOCK TABLES `BookList` WRITE;
/*!40000 ALTER TABLE `BookList` DISABLE KEYS */;
INSERT INTO `BookList` VALUES ('9780007491568'),('9780060256654'),('9780060529963'),('9780060589288'),('9780060759957'),('9780060855925'),('9780060885373'),('9780061120084'),('9780061234002'),('9780061583070'),('9780061726835'),('9780061996160'),('9780062059963'),('9780066238500'),('9780140283303'),('9780141184272'),('9780141441160'),('9780142414934'),('9780142437209'),('9780142437261'),('9780142501559'),('9780143037149'),('9780143038108'),('9780143039563'),('9780156001311'),('9780307265432'),('9780307269751'),('9780307277671'),('9780307279460'),('9780307593313'),('9780312353766'),('9780312532765'),('9780312650247'),('9780312995058'),('9780316125581'),('9780316166683'),('9780316323703'),('9780345391803'),('9780345418920'),('9780345504968'),('9780345538376'),('9780373210183'),('9780375726408'),('9780375806810'),('9780375822742'),('9780375826696'),('9780375831003'),('9780375836671'),('9780385315142'),('9780385333849'),('9780385490818'),('9780385537674'),('9780385720922'),('9780385730587'),('9780385738750'),('9780385739160'),('9780393324815'),('9780393978896'),('9780399254826'),('9780425172902'),('9780439023481'),('9780439358071'),('9780439554930'),('9780439813785'),('9780440222651'),('9780440242949'),('9780440360377'),('9780446605236'),('9780446611084'),('9780446675536'),('9780446696111'),('9780449912553'),('9780450040184'),('9780451207142'),('9780451219367'),('9780451412355'),('9780451525260'),('9780451528827'),('9780452284241'),('9780525422945'),('9780525467564'),('9780525476887'),('9780543898081'),('9780545010221'),('9780553208849'),('9780553213690'),('9780553384284'),('9780553573404'),('9780553588484'),('9780571224388'),('9780605039070'),('9780618346257'),('9780670844876'),('9780671683900'),('9780679720218'),('9780679735908'),('9780679783268'),('9780684830490'),('9780684833392'),('9780691096124'),('9780739326220'),('9780743246989'),('9780743273565'),('9780743466523'),('9780743477574'),('9780743496728'),('9780752865331'),('9780767902892'),('9780786226740'),('9780802130303'),('9780805005011'),('9780811201889'),('9781400064168'),('9781416989417'),('9781420925807'),('9781461091110'),('9781557831576'),('9781580493888'),('9781599900735'),('9781862301382'),('9781903436578'),('9783125785021'),('9788976100146'),('9789703707034');
/*!40000 ALTER TABLE `BookList` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BookSeen`
--

DROP TABLE IF EXISTS `BookSeen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BookSeen` (
  `email` varchar(30) NOT NULL DEFAULT '',
  `rating` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `isbn_num` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`email`,`isbn_num`),
  KEY `isbn_num` (`isbn_num`),
  CONSTRAINT `BookSeen_ibfk_1` FOREIGN KEY (`email`) REFERENCES `Account` (`email`),
  CONSTRAINT `BookSeen_ibfk_2` FOREIGN KEY (`isbn_num`) REFERENCES `BookList` (`isbn_num`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BookSeen`
--

LOCK TABLES `BookSeen` WRITE;
/*!40000 ALTER TABLE `BookSeen` DISABLE KEYS */;
/*!40000 ALTER TABLE `BookSeen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PopularBookList`
--

DROP TABLE IF EXISTS `PopularBookList`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PopularBookList` (
  `isbn_num` varchar(15) NOT NULL,
  PRIMARY KEY (`isbn_num`),
  UNIQUE KEY `isbn_num` (`isbn_num`),
  CONSTRAINT `PopularBookList_ibfk_1` FOREIGN KEY (`isbn_num`) REFERENCES `BookList` (`isbn_num`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PopularBookList`
--

LOCK TABLES `PopularBookList` WRITE;
/*!40000 ALTER TABLE `PopularBookList` DISABLE KEYS */;
INSERT INTO `PopularBookList` VALUES ('9780385537674'),('9780385730587'),('9780393324815'),('9780439023481'),('9780446605236'),('9780553573404'),('9780605039070'),('9783125785021'),('9788976100146'),('9789703707034');
/*!40000 ALTER TABLE `PopularBookList` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Rating`
--

DROP TABLE IF EXISTS `Rating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Rating` (
  `email` varchar(30) NOT NULL DEFAULT '',
  `rating` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `isbn_num` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`email`,`isbn_num`),
  KEY `isbn_num` (`isbn_num`),
  CONSTRAINT `Rating_ibfk_1` FOREIGN KEY (`email`) REFERENCES `Account` (`email`),
  CONSTRAINT `Rating_ibfk_2` FOREIGN KEY (`isbn_num`) REFERENCES `BookList` (`isbn_num`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Rating`
--

LOCK TABLES `Rating` WRITE;
/*!40000 ALTER TABLE `Rating` DISABLE KEYS */;
INSERT INTO `Rating` VALUES ('a@a',1,'2014-11-07 18:04:05','9780605039070'),('amccarthy@bookup.com',-1,'2014-11-03 03:39:09','9780007491568'),('amccarthy@bookup.com',-1,'2014-11-03 03:39:09','9780060256654'),('amccarthy@bookup.com',-1,'2014-11-03 03:39:09','9780060529963'),('BrandonG481@gmail.com',1,'2014-11-05 17:09:50','9780605039070'),('BrandonG481@gmail.com',1,'2014-11-05 17:09:48','9783125785021'),('dhwong@smu.edu',1,'2014-11-07 02:48:39','9780553573404'),('dhwong@smu.edu',1,'2014-11-07 02:48:33','9780605039070'),('dhwong@smu.edu',-1,'2014-11-07 02:48:31','9783125785021'),('drizzuto@bookup.com',-1,'2014-11-03 03:39:09','9781599900735'),('drizzuto@bookup.com',1,'2014-11-03 03:39:09','9781862301382'),('drizzuto@bookup.com',0,'2014-11-03 03:39:09','9781903436578'),('drizzuto@bookup.com',-1,'2014-11-03 03:39:09','9783125785021'),('drizzuto@bookup.com',1,'2014-11-03 03:39:09','9788976100146'),('drizzuto@bookup.com',0,'2014-11-03 03:39:09','9789703707034'),('ebusbee@bookup.com',1,'2014-11-03 06:37:08','9780553573404'),('ebusbee@bookup.com',1,'2014-11-03 06:37:02','9783125785021'),('hubbard@lyle.smu.edu',1,'2014-11-19 18:31:49','9780393324815'),('hubbard@lyle.smu.edu',-1,'2014-11-19 18:32:32','9780605039070'),('hubbard@lyle.smu.edu',1,'2014-11-19 18:32:36','9789703707034'),('john@smith.com',1,'2014-11-07 17:33:04','9780385537674'),('khabeck@bookup.com',1,'2014-11-20 15:37:41','9780060529963'),('khabeck@bookup.com',1,'2014-11-20 15:37:27','9780393324815'),('khabeck@bookup.com',1,'2014-11-03 04:03:09','9780446605236'),('khabeck@bookup.com',1,'2014-11-20 03:35:00','9780451412355'),('khabeck@bookup.com',1,'2014-11-03 04:03:11','9780553573404'),('khabeck@bookup.com',-1,'2014-11-20 04:15:15','9780553588484'),('khabeck@bookup.com',1,'2014-11-03 04:03:12','9780605039070'),('khabeck@bookup.com',-1,'2014-11-20 16:26:34','9780684833392'),('khabeck@bookup.com',-1,'2014-11-20 04:16:36','9780767902892'),('khabeck@bookup.com',1,'2014-11-20 15:37:24','9783125785021'),('khabeck@bookup.com',1,'2014-11-03 04:03:14','9789703707034'),('loglesbee@bookup.com',1,'2014-11-04 03:37:07','9780393324815'),('loglesbee@bookup.com',1,'2014-11-04 03:37:09','9780553573404'),('loglesbee@bookup.com',-1,'2014-11-04 03:37:10','9780605039070'),('loglesbee@bookup.com',-1,'2014-11-04 03:37:12','9783125785021'),('loglesbee@bookup.com',1,'2014-11-04 03:37:14','9789703707034'),('new@bookup.com',1,'2014-11-03 06:06:44','9780553573404'),('new@bookup.com',1,'2014-11-03 06:06:47','9780605039070'),('new@bookup.com',-1,'2014-11-03 06:06:42','9788976100146'),('new@bookup.com',1,'2014-11-03 06:06:41','9789703707034'),('newuser@newuser.com',-1,'2014-11-08 23:07:01','9780385537674'),('newuser@newuser.com',1,'2014-11-08 23:07:02','9780446605236'),('newuser@newuser.com',1,'2014-11-08 23:06:56','9780605039070'),('newuser@newuser.com',1,'2014-11-08 23:07:04','9789703707034'),('ngatmaitin@bookup.com',1,'2014-11-03 05:57:50','9780605039070'),('somebody@somewhere.com',-1,'2014-11-19 08:42:44','9780393324815'),('someone@example.com',1,'2014-11-06 22:36:34','9780553573404'),('someone@example.com',1,'2014-11-06 22:36:32','9780605039070'),('someone@example.com',-1,'2014-11-06 22:36:38','9788976100146'),('ummmm@what',-1,'2014-11-04 16:39:35','9780385537674'),('ummmm@what',-1,'2014-11-04 16:39:37','9780385730587'),('ummmm@what',-1,'2014-11-04 16:39:38','9780393324815'),('ummmm@what',-1,'2014-11-04 16:39:41','9780446605236'),('ummmm@what',-1,'2014-11-04 16:39:42','9780553573404'),('ummmm@what',-1,'2014-11-04 16:39:44','9780605039070'),('ummmm@what',-1,'2014-11-04 16:39:45','9783125785021'),('ummmm@what',-1,'2014-11-04 16:39:43','9788976100146'),('ummmm@what',-1,'2014-11-04 16:39:48','9789703707034'),('user2@bookup.com',-1,'2014-11-03 04:04:16','9780385730587'),('user2@bookup.com',1,'2014-11-03 04:04:17','9780393324815'),('user2@bookup.com',1,'2014-11-03 04:04:19','9780605039070'),('whatisyourproblem@smu.edu',-1,'2014-11-03 22:11:54','9780446605236'),('whatisyourproblem@smu.edu',1,'2014-11-03 22:11:53','9788976100146'),('will@test.com',1,'2014-11-04 16:50:19','9780385730587'),('zfout@bookup.com',1,'2014-11-04 21:40:08','9780393324815'),('zfout@bookup.com',-1,'2014-11-04 21:40:13','9780553573404'),('zfout@bookup.com',1,'2014-11-04 21:40:12','9780605039070'),('zfout@bookup.com',-1,'2014-11-04 21:40:15','9783125785021'),('zfout@bookup.com',1,'2014-11-04 21:40:18','9789703707034');
/*!40000 ALTER TABLE `Rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ReadingList`
--

DROP TABLE IF EXISTS `ReadingList`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ReadingList` (
  `email` varchar(30) NOT NULL DEFAULT '',
  `timestamp` datetime DEFAULT NULL,
  `isbn_num` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`email`,`isbn_num`),
  KEY `isbn_num` (`isbn_num`),
  CONSTRAINT `ReadingList_ibfk_1` FOREIGN KEY (`email`) REFERENCES `Account` (`email`),
  CONSTRAINT `ReadingList_ibfk_2` FOREIGN KEY (`isbn_num`) REFERENCES `BookList` (`isbn_num`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ReadingList`
--

LOCK TABLES `ReadingList` WRITE;
/*!40000 ALTER TABLE `ReadingList` DISABLE KEYS */;
INSERT INTO `ReadingList` VALUES ('amccarthy@bookup.com','2014-11-03 03:39:09','9780553384284'),('amccarthy@bookup.com','2014-11-03 03:39:09','9780553573404'),('amccarthy@bookup.com','2014-11-03 03:39:09','9780553588484'),('amccarthy@bookup.com','2014-11-03 03:39:09','9780571224388'),('amccarthy@bookup.com','2014-11-03 03:39:09','9780605039070'),('amccarthy@bookup.com','2014-11-03 03:39:09','9780618346257'),('amccarthy@bookup.com','2014-11-03 03:39:09','9780670844876'),('amccarthy@bookup.com','2014-11-03 03:39:09','9780671683900'),('amccarthy@bookup.com','2014-11-03 03:39:09','9780679720218'),('amccarthy@bookup.com','2014-11-03 03:39:09','9780679735908'),('BrandonG481@gmail.com','2014-11-05 17:11:04','9780450040184'),('drizzuto@bookup.com','2014-11-03 03:39:09','9780007491568'),('drizzuto@bookup.com','2014-11-03 03:39:09','9780060256654'),('drizzuto@bookup.com','2014-11-03 03:39:09','9780060529963'),('drizzuto@bookup.com','2014-11-03 03:39:09','9780060589288'),('drizzuto@bookup.com','2014-11-03 03:39:09','9780060759957'),('drizzuto@bookup.com','2014-11-03 03:39:09','9780060855925'),('drizzuto@bookup.com','2014-11-03 20:36:57','9780451219367'),('drizzuto@bookup.com','2014-11-03 06:13:17','9780811201889'),('ebusbee@bookup.com','2014-11-03 06:37:56','9780061234002'),('ebusbee@bookup.com','2014-11-03 06:42:24','9780061726835'),('ebusbee@bookup.com','2014-11-03 06:37:44','9780307269751'),('ebusbee@bookup.com','2014-11-20 00:57:08','9780553588484'),('ebusbee@bookup.com','2014-11-20 00:57:30','9780743466523'),('hubbard@lyle.smu.edu','2014-11-19 18:33:53','9780007491568'),('john@smith.com','2014-11-07 17:55:41','9780142437261'),('john@smith.com','2014-11-07 17:48:12','9780679720218'),('john@smith.com','2014-11-07 17:52:29','9780691096124'),('khabeck@bookup.com','2014-11-05 04:18:18','9780060589288'),('khabeck@bookup.com','2014-11-03 16:26:08','9780143037149'),('khabeck@bookup.com','2014-11-03 03:39:09','9780375822742'),('khabeck@bookup.com','2014-11-03 03:39:09','9780385315142'),('khabeck@bookup.com','2014-11-03 04:03:20','9780425172902'),('khabeck@bookup.com','2014-11-03 06:46:14','9780684833392'),('loglesbee@bookup.com','2014-11-04 03:37:25','9780061120084'),('loglesbee@bookup.com','2014-11-04 03:37:48','9780439358071'),('loglesbee@bookup.com','2014-11-04 03:39:18','9789703707034'),('someone@example.com','2014-11-06 22:38:03','9780007491568'),('ummmm@what','2014-11-04 16:40:00','9780373210183'),('ummmm@what','2014-11-04 16:40:46','9781903436578'),('whatisyourproblem@smu.edu','2014-11-03 22:15:40','9780553573404'),('whatisyourproblem@smu.edu','2014-11-03 22:16:09','9780767902892');
/*!40000 ALTER TABLE `ReadingList` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-11-20 10:27:30
