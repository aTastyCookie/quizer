-- MySQL dump 10.13  Distrib 5.5.43, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: quizeryii2
-- ------------------------------------------------------
-- Server version	5.5.43-0ubuntu0.14.04.1

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
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8_general_mysql500_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1432494571);
INSERT INTO `migration` VALUES ('m140209_132017_init',1432494577);
INSERT INTO `migration` VALUES ('m140403_174025_create_account_table',1432494578);
INSERT INTO `migration` VALUES ('m140504_113157_update_tables',1432494578);
INSERT INTO `migration` VALUES ('m140504_130429_create_token_table',1432494578);
INSERT INTO `migration` VALUES ('m140830_171933_fix_ip_field',1432494578);
INSERT INTO `migration` VALUES ('m140830_172703_change_account_table_name',1432494578);
INSERT INTO `migration` VALUES ('m141222_110026_update_ip_field',1432494578);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `node`
--

DROP TABLE IF EXISTS `node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_general_mysql500_ci NOT NULL,
  `quest_id` int(11) NOT NULL,
  `next` int(11) NOT NULL DEFAULT '0',
  `prev` int(11) NOT NULL DEFAULT '0',
  `prev2` int(11) NOT NULL DEFAULT '0',
  `top` int(11) NOT NULL DEFAULT '0',
  `left` int(11) NOT NULL DEFAULT '0',
  `answer` text COLLATE utf8_general_mysql500_ci,
  `case_depend` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `quest_id` (`quest_id`),
  CONSTRAINT `node_ibfk_1` FOREIGN KEY (`quest_id`) REFERENCES `quest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `node`
--

LOCK TABLES `node` WRITE;
/*!40000 ALTER TABLE `node` DISABLE KEYS */;
INSERT INTO `node` VALUES (1,'node1',1,0,4,8,420,700,'',0);
INSERT INTO `node` VALUES (2,'dasda',1,5,0,9,220,50,'',0);
INSERT INTO `node` VALUES (3,'q1',2,0,0,0,0,0,NULL,0);
INSERT INTO `node` VALUES (4,'3node',1,1,9,0,220,540,NULL,0);
INSERT INTO `node` VALUES (5,'как вы относитесь к Путину',1,6,2,0,340,250,NULL,0);
INSERT INTO `node` VALUES (6,'Wehsbsbbs',1,9,5,0,180,260,NULL,0);
INSERT INTO `node` VALUES (7,'q2',2,0,0,0,0,0,NULL,0);
INSERT INTO `node` VALUES (8,'XXXXX',1,0,0,0,370,960,NULL,0);
INSERT INTO `node` VALUES (9,'vasya',1,4,6,0,30,330,NULL,0);
/*!40000 ALTER TABLE `node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `public_email` varchar(255) DEFAULT NULL,
  `gravatar_email` varchar(255) DEFAULT NULL,
  `gravatar_id` varchar(32) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `bio` text,
  `img_path` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_user_profile` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile`
--

LOCK TABLES `profile` WRITE;
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
INSERT INTO `profile` VALUES (1,'тест','','ifourspb@gmail.com','8266f127124ff78e5d1f99f0c30afcd5','','','','2WAF4dkJowDJp-uDMPfQVWGMkKGVybAH.jpg');
INSERT INTO `profile` VALUES (2,'Roman Ananyev','me@rananyev.ru','me@rananyev.ru','565ffe3c79f7ad7b74f5e89fea782dbe','Moscow','https://rananyev.ru','I spend my spare time on my own projects, improvement of myself and the world around me. \\m/','S86TUPOQhLMBCAdDjGq8jxK1JddWKSyZ.jpg');
INSERT INTO `profile` VALUES (3,NULL,NULL,'slavsevast@gmail.com','2105ca8fcae5e62a35fa2144b1928f2b',NULL,NULL,NULL,NULL);
INSERT INTO `profile` VALUES (4,'','','ant1freezeca@gmail.com','d25e593736ccfcb2524eb0ca63f2d102','','','','');
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quest`
--

DROP TABLE IF EXISTS `quest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) COLLATE utf8_general_mysql500_ci NOT NULL,
  `logo` varchar(500) COLLATE utf8_general_mysql500_ci DEFAULT NULL,
  `complexity` int(2) DEFAULT '1',
  `url` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL,
  `short` varchar(500) COLLATE utf8_general_mysql500_ci DEFAULT NULL,
  `descr` text COLLATE utf8_general_mysql500_ci,
  `date_start` datetime NOT NULL,
  `date_finish` datetime DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quest`
--

LOCK TABLES `quest` WRITE;
/*!40000 ALTER TABLE `quest` DISABLE KEYS */;
INSERT INTO `quest` VALUES (1,'test','-2_RAjYJy3rfUdu28YnApovoNd1_XQlo.jpg',NULL,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00','7rim4h9ae');
INSERT INTO `quest` VALUES (2,'test2','',NULL,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00','7rim4h9ae');
/*!40000 ALTER TABLE `quest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_account`
--

DROP TABLE IF EXISTS `social_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `social_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `provider` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_unique` (`provider`,`client_id`),
  KEY `fk_user_account` (`user_id`),
  CONSTRAINT `fk_user_account` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_account`
--

LOCK TABLES `social_account` WRITE;
/*!40000 ALTER TABLE `social_account` DISABLE KEYS */;
INSERT INTO `social_account` VALUES (1,3,'facebook','855521214496700','{\"id\":\"855521214496700\",\"email\":\"slavsevast@gmail.com\",\"first_name\":\"\\u0410\\u043b\\u044c\\u0431\\u0435\\u0440\\u0442\",\"gender\":\"male\",\"last_name\":\"\\u041c\\u0438\\u0440\\u0441\\u043a\\u043e\\u0439\",\"link\":\"https:\\/\\/www.facebook.com\\/app_scoped_user_id\\/855521214496700\\/\",\"locale\":\"ru_RU\",\"name\":\"\\u0410\\u043b\\u044c\\u0431\\u0435\\u0440\\u0442 \\u041c\\u0438\\u0440\\u0441\\u043a\\u043e\\u0439\",\"timezone\":3,\"updated_time\":\"2015-03-31T10:58:59+0000\",\"verified\":true}');
INSERT INTO `social_account` VALUES (3,1,'vkontakte','368412','{\"user_id\":368412,\"email\":\"ifourspb@gmail.com\",\"uid\":368412,\"first_name\":\"\\u0410\\u043b\\u044c\\u0431\\u0435\\u0440\\u0442\",\"last_name\":\"\\u041c\\u0438\\u0440\\u0441\\u043a\\u043e\\u0439\",\"sex\":2,\"nickname\":\"\",\"screen_name\":\"mirskoy\",\"bdate\":\"1.1.1910\",\"city\":87,\"country\":1,\"timezone\":3,\"photo\":\"http:\\/\\/cs628030.vk.me\\/v628030412\\/30f9\\/C4PYRNcMhkY.jpg\",\"id\":368412}');
INSERT INTO `social_account` VALUES (4,2,'facebook','923640541033615','{\"id\":\"923640541033615\",\"bio\":\"i`m here\",\"birthday\":\"03\\/24\\/1993\",\"email\":\"ant1freezeca@gmail.com\",\"first_name\":\"Roman\",\"gender\":\"male\",\"last_name\":\"Ananyev\",\"link\":\"https:\\/\\/www.facebook.com\\/app_scoped_user_id\\/923640541033615\\/\",\"locale\":\"ru_RU\",\"name\":\"Ananyev Roman\",\"timezone\":3,\"updated_time\":\"2015-06-07T01:44:37+0000\",\"verified\":true,\"website\":\"http:\\/\\/atastycookie.me\\/\"}');
INSERT INTO `social_account` VALUES (5,2,'vkontakte','16867849','{\"user_id\":16867849,\"email\":\"me@rananyev.ru\",\"uid\":16867849,\"first_name\":\"\\u0420\\u043e\\u043c\\u0430\\u043d\",\"last_name\":\"\\u0410\\u043d\\u0430\\u043d\\u044c\\u0435\\u0432\",\"sex\":2,\"nickname\":\"\",\"screen_name\":\"rsananyev\",\"bdate\":\"24.3.1993\",\"city\":1,\"country\":1,\"timezone\":3,\"photo\":\"https:\\/\\/pp.vk.me\\/c616421\\/v616421849\\/1df53\\/OSsw_RPORfc.jpg\",\"id\":16867849}');
INSERT INTO `social_account` VALUES (6,2,'twitter','595437354','{\"id\":595437354,\"id_str\":\"595437354\",\"name\":\"Ananyev Roman\",\"screen_name\":\"AnanyevRoman\",\"location\":\"\",\"description\":\"\",\"url\":\"http:\\/\\/t.co\\/7cnqotXDHm\",\"entities\":{\"url\":{\"urls\":[{\"url\":\"http:\\/\\/t.co\\/7cnqotXDHm\",\"expanded_url\":\"http:\\/\\/atastycookie.me\",\"display_url\":\"atastycookie.me\",\"indices\":[0,22]}]},\"description\":{\"urls\":[]}},\"protected\":false,\"followers_count\":23,\"friends_count\":10,\"listed_count\":0,\"created_at\":\"Thu May 31 10:01:16 +0000 2012\",\"favourites_count\":3,\"utc_offset\":14400,\"time_zone\":\"Moscow\",\"geo_enabled\":true,\"verified\":false,\"statuses_count\":429,\"lang\":\"ru\",\"status\":{\"created_at\":\"Sat May 30 11:09:26 +0000 2015\",\"id\":604605531907616769,\"id_str\":\"604605531907616769\",\"text\":\"\\u041e\\u0431\\u044f\\u0437\\u0430\\u0442\\u0435\\u043b\\u044c\\u043d\\u043e \\u043a \\u043f\\u0440\\u043e\\u0441\\u043c\\u043e\\u0442\\u0440\\u0443! http:\\/\\/t.co\\/hLzryRNNj9\",\"source\":\"<a href=\\\"http:\\/\\/vk.com\\\" rel=\\\"nofollow\\\">vk.com<\\/a>\",\"truncated\":false,\"in_reply_to_status_id\":null,\"in_reply_to_status_id_str\":null,\"in_reply_to_user_id\":null,\"in_reply_to_user_id_str\":null,\"in_reply_to_screen_name\":null,\"geo\":null,\"coordinates\":null,\"place\":null,\"contributors\":null,\"retweet_count\":0,\"favorite_count\":0,\"entities\":{\"hashtags\":[],\"symbols\":[],\"user_mentions\":[],\"urls\":[{\"url\":\"http:\\/\\/t.co\\/hLzryRNNj9\",\"expanded_url\":\"http:\\/\\/vk.cc\\/3Qqswb\",\"display_url\":\"vk.cc\\/3Qqswb\",\"indices\":[25,47]}]},\"favorited\":false,\"retweeted\":false,\"possibly_sensitive\":false,\"lang\":\"ru\"},\"contributors_enabled\":false,\"is_translator\":false,\"is_translation_enabled\":false,\"profile_background_color\":\"131516\",\"profile_background_image_url\":\"http:\\/\\/abs.twimg.com\\/images\\/themes\\/theme14\\/bg.gif\",\"profile_background_image_url_https\":\"https:\\/\\/abs.twimg.com\\/images\\/themes\\/theme14\\/bg.gif\",\"profile_background_tile\":true,\"profile_image_url\":\"http:\\/\\/pbs.twimg.com\\/profile_images\\/495474947741843456\\/TlMMbUAG_normal.jpeg\",\"profile_image_url_https\":\"https:\\/\\/pbs.twimg.com\\/profile_images\\/495474947741843456\\/TlMMbUAG_normal.jpeg\",\"profile_banner_url\":\"https:\\/\\/pbs.twimg.com\\/profile_banners\\/595437354\\/1354950225\",\"profile_link_color\":\"009999\",\"profile_sidebar_border_color\":\"EEEEEE\",\"profile_sidebar_fill_color\":\"EFEFEF\",\"profile_text_color\":\"333333\",\"profile_use_background_image\":true,\"default_profile\":false,\"default_profile_image\":false,\"following\":false,\"follow_request_sent\":false,\"notifications\":false}');
INSERT INTO `social_account` VALUES (7,2,'google','103169831794510768441','{\"kind\":\"plus#person\",\"etag\":\"\\\"RqKWnRU4WW46-6W3rWhLR9iFZQM\\/hH6v9MFDP1ipzUWDQHKvwQBxdSY\\\"\",\"occupation\":\"eCommerce\",\"birthday\":\"0000-03-24\",\"gender\":\"male\",\"emails\":[{\"value\":\"ant1freezeca@gmail.com\",\"type\":\"account\"}],\"objectType\":\"person\",\"id\":\"103169831794510768441\",\"displayName\":\"\\u0420\\u043e\\u043c\\u0430\\u043d \\u0410\\u043d\\u0430\\u043d\\u044c\\u0435\\u0432\",\"name\":{\"familyName\":\"\\u0410\\u043d\\u0430\\u043d\\u044c\\u0435\\u0432\",\"givenName\":\"\\u0420\\u043e\\u043c\\u0430\\u043d\"},\"url\":\"https:\\/\\/plus.google.com\\/+\\u0420\\u043e\\u043c\\u0430\\u043d\\u0410\\u043d\\u0430\\u043d\\u044c\\u0435\\u0432\",\"image\":{\"url\":\"https:\\/\\/lh3.googleusercontent.com\\/-C6pSzkInt-Y\\/AAAAAAAAAAI\\/AAAAAAAAAf8\\/oZ8HlmtSDZY\\/photo.jpg?sz=50\",\"isDefault\":false},\"organizations\":[{\"name\":\"Yandex.Money\",\"title\":\"\\u041c\\u0435\\u043d\\u0435\\u0434\\u0436\\u0435\\u0440 \\u043f\\u0440\\u043e\\u0435\\u043a\\u0442\\u043e\\u0432\",\"type\":\"work\",\"startDate\":\"2014\",\"primary\":true}],\"placesLived\":[{\"value\":\"\\u041c\\u043e\\u0441\\u043a\\u0432\\u0430\",\"primary\":true}],\"isPlusUser\":true,\"language\":\"ru\",\"circledByCount\":117,\"verified\":false,\"cover\":{\"layout\":\"banner\",\"coverPhoto\":{\"url\":\"https:\\/\\/lh3.googleusercontent.com\\/-LvV8xjftuwI\\/T9kKSryho3I\\/AAAAAAAAAGY\\/nXJWH0PFSLE\\/s630\\/Rainbokeh.jpg\",\"height\":626,\"width\":940},\"coverInfo\":{\"topImageOffset\":0,\"leftImageOffset\":0}}}');
INSERT INTO `social_account` VALUES (8,2,'github','2944373','{\"login\":\"aTastyCookie\",\"id\":2944373,\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/2944373?v=3\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/aTastyCookie\",\"html_url\":\"https:\\/\\/github.com\\/aTastyCookie\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/aTastyCookie\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/aTastyCookie\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/aTastyCookie\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/aTastyCookie\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/aTastyCookie\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/aTastyCookie\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/aTastyCookie\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/aTastyCookie\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/aTastyCookie\\/received_events\",\"type\":\"User\",\"site_admin\":false,\"name\":\"Roman Ananyev\",\"company\":\"AppinTop, AdtoApp\",\"blog\":\"https:\\/\\/rananyev.ru\",\"location\":\"Moscow\",\"email\":\"me@rananyev.ru\",\"hireable\":true,\"bio\":null,\"public_repos\":42,\"public_gists\":2,\"followers\":29,\"following\":0,\"created_at\":\"2012-12-02T13:34:26Z\",\"updated_at\":\"2015-06-15T08:52:02Z\",\"private_gists\":33,\"total_private_repos\":7,\"owned_private_repos\":7,\"disk_usage\":928786,\"collaborators\":11,\"plan\":{\"name\":\"small\",\"space\":976562499,\"collaborators\":0,\"private_repos\":10}}');
INSERT INTO `social_account` VALUES (9,2,'yandex','157680231','{\"first_name\":\"\\u0420\\u043e\\u043c\\u0430\\u043d\",\"last_name\":\"\\u0410\\u043d\\u0430\\u043d\\u044c\\u0435\\u0432\",\"display_name\":\"\\u0410\\u043d\\u0430\\u043d\\u044c\\u0435\\u0432  \\u0420\\u043e\\u043c\\u0430\\u043d\",\"emails\":[\"ant1freezeca@yandex.ru\"],\"default_email\":\"ant1freezeca@yandex.ru\",\"real_name\":\"\\u0420\\u043e\\u043c\\u0430\\u043d \\u0410\\u043d\\u0430\\u043d\\u044c\\u0435\\u0432\",\"birthday\":\"1993-03-24\",\"default_avatar_id\":\"157680231\",\"login\":\"ant1freezeca\",\"sex\":\"male\",\"id\":\"157680231\"}');
/*!40000 ALTER TABLE `social_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `token`
--

DROP TABLE IF EXISTS `token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `token` (
  `user_id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `created_at` int(11) NOT NULL,
  `type` smallint(6) NOT NULL,
  UNIQUE KEY `token_unique` (`user_id`,`code`,`type`),
  CONSTRAINT `fk_user_token` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `token`
--

LOCK TABLES `token` WRITE;
/*!40000 ALTER TABLE `token` DISABLE KEYS */;
INSERT INTO `token` VALUES (1,'0SNHlbFLRzbvSg6QOuF2IyVoLsQkBx-H',1433008625,0);
INSERT INTO `token` VALUES (1,'6u0EPv2JYo_eipgO_Gt0seAgCaTaOWAB',1432496346,0);
INSERT INTO `token` VALUES (1,'7d-Hzt9uzuIqE8unUIk1RLc1I5xEyVyW',1433009053,0);
INSERT INTO `token` VALUES (1,'8_rCaA2s17ezV-8WmsbibWOHKsSpa3DZ',1433008958,0);
INSERT INTO `token` VALUES (1,'kmFT9scKzNb--b-fTSxU3qzfcnkvdPiO',1433008648,0);
INSERT INTO `token` VALUES (1,'Lp-U31-GCym729HFqkpyJwgWxHsIF6c8',1433008906,0);
INSERT INTO `token` VALUES (1,'Pau0aLeWYeYY5iErQL6SQoKJKkJYgqgd',1433008864,0);
INSERT INTO `token` VALUES (1,'u018eaIVkhrWFq6ZgdcT3V2PrmfUCMIT',1433008945,0);
INSERT INTO `token` VALUES (1,'ywQe0pzVisg-mmDEnYoOVtjc1pegcCV1',1433008998,0);
INSERT INTO `token` VALUES (2,'OdxiS2oV8mOy47t-IICUnZpRZ6CnpDTD',1433006524,0);
/*!40000 ALTER TABLE `token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(60) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `confirmed_at` int(11) DEFAULT NULL,
  `unconfirmed_email` varchar(255) DEFAULT NULL,
  `blocked_at` int(11) DEFAULT NULL,
  `registration_ip` varchar(45) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `flags` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_unique_username` (`username`),
  UNIQUE KEY `user_unique_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'vp1','ifourspb@gmail.com','$2y$10$LnolS7F4.7/Y6QE6YBgcIOOOypZ0P813hdsd4unfxW5tX8U3v8He6','_fN9XoxQ-KuV9ueIHo3xKxApP0oGsjEx',1433009138,NULL,NULL,'109.110.66.111',1432496346,1433009138,0);
INSERT INTO `user` VALUES (2,'root','me@rananyev.ru','$2y$10$3aHx/sNOWLXXQTx8BAi8ZOZIZP5swxTNEgqGS2GtmNlUUHPvX7myS','dH7kfdor2VsGPzZq8A3NsDOuHJHvFNDz',1433010015,NULL,NULL,'46.39.243.207',1433006524,1433006524,0);
INSERT INTO `user` VALUES (3,'slavsevast','slavsevast@gmail.com','$2y$10$mHdHXdHGbKtHkfLf9P0HQ.n1xqHqEcZ8VLrxBdl3ZF1QXsjX8zJRq','VLRizsVIPzBnPmkz9YRqjkYAwJ0KwEWR',1434285431,NULL,NULL,'109.110.66.104',1434285431,1434285431,0);
INSERT INTO `user` VALUES (4,'ant1freezeca','ant1freezeca@gmail.com','$2y$10$TBvGx/UUJ0.bA/3sHOS0wu93tJ6LhTZ7UHN0XzJEMiVHf0CLznoNy','_GrwkIknrom7E8VNR2ZiKWobIr0AgHrA',1434287595,NULL,NULL,'94.25.237.74',1434287595,1434400123,0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-07-22  0:00:01
