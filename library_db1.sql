-- MySQL dump 10.13  Distrib 5.7.12, for Win32 (AMD64)
--
-- Host: localhost    Database: library_db1
-- ------------------------------------------------------
-- Server version	5.7.32-log

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
-- Table structure for table `author`
--

DROP TABLE IF EXISTS `author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `author` (
  `author_id` int(11) NOT NULL AUTO_INCREMENT,
  `author_name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `author`
--

LOCK TABLES `author` WRITE;
/*!40000 ALTER TABLE `author` DISABLE KEYS */;
INSERT INTO `author` VALUES (1,'Остин Джейн'),(2,'Толстой Лев Николаевич'),(3,'Кэрролл Льюис'),(4,'Стивенсон Роберт Льюис'),(5,'Достоевский Федор Михайлович'),(6,'Гюго Виктор'),(7,'Булгаков Михаил Афанасьевич'),(8,'Ильф Илья'),(9,'Петров Евгений'),(10,'Верн Жюль'),(11,'Пушкин Александр Сергеевич'),(12,'Островский Александр Николаевич'),(13,'Ремарк Эрих Мария');
/*!40000 ALTER TABLE `author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `book_year` year(4) NOT NULL,
  `book_pages` int(10) unsigned NOT NULL,
  `book_copies` int(10) unsigned NOT NULL,
  `publisher_publisher_id` int(11) NOT NULL,
  PRIMARY KEY (`book_id`),
  KEY `fk_book_publisher_idx` (`publisher_publisher_id`),
  CONSTRAINT `fk_book_publisher` FOREIGN KEY (`publisher_publisher_id`) REFERENCES `publisher` (`publisher_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book`
--

LOCK TABLES `book` WRITE;
/*!40000 ALTER TABLE `book` DISABLE KEYS */;
INSERT INTO `book` VALUES (1,'Гордость и предубеждение',2020,416,2,1),(2,'Война и мир',2019,1360,2,2),(3,'Алиса в Стране чудес',2015,144,4,3),(4,'Остров сокровищ',2014,192,3,4),(5,'Анна Каренина',2020,800,2,2),(6,'Преступление и наказание',2020,540,3,2),(7,'Отверженные',2021,768,1,1),(8,'Мастер и Маргарита',2020,416,2,1),(9,'Золотой теленок',2021,394,3,5),(10,'Пятнадцатилетний капитан',2018,351,5,6),(11,'Братья Карамазовы',2015,784,2,7),(12,'Евгений Онегин',2021,274,2,8),(13,'Гроза',2020,256,2,1),(14,'Станция на горизонте',2021,320,2,2),(15,'Двенадцать стульев',2016,320,4,7);
/*!40000 ALTER TABLE `book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `book_author`
--

DROP TABLE IF EXISTS `book_author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book_author` (
  `book_book_id` int(11) NOT NULL,
  `author_author_id` int(11) NOT NULL,
  PRIMARY KEY (`book_book_id`,`author_author_id`),
  KEY `fk_book_has_author_author1_idx` (`author_author_id`),
  KEY `fk_book_has_author_book1_idx` (`book_book_id`),
  CONSTRAINT `fk_book_has_author_author1` FOREIGN KEY (`author_author_id`) REFERENCES `author` (`author_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_book_has_author_book1` FOREIGN KEY (`book_book_id`) REFERENCES `book` (`book_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book_author`
--

LOCK TABLES `book_author` WRITE;
/*!40000 ALTER TABLE `book_author` DISABLE KEYS */;
INSERT INTO `book_author` VALUES (1,1),(2,2),(5,2),(3,3),(4,4),(6,5),(11,5),(7,6),(8,7),(9,8),(15,8),(9,9),(15,9),(10,10),(12,11),(13,12),(14,13);
/*!40000 ALTER TABLE `book_author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `book_genre`
--

DROP TABLE IF EXISTS `book_genre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book_genre` (
  `book_book_id` int(11) NOT NULL,
  `genre_genre_id` int(11) NOT NULL,
  PRIMARY KEY (`book_book_id`,`genre_genre_id`),
  KEY `fk_book_has_genre_genre1_idx` (`genre_genre_id`),
  KEY `fk_book_has_genre_book1_idx` (`book_book_id`),
  CONSTRAINT `fk_book_has_genre_book1` FOREIGN KEY (`book_book_id`) REFERENCES `book` (`book_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_book_has_genre_genre1` FOREIGN KEY (`genre_genre_id`) REFERENCES `genre` (`genre_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book_genre`
--

LOCK TABLES `book_genre` WRITE;
/*!40000 ALTER TABLE `book_genre` DISABLE KEYS */;
INSERT INTO `book_genre` VALUES (1,1),(4,1),(5,1),(6,1),(8,1),(9,1),(11,1),(15,1),(2,2),(7,2),(3,3),(4,5),(10,5),(14,5),(10,6),(12,7),(13,8),(15,9);
/*!40000 ALTER TABLE `book_genre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genre`
--

DROP TABLE IF EXISTS `genre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `genre` (
  `genre_id` int(11) NOT NULL AUTO_INCREMENT,
  `genre_name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`genre_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genre`
--

LOCK TABLES `genre` WRITE;
/*!40000 ALTER TABLE `genre` DISABLE KEYS */;
INSERT INTO `genre` VALUES (1,'Роман'),(2,'Роман-эпопея'),(3,'Сказка'),(5,'Приключение'),(6,'Фантастика'),(7,'Роман в стихах'),(8,'Драма'),(9,'Сатира');
/*!40000 ALTER TABLE `genre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `give_out`
--

DROP TABLE IF EXISTS `give_out`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `give_out` (
  `give_out_id` int(11) NOT NULL AUTO_INCREMENT,
  `give_date` date NOT NULL,
  `take_date` date NOT NULL,
  `book_book_id` int(11) NOT NULL,
  `reader_reader_id` int(11) NOT NULL,
  `stat` enum('Не сдана','Сдана') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Не сдана',
  `return_date` date DEFAULT NULL,
  PRIMARY KEY (`give_out_id`,`book_book_id`,`reader_reader_id`),
  KEY `fk_give_out_book1_idx` (`book_book_id`),
  KEY `fk_give_out_reader1_idx` (`reader_reader_id`),
  CONSTRAINT `fk_give_out_book1` FOREIGN KEY (`book_book_id`) REFERENCES `book` (`book_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_give_out_reader1` FOREIGN KEY (`reader_reader_id`) REFERENCES `reader` (`reader_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `give_out`
--

LOCK TABLES `give_out` WRITE;
/*!40000 ALTER TABLE `give_out` DISABLE KEYS */;
INSERT INTO `give_out` VALUES (1,'2021-06-03','2021-06-17',7,3,'Сдана','2021-06-03'),(2,'2021-06-03','2021-06-17',3,3,'Не сдана',NULL);
/*!40000 ALTER TABLE `give_out` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publisher`
--

DROP TABLE IF EXISTS `publisher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publisher` (
  `publisher_id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `publisher_city` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`publisher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publisher`
--

LOCK TABLES `publisher` WRITE;
/*!40000 ALTER TABLE `publisher` DISABLE KEYS */;
INSERT INTO `publisher` VALUES (1,' Эксмо','Москва'),(2,'АСТ','Москва'),(3,'Самовар','Москва'),(4,'Проф-пресс','Аксай'),(5,'РИПОЛ классик','Москва'),(6,'Альфа-Книга','Москва'),(7,'Мартин','Тверь'),(8,'Новое Небо','Москва');
/*!40000 ALTER TABLE `publisher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reader`
--

DROP TABLE IF EXISTS `reader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reader` (
  `reader_id` int(11) NOT NULL AUTO_INCREMENT,
  `reader_name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `reader_email` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `reader_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`reader_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reader`
--

LOCK TABLES `reader` WRITE;
/*!40000 ALTER TABLE `reader` DISABLE KEYS */;
INSERT INTO `reader` VALUES (1,'Петров Василий Олегович','vaspo@mail.ru','7(961)536-21-11'),(2,'Малышева Светлана Алексеевна','sveta87@gmail.com','7(968)438-26-77'),(3,'Ванин Сергей Евгеньевич','vann_s@yandex.ru','7(999)268-54-98'),(4,'Яковлев Владислав Дмитриевич','vlad_45@mail.ru','7(989)234-37-24'),(5,'Орлова Виктория Денисовна','vicomail@gmail.com','7(956)532-71-62'),(6,'Самойлова Екатерина Викторовна','katsam@yandex.ru','7(976)231-38-68');
/*!40000 ALTER TABLE `reader` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-06-03 17:06:14
