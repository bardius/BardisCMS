/*
SQLyog Community v12.11 (64 bit)
MySQL - 5.6.17 : Database - bardiscms
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `acl_classes` */

DROP TABLE IF EXISTS `acl_classes`;

CREATE TABLE `acl_classes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_type` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_69DD750638A36066` (`class_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `acl_classes` */

/*Table structure for table `acl_entries` */

DROP TABLE IF EXISTS `acl_entries`;

CREATE TABLE `acl_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` int(10) unsigned NOT NULL,
  `object_identity_id` int(10) unsigned DEFAULT NULL,
  `security_identity_id` int(10) unsigned NOT NULL,
  `field_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ace_order` smallint(5) unsigned NOT NULL,
  `mask` int(11) NOT NULL,
  `granting` tinyint(1) NOT NULL,
  `granting_strategy` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `audit_success` tinyint(1) NOT NULL,
  `audit_failure` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_46C8B806EA000B103D9AB4A64DEF17BCE4289BF4` (`class_id`,`object_identity_id`,`field_name`,`ace_order`),
  KEY `IDX_46C8B806EA000B103D9AB4A6DF9183C9` (`class_id`,`object_identity_id`,`security_identity_id`),
  KEY `IDX_46C8B806EA000B10` (`class_id`),
  KEY `IDX_46C8B8063D9AB4A6` (`object_identity_id`),
  KEY `IDX_46C8B806DF9183C9` (`security_identity_id`),
  CONSTRAINT `FK_46C8B806DF9183C9` FOREIGN KEY (`security_identity_id`) REFERENCES `acl_security_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_46C8B8063D9AB4A6` FOREIGN KEY (`object_identity_id`) REFERENCES `acl_object_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_46C8B806EA000B10` FOREIGN KEY (`class_id`) REFERENCES `acl_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `acl_entries` */

/*Table structure for table `acl_object_identities` */

DROP TABLE IF EXISTS `acl_object_identities`;

CREATE TABLE `acl_object_identities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_object_identity_id` int(10) unsigned DEFAULT NULL,
  `class_id` int(10) unsigned NOT NULL,
  `object_identifier` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `entries_inheriting` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9407E5494B12AD6EA000B10` (`object_identifier`,`class_id`),
  KEY `IDX_9407E54977FA751A` (`parent_object_identity_id`),
  CONSTRAINT `FK_9407E54977FA751A` FOREIGN KEY (`parent_object_identity_id`) REFERENCES `acl_object_identities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `acl_object_identities` */

/*Table structure for table `acl_object_identity_ancestors` */

DROP TABLE IF EXISTS `acl_object_identity_ancestors`;

CREATE TABLE `acl_object_identity_ancestors` (
  `object_identity_id` int(10) unsigned NOT NULL,
  `ancestor_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`object_identity_id`,`ancestor_id`),
  KEY `IDX_825DE2993D9AB4A6` (`object_identity_id`),
  KEY `IDX_825DE299C671CEA1` (`ancestor_id`),
  CONSTRAINT `FK_825DE299C671CEA1` FOREIGN KEY (`ancestor_id`) REFERENCES `acl_object_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_825DE2993D9AB4A6` FOREIGN KEY (`object_identity_id`) REFERENCES `acl_object_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `acl_object_identity_ancestors` */

/*Table structure for table `acl_security_identities` */

DROP TABLE IF EXISTS `acl_security_identities`;

CREATE TABLE `acl_security_identities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `username` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8835EE78772E836AF85E0677` (`identifier`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `acl_security_identities` */

/*Table structure for table `bannercontent_blocks` */

DROP TABLE IF EXISTS `bannercontent_blocks`;

CREATE TABLE `bannercontent_blocks` (
  `page_id` int(11) NOT NULL,
  `contentblock_id` int(11) NOT NULL,
  PRIMARY KEY (`page_id`,`contentblock_id`),
  KEY `IDX_F4D586C4663E4` (`page_id`),
  KEY `IDX_F4D58642ADBAC2` (`contentblock_id`),
  CONSTRAINT `FK_F4D58642ADBAC2` FOREIGN KEY (`contentblock_id`) REFERENCES `content_blocks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_F4D586C4663E4` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `bannercontent_blocks` */

insert  into `bannercontent_blocks`(`page_id`,`contentblock_id`) values (1,6),(1,7);

/*Table structure for table `blog_bannercontent_blocks` */

DROP TABLE IF EXISTS `blog_bannercontent_blocks`;

CREATE TABLE `blog_bannercontent_blocks` (
  `blog_id` int(11) NOT NULL,
  `contentblock_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`contentblock_id`),
  KEY `IDX_BBBD8485DAE07E97` (`blog_id`),
  KEY `IDX_BBBD848542ADBAC2` (`contentblock_id`),
  CONSTRAINT `FK_BBBD848542ADBAC2` FOREIGN KEY (`contentblock_id`) REFERENCES `content_blocks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_BBBD8485DAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_bannercontent_blocks` */

/*Table structure for table `blog_extracontent_blocks` */

DROP TABLE IF EXISTS `blog_extracontent_blocks`;

CREATE TABLE `blog_extracontent_blocks` (
  `blog_id` int(11) NOT NULL,
  `contentblock_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`contentblock_id`),
  KEY `IDX_D0FE99C6DAE07E97` (`blog_id`),
  KEY `IDX_D0FE99C642ADBAC2` (`contentblock_id`),
  CONSTRAINT `FK_D0FE99C642ADBAC2` FOREIGN KEY (`contentblock_id`) REFERENCES `content_blocks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_D0FE99C6DAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_extracontent_blocks` */

/*Table structure for table `blog_maincontent_blocks` */

DROP TABLE IF EXISTS `blog_maincontent_blocks`;

CREATE TABLE `blog_maincontent_blocks` (
  `blog_id` int(11) NOT NULL,
  `contentblock_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`contentblock_id`),
  KEY `IDX_1FB7CF4EDAE07E97` (`blog_id`),
  KEY `IDX_1FB7CF4E42ADBAC2` (`contentblock_id`),
  CONSTRAINT `FK_1FB7CF4E42ADBAC2` FOREIGN KEY (`contentblock_id`) REFERENCES `content_blocks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_1FB7CF4EDAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_maincontent_blocks` */

insert  into `blog_maincontent_blocks`(`blog_id`,`contentblock_id`) values (5,5);

/*Table structure for table `blog_modalcontent_blocks` */

DROP TABLE IF EXISTS `blog_modalcontent_blocks`;

CREATE TABLE `blog_modalcontent_blocks` (
  `blog_id` int(11) NOT NULL,
  `contentblock_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`contentblock_id`),
  KEY `IDX_3262B322DAE07E97` (`blog_id`),
  KEY `IDX_3262B32242ADBAC2` (`contentblock_id`),
  CONSTRAINT `FK_3262B32242ADBAC2` FOREIGN KEY (`contentblock_id`) REFERENCES `content_blocks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_3262B322DAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_modalcontent_blocks` */

/*Table structure for table `blogs` */

DROP TABLE IF EXISTS `blogs`;

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` int(11) DEFAULT NULL,
  `introimage` int(11) DEFAULT NULL,
  `bgimage` int(11) DEFAULT NULL,
  `introvideo` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pageOrder` int(11) NOT NULL,
  `showPageTitle` int(11) NOT NULL,
  `publishState` int(11) NOT NULL,
  `pageclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `introtext` longtext COLLATE utf8_unicode_ci,
  `intromediasize` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `introclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pagetype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_last_modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F41BCA70E16C6B94` (`alias`),
  UNIQUE KEY `UNIQ_F41BCA70F3890D5F` (`introimage`),
  UNIQUE KEY `UNIQ_F41BCA7097AB4E12` (`bgimage`),
  UNIQUE KEY `UNIQ_F41BCA704A73D32C` (`introvideo`),
  KEY `IDX_F41BCA70BDAFD8C8` (`author`),
  CONSTRAINT `FK_F41BCA704A73D32C` FOREIGN KEY (`introvideo`) REFERENCES `media__media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_F41BCA7097AB4E12` FOREIGN KEY (`bgimage`) REFERENCES `media__media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_F41BCA70BDAFD8C8` FOREIGN KEY (`author`) REFERENCES `fos_user_user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_F41BCA70F3890D5F` FOREIGN KEY (`introimage`) REFERENCES `media__media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blogs` */

insert  into `blogs`(`id`,`author`,`introimage`,`bgimage`,`introvideo`,`date`,`title`,`alias`,`pageOrder`,`showPageTitle`,`publishState`,`pageclass`,`description`,`keywords`,`introtext`,`intromediasize`,`introclass`,`pagetype`,`date_last_modified`) values (1,1,NULL,NULL,NULL,'2015-05-14','Blog Home','articles',99,1,1,NULL,NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.',NULL,NULL,'blog_home','2015-05-14 15:04:11'),(2,1,NULL,NULL,NULL,'2015-05-14','News','news',99,1,1,NULL,NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.',NULL,NULL,'blog_cat_page','2015-05-14 15:04:11'),(3,1,NULL,NULL,NULL,'2015-05-14','Events','events',99,1,1,NULL,NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.',NULL,NULL,'blog_cat_page','2015-05-14 15:04:11'),(4,1,NULL,NULL,NULL,'2015-05-14','Blog Filtered Listing','tagged',99,1,1,NULL,NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.',NULL,NULL,'blog_filtered_list','2015-05-14 15:04:11'),(5,1,5,NULL,NULL,'2015-05-14','Test Blog Post 1','test-blog-post-1',99,1,1,NULL,NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.',NULL,NULL,'blog_article','2015-05-14 15:04:11'),(6,1,6,NULL,NULL,'2015-05-14','Test Blog Post 2','test-blog-post-2',99,1,1,NULL,NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.',NULL,NULL,'blog_article','2015-05-14 15:04:11'),(7,1,7,NULL,NULL,'2015-05-14','Test Blog Post 3','test-blog-post-3',99,1,1,NULL,NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.',NULL,NULL,'blog_article','2015-05-14 15:04:11'),(8,1,8,NULL,NULL,'2015-05-14','Test Blog Post 4','test-blog-post-4',99,1,1,NULL,NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.',NULL,NULL,'blog_article','2015-05-14 15:04:11');

/*Table structure for table `blogs_categories` */

DROP TABLE IF EXISTS `blogs_categories`;

CREATE TABLE `blogs_categories` (
  `blog_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`category_id`),
  KEY `IDX_9DB3BC97DAE07E97` (`blog_id`),
  KEY `IDX_9DB3BC9712469DE2` (`category_id`),
  CONSTRAINT `FK_9DB3BC9712469DE2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_9DB3BC97DAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blogs_categories` */

insert  into `blogs_categories`(`blog_id`,`category_id`) values (5,2),(6,3),(7,2),(8,3);

/*Table structure for table `blogs_tags` */

DROP TABLE IF EXISTS `blogs_tags`;

CREATE TABLE `blogs_tags` (
  `blog_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`tag_id`),
  KEY `IDX_B21862B8DAE07E97` (`blog_id`),
  KEY `IDX_B21862B8BAD26311` (`tag_id`),
  CONSTRAINT `FK_B21862B8BAD26311` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_B21862B8DAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blogs_tags` */

insert  into `blogs_tags`(`blog_id`,`tag_id`) values (5,1),(6,2),(7,2),(8,1);

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `categoryClass` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_last_modified` datetime NOT NULL,
  `categoryIcon` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3AF34668AD0F3245` (`categoryIcon`),
  CONSTRAINT `FK_3AF34668AD0F3245` FOREIGN KEY (`categoryIcon`) REFERENCES `media__media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `categories` */

insert  into `categories`(`id`,`title`,`categoryClass`,`date_last_modified`,`categoryIcon`) values (1,'Homepage',NULL,'2015-05-14 15:04:10',NULL),(2,'News','news','2015-05-14 15:04:10',NULL),(3,'Events','events','2015-05-14 15:04:10',NULL),(4,'Sample Category','featured-category','2015-05-14 15:04:10',NULL);

/*Table structure for table `classification__category` */

DROP TABLE IF EXISTS `classification__category`;

CREATE TABLE `classification__category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `context` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_43629B36727ACA70` (`parent_id`),
  KEY `IDX_43629B36E25D857E` (`context`),
  KEY `IDX_43629B36EA9FDD75` (`media_id`),
  CONSTRAINT `FK_43629B36EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media__media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_43629B36727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `classification__category` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_43629B36E25D857E` FOREIGN KEY (`context`) REFERENCES `classification__context` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `classification__category` */

insert  into `classification__category`(`id`,`parent_id`,`context`,`media_id`,`name`,`enabled`,`slug`,`description`,`position`,`created_at`,`updated_at`) values (1,NULL,'default',NULL,'default',1,'default','Default Media Category',NULL,'2015-05-14 15:04:11','2015-05-14 15:04:11'),(2,NULL,'intro',NULL,'intro',1,'intro','Intro Media Category',NULL,'2015-05-14 15:04:11','2015-05-14 15:04:11'),(3,NULL,'bgimage',NULL,'bgimage',1,'bgimage','Background Image Media Category',NULL,'2015-05-14 15:04:11','2015-05-14 15:04:11'),(4,NULL,'icons',NULL,'icons',1,'icons','Icons Media Category',NULL,'2015-05-14 15:04:11','2015-05-14 15:04:11');

/*Table structure for table `classification__collection` */

DROP TABLE IF EXISTS `classification__collection`;

CREATE TABLE `classification__collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `context` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_collection` (`slug`,`context`),
  KEY `IDX_A406B56AE25D857E` (`context`),
  KEY `IDX_A406B56AEA9FDD75` (`media_id`),
  CONSTRAINT `FK_A406B56AEA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media__media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_A406B56AE25D857E` FOREIGN KEY (`context`) REFERENCES `classification__context` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `classification__collection` */

/*Table structure for table `classification__context` */

DROP TABLE IF EXISTS `classification__context`;

CREATE TABLE `classification__context` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `classification__context` */

insert  into `classification__context`(`id`,`name`,`enabled`,`created_at`,`updated_at`) values ('bgimage','bgimage',1,'2015-05-14 15:04:11','2015-05-14 15:04:11'),('default','default',1,'2015-05-14 15:04:11','2015-05-14 15:04:11'),('icons','icons',1,'2015-05-14 15:04:11','2015-05-14 15:04:11'),('intro','intro',1,'2015-05-14 15:04:11','2015-05-14 15:04:11');

/*Table structure for table `classification__tag` */

DROP TABLE IF EXISTS `classification__tag`;

CREATE TABLE `classification__tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `context` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_context` (`slug`,`context`),
  KEY `IDX_CA57A1C7E25D857E` (`context`),
  CONSTRAINT `FK_CA57A1C7E25D857E` FOREIGN KEY (`context`) REFERENCES `classification__context` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `classification__tag` */

/*Table structure for table `comments` */

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` longtext COLLATE utf8_unicode_ci NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `commentType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bottrap` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_last_modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F9E962ADAE07E97` (`blog_id`),
  CONSTRAINT `FK_5F9E962ADAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `comments` */

insert  into `comments`(`id`,`blog_id`,`title`,`username`,`comment`,`approved`,`created`,`commentType`,`bottrap`,`date_last_modified`) values (1,5,'Sample Comment 1','blogger1','To make a long story short. You can\'t go wrong by choosing Symfony! And no one has ever been fired for using Symfony.',1,'2015-05-14 15:04:11','Blog',NULL,'2015-05-14 15:04:11'),(2,5,'Sample Comment 2','blogger2','To make a long story short. You can\'t go wrong by choosing Symfony! And no one has ever been fired for using Symfony 2.',1,'2015-05-14 15:04:11','Blog',NULL,'2015-05-14 15:04:11'),(3,5,'Sample Comment 3','blogger3','To make a long story short. You can\'t go wrong by choosing Symfony! And no one has ever been fired for using Symfony 3.',1,'2015-05-14 15:04:11','Blog',NULL,'2015-05-14 15:04:11');

/*Table structure for table `content_blocks` */

DROP TABLE IF EXISTS `content_blocks`;

CREATE TABLE `content_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slide` int(11) DEFAULT NULL,
  `vimeo` int(11) DEFAULT NULL,
  `youtube` int(11) DEFAULT NULL,
  `globalblock` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `publishedState` int(11) NOT NULL,
  `availability` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `showTitle` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `className` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sizeClass` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mediaSize` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contentType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `htmlText` longtext COLLATE utf8_unicode_ci,
  `date_last_modified` datetime NOT NULL,
  `fileFile` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A6DBE5D472EFEE62` (`slide`),
  UNIQUE KEY `UNIQ_A6DBE5D44E850E4D` (`fileFile`),
  UNIQUE KEY `UNIQ_A6DBE5D47316E1A3` (`vimeo`),
  UNIQUE KEY `UNIQ_A6DBE5D4F0789934` (`youtube`),
  UNIQUE KEY `UNIQ_A6DBE5D4DF69D755` (`globalblock`),
  CONSTRAINT `FK_A6DBE5D4DF69D755` FOREIGN KEY (`globalblock`) REFERENCES `content_globalblock` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_A6DBE5D44E850E4D` FOREIGN KEY (`fileFile`) REFERENCES `media__media` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_A6DBE5D472EFEE62` FOREIGN KEY (`slide`) REFERENCES `content_slides` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_A6DBE5D47316E1A3` FOREIGN KEY (`vimeo`) REFERENCES `media__media` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_A6DBE5D4F0789934` FOREIGN KEY (`youtube`) REFERENCES `media__media` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `content_blocks` */

insert  into `content_blocks`(`id`,`slide`,`vimeo`,`youtube`,`globalblock`,`title`,`publishedState`,`availability`,`showTitle`,`ordering`,`className`,`sizeClass`,`mediaSize`,`idName`,`contentType`,`htmlText`,`date_last_modified`,`fileFile`) values (1,NULL,NULL,NULL,NULL,'Sample Content Home',1,'page',1,1,NULL,'large-12',NULL,NULL,'html','<p>Quisque non arcu id ipsum imperdiet ultricies pharetra eu nibh. Etiam eros lectus, ullamcorper et congue in, lobortis sit amet lectus. In fermentum quam in arcu sodales, id varius est placerat. Fusce a dictum mi. Aliquam accumsan diam eget rutrum tincidunt. Nullam massa metus, placerat quis mattis nec</p>','2015-05-14 15:04:11',NULL),(2,NULL,NULL,NULL,NULL,'Sample Content 1',1,'page',1,1,'sampleClassname','large-12',NULL,'sampleId','html','<p>Quisque non arcu id ipsum imperdiet ultricies pharetra eu nibh. Etiam eros lectus, ullamcorper et congue in, lobortis sit amet lectus. In fermentum quam in arcu sodales, id varius est placerat. Fusce a dictum mi. Aliquam accumsan diam eget rutrum tincidunt. Nullam massa metus, placerat quis mattis nec</p>','2015-05-14 15:04:11',NULL),(3,NULL,NULL,NULL,NULL,'Sample Content 2',1,'page',1,2,NULL,'large-12',NULL,NULL,'html','<p>Quisque non arcu id ipsum imperdiet ultricies pharetra eu nibh. Etiam eros lectus, ullamcorper et congue in, lobortis sit amet lectus. In fermentum quam in arcu sodales, id varius est placerat. Fusce a dictum mi. Aliquam accumsan diam eget rutrum tincidunt. Nullam massa metus, placerat quis mattis nec</p>','2015-05-14 15:04:11',NULL),(4,NULL,NULL,NULL,NULL,'Sample Contact Form',1,'page',1,1,NULL,'large-12',NULL,NULL,'contact',NULL,'2015-05-14 15:04:11',NULL),(5,NULL,NULL,NULL,NULL,'Sample Blog Content 1',1,'page',1,1,'sampleClassname','large-12',NULL,'sampleId','html','<p>Quisque non arcu id ipsum imperdiet ultricies pharetra eu nibh. Etiam eros lectus, ullamcorper et congue in, lobortis sit amet lectus. In fermentum quam in arcu sodales, id varius est placerat. Fusce a dictum mi. Aliquam accumsan diam eget rutrum tincidunt. Nullam massa metus, placerat quis mattis nec</p>','2015-05-14 15:04:11',NULL),(6,1,NULL,NULL,NULL,'Home Top Banner Slide 1',1,'page',0,1,NULL,'large-12',NULL,NULL,'slide',NULL,'2015-05-14 15:04:11',NULL),(7,2,NULL,NULL,NULL,'Home Top Banner Slide 2',1,'page',0,2,NULL,'large-12',NULL,NULL,'slide',NULL,'2015-05-14 15:04:11',NULL);

/*Table structure for table `content_blocks_images` */

DROP TABLE IF EXISTS `content_blocks_images`;

CREATE TABLE `content_blocks_images` (
  `contentblock_id` int(11) NOT NULL,
  `contentimage_id` int(11) NOT NULL,
  PRIMARY KEY (`contentblock_id`,`contentimage_id`),
  KEY `IDX_960CFC1F42ADBAC2` (`contentblock_id`),
  KEY `IDX_960CFC1F96E51DA3` (`contentimage_id`),
  CONSTRAINT `FK_960CFC1F96E51DA3` FOREIGN KEY (`contentimage_id`) REFERENCES `content_images` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_960CFC1F42ADBAC2` FOREIGN KEY (`contentblock_id`) REFERENCES `content_blocks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `content_blocks_images` */

/*Table structure for table `content_globalblock` */

DROP TABLE IF EXISTS `content_globalblock`;

CREATE TABLE `content_globalblock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contentblock` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8C437DD3135C0AFC` (`contentblock`),
  CONSTRAINT `FK_8C437DD3135C0AFC` FOREIGN KEY (`contentblock`) REFERENCES `content_blocks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `content_globalblock` */

/*Table structure for table `content_images` */

DROP TABLE IF EXISTS `content_images`;

CREATE TABLE `content_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imagefile` int(11) DEFAULT NULL,
  `imageOrder` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8829CEC6991EFFB9` (`imagefile`),
  CONSTRAINT `FK_8829CEC6991EFFB9` FOREIGN KEY (`imagefile`) REFERENCES `media__media` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `content_images` */

/*Table structure for table `content_slides` */

DROP TABLE IF EXISTS `content_slides`;

CREATE TABLE `content_slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imagefile` int(11) DEFAULT NULL,
  `imageLinkTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `imageLinkURL` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D0F6503D991EFFB9` (`imagefile`),
  CONSTRAINT `FK_D0F6503D991EFFB9` FOREIGN KEY (`imagefile`) REFERENCES `media__media` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `content_slides` */

insert  into `content_slides`(`id`,`imagefile`,`imageLinkTitle`,`imageLinkURL`) values (1,9,'Slide 1','/blog/events'),(2,10,'Slide 2',NULL);

/*Table structure for table `extracontent_blocks` */

DROP TABLE IF EXISTS `extracontent_blocks`;

CREATE TABLE `extracontent_blocks` (
  `page_id` int(11) NOT NULL,
  `contentblock_id` int(11) NOT NULL,
  PRIMARY KEY (`page_id`,`contentblock_id`),
  KEY `IDX_92E89973C4663E4` (`page_id`),
  KEY `IDX_92E8997342ADBAC2` (`contentblock_id`),
  CONSTRAINT `FK_92E8997342ADBAC2` FOREIGN KEY (`contentblock_id`) REFERENCES `content_blocks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_92E89973C4663E4` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `extracontent_blocks` */

/*Table structure for table `fos_user_group` */

DROP TABLE IF EXISTS `fos_user_group`;

CREATE TABLE `fos_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_583D1F3E5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `fos_user_group` */

/*Table structure for table `fos_user_user` */

DROP TABLE IF EXISTS `fos_user_user`;

CREATE TABLE `fos_user_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `date_of_birth` datetime DEFAULT NULL,
  `firstname` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `biography` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locale` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_uid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  `twitter_uid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter_data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  `gplus_uid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gplus_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gplus_data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `two_step_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bakeFrequency` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sex` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bakeChoises` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `age` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `children` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `campaign` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C560D76192FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_C560D761A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `fos_user_user` */

insert  into `fos_user_user`(`id`,`username`,`username_canonical`,`email`,`email_canonical`,`enabled`,`salt`,`password`,`last_login`,`locked`,`expired`,`expires_at`,`confirmation_token`,`password_requested_at`,`roles`,`credentials_expired`,`credentials_expire_at`,`created_at`,`updated_at`,`date_of_birth`,`firstname`,`lastname`,`website`,`biography`,`gender`,`locale`,`timezone`,`phone`,`facebook_uid`,`facebook_name`,`facebook_data`,`twitter_uid`,`twitter_name`,`twitter_data`,`gplus_uid`,`gplus_name`,`gplus_data`,`token`,`two_step_code`,`bakeFrequency`,`sex`,`bakeChoises`,`age`,`children`,`campaign`) values (1,'admin','admin','admin@domain.com','admin@domain.com',1,'llqyt3h3wgg84wggc448sowg8cwogcc','8mF44fHLL5pftN65HhsWrkzx3hgfbm53fjtt70Wj9Sg4Uo3ObSrV4IyG6CJgSrvSl/TinUpRJYAxujXA14MgQA==',NULL,0,0,NULL,NULL,NULL,'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}',0,NULL,'2015-05-14 15:04:10','2015-05-14 15:04:10',NULL,NULL,NULL,NULL,NULL,'u',NULL,NULL,NULL,NULL,NULL,'null',NULL,NULL,'null',NULL,NULL,'null',NULL,NULL,NULL,NULL,'N;',NULL,NULL,NULL);

/*Table structure for table `fos_user_user_group` */

DROP TABLE IF EXISTS `fos_user_user_group`;

CREATE TABLE `fos_user_user_group` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `IDX_B3C77447A76ED395` (`user_id`),
  KEY `IDX_B3C77447FE54D947` (`group_id`),
  CONSTRAINT `FK_B3C77447FE54D947` FOREIGN KEY (`group_id`) REFERENCES `fos_user_group` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_B3C77447A76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `fos_user_user_group` */

/*Table structure for table `maincontent_blocks` */

DROP TABLE IF EXISTS `maincontent_blocks`;

CREATE TABLE `maincontent_blocks` (
  `page_id` int(11) NOT NULL,
  `contentblock_id` int(11) NOT NULL,
  PRIMARY KEY (`page_id`,`contentblock_id`),
  KEY `IDX_BB2F1667C4663E4` (`page_id`),
  KEY `IDX_BB2F166742ADBAC2` (`contentblock_id`),
  CONSTRAINT `FK_BB2F166742ADBAC2` FOREIGN KEY (`contentblock_id`) REFERENCES `content_blocks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_BB2F1667C4663E4` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `maincontent_blocks` */

insert  into `maincontent_blocks`(`page_id`,`contentblock_id`) values (1,1),(5,4),(7,2),(7,3);

/*Table structure for table `media__gallery` */

DROP TABLE IF EXISTS `media__gallery`;

CREATE TABLE `media__gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `context` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `default_format` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `media__gallery` */

/*Table structure for table `media__gallery_media` */

DROP TABLE IF EXISTS `media__gallery_media`;

CREATE TABLE `media__gallery_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(11) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `position` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_80D4C5414E7AF8F` (`gallery_id`),
  KEY `IDX_80D4C541EA9FDD75` (`media_id`),
  CONSTRAINT `FK_80D4C541EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media__media` (`id`),
  CONSTRAINT `FK_80D4C5414E7AF8F` FOREIGN KEY (`gallery_id`) REFERENCES `media__gallery` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `media__gallery_media` */

/*Table structure for table `media__media` */

DROP TABLE IF EXISTS `media__media`;

CREATE TABLE `media__media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `enabled` tinyint(1) NOT NULL,
  `provider_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_status` int(11) NOT NULL,
  `provider_reference` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_metadata` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `length` decimal(10,0) DEFAULT NULL,
  `content_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content_size` int(11) DEFAULT NULL,
  `copyright` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `author_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `context` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cdn_is_flushable` tinyint(1) DEFAULT NULL,
  `cdn_flush_identifier` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cdn_flush_at` datetime DEFAULT NULL,
  `cdn_status` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5C6DD74E12469DE2` (`category_id`),
  CONSTRAINT `FK_5C6DD74E12469DE2` FOREIGN KEY (`category_id`) REFERENCES `classification__category` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `media__media` */

insert  into `media__media`(`id`,`category_id`,`name`,`description`,`enabled`,`provider_name`,`provider_status`,`provider_reference`,`provider_metadata`,`width`,`height`,`length`,`content_type`,`content_size`,`copyright`,`author_name`,`context`,`cdn_is_flushable`,`cdn_flush_identifier`,`cdn_flush_at`,`cdn_status`,`updated_at`,`created_at`) values (1,2,'sample_thumb.jpeg',NULL,0,'sonata.media.provider.image',1,'sample_thumb.jpeg','{\"filename\":\"sample_thumb.jpeg\"}',622,415,NULL,'image/jpeg',8043,NULL,NULL,'intro',NULL,NULL,NULL,NULL,'2015-05-14 15:04:11','2015-05-14 15:04:11'),(2,2,'sample_thumb.jpeg',NULL,0,'sonata.media.provider.image',1,'sample_thumb.jpeg','{\"filename\":\"sample_thumb.jpeg\"}',622,415,NULL,'image/jpeg',8043,NULL,NULL,'intro',NULL,NULL,NULL,NULL,'2015-05-14 15:04:11','2015-05-14 15:04:11'),(3,2,'sample_thumb.jpeg',NULL,0,'sonata.media.provider.image',1,'sample_thumb.jpeg','{\"filename\":\"sample_thumb.jpeg\"}',622,415,NULL,'image/jpeg',8043,NULL,NULL,'intro',NULL,NULL,NULL,NULL,'2015-05-14 15:04:11','2015-05-14 15:04:11'),(4,2,'sample_thumb.jpeg',NULL,0,'sonata.media.provider.image',1,'sample_thumb.jpeg','{\"filename\":\"sample_thumb.jpeg\"}',622,415,NULL,'image/jpeg',8043,NULL,NULL,'intro',NULL,NULL,NULL,NULL,'2015-05-14 15:04:11','2015-05-14 15:04:11'),(5,2,'sample_thumb.jpeg',NULL,0,'sonata.media.provider.image',1,'sample_thumb.jpeg','{\"filename\":\"sample_thumb.jpeg\"}',622,415,NULL,'image/jpeg',8043,NULL,NULL,'intro',NULL,NULL,NULL,NULL,'2015-05-14 15:04:11','2015-05-14 15:04:11'),(6,2,'sample_thumb.jpeg',NULL,0,'sonata.media.provider.image',1,'sample_thumb.jpeg','{\"filename\":\"sample_thumb.jpeg\"}',622,415,NULL,'image/jpeg',8043,NULL,NULL,'intro',NULL,NULL,NULL,NULL,'2015-05-14 15:04:11','2015-05-14 15:04:11'),(7,2,'sample_thumb.jpeg',NULL,0,'sonata.media.provider.image',1,'sample_thumb.jpeg','{\"filename\":\"sample_thumb.jpeg\"}',622,415,NULL,'image/jpeg',8043,NULL,NULL,'intro',NULL,NULL,NULL,NULL,'2015-05-14 15:04:11','2015-05-14 15:04:11'),(8,2,'sample_thumb.jpeg',NULL,0,'sonata.media.provider.image',1,'sample_thumb.jpeg','{\"filename\":\"sample_thumb.jpeg\"}',622,415,NULL,'image/jpeg',8043,NULL,NULL,'intro',NULL,NULL,NULL,NULL,'2015-05-14 15:04:11','2015-05-14 15:04:11'),(9,3,'sample_thumb.jpeg',NULL,0,'sonata.media.provider.image',1,'sample_thumb.jpeg','{\"filename\":\"sample_thumb.jpeg\"}',622,415,NULL,'image/jpeg',8043,NULL,NULL,'bgimage',NULL,NULL,NULL,NULL,'2015-05-14 15:04:11','2015-05-14 15:04:11'),(10,3,'sample_thumb.jpeg',NULL,0,'sonata.media.provider.image',1,'sample_thumb.jpeg','{\"filename\":\"sample_thumb.jpeg\"}',622,415,NULL,'image/jpeg',8043,NULL,NULL,'bgimage',NULL,NULL,NULL,NULL,'2015-05-14 15:04:11','2015-05-14 15:04:11');

/*Table structure for table `menu_items` */

DROP TABLE IF EXISTS `menu_items`;

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` int(11) DEFAULT NULL,
  `blog` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `menuType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `route` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `externalUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accessLevel` int(11) NOT NULL,
  `parent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `menuGroup` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `publishState` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `menuUrlExtras` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `menuImage` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_70B2CA2A9196AB0E` (`menuImage`),
  KEY `IDX_70B2CA2A140AB620` (`page`),
  KEY `IDX_70B2CA2AC0155143` (`blog`),
  CONSTRAINT `FK_70B2CA2A9196AB0E` FOREIGN KEY (`menuImage`) REFERENCES `media__media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_70B2CA2A140AB620` FOREIGN KEY (`page`) REFERENCES `pages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_70B2CA2AC0155143` FOREIGN KEY (`blog`) REFERENCES `blogs` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `menu_items` */

insert  into `menu_items`(`id`,`page`,`blog`,`title`,`menuType`,`route`,`externalUrl`,`accessLevel`,`parent`,`menuGroup`,`publishState`,`ordering`,`menuUrlExtras`,`menuImage`) values (1,1,NULL,'Homepage','Page','showPage',NULL,0,'0','Main Menu',1,0,NULL,NULL),(2,NULL,1,'Blog','Blog','showPage',NULL,0,'0','Main Menu',1,2,NULL,NULL),(3,NULL,3,'Events','Blog','showPage',NULL,0,'0','Main Menu',1,3,NULL,NULL),(4,NULL,2,'News','Blog','showPage',NULL,0,'0','Main Menu',1,4,NULL,NULL),(5,8,NULL,'Sports','Page','showPage',NULL,0,'0','Main Menu',1,5,NULL,NULL),(6,7,NULL,'E-Magazine','Page','showPage',NULL,0,'0','Main Menu',1,6,NULL,NULL),(7,5,NULL,'Contact Us','Page','showPage',NULL,0,'0','Main Menu',1,7,NULL,NULL),(8,3,NULL,'Sitemap','Page','showPage',NULL,0,'0','Footer Menu',1,0,NULL,NULL);

/*Table structure for table `modalcontent_blocks` */

DROP TABLE IF EXISTS `modalcontent_blocks`;

CREATE TABLE `modalcontent_blocks` (
  `page_id` int(11) NOT NULL,
  `contentblock_id` int(11) NOT NULL,
  PRIMARY KEY (`page_id`,`contentblock_id`),
  KEY `IDX_7074B397C4663E4` (`page_id`),
  KEY `IDX_7074B39742ADBAC2` (`contentblock_id`),
  CONSTRAINT `FK_7074B39742ADBAC2` FOREIGN KEY (`contentblock_id`) REFERENCES `content_blocks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_7074B397C4663E4` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `modalcontent_blocks` */

/*Table structure for table `pages` */

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` int(11) DEFAULT NULL,
  `introimage` int(11) DEFAULT NULL,
  `bgimage` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pageOrder` int(11) NOT NULL,
  `showPageTitle` int(11) NOT NULL,
  `publishState` int(11) NOT NULL,
  `pageclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `introtext` longtext COLLATE utf8_unicode_ci,
  `intromediasize` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `introclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pagetype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_last_modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2074E575E16C6B94` (`alias`),
  UNIQUE KEY `UNIQ_2074E575F3890D5F` (`introimage`),
  UNIQUE KEY `UNIQ_2074E57597AB4E12` (`bgimage`),
  KEY `IDX_2074E575BDAFD8C8` (`author`),
  CONSTRAINT `FK_2074E57597AB4E12` FOREIGN KEY (`bgimage`) REFERENCES `media__media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_2074E575BDAFD8C8` FOREIGN KEY (`author`) REFERENCES `fos_user_user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_2074E575F3890D5F` FOREIGN KEY (`introimage`) REFERENCES `media__media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `pages` */

insert  into `pages`(`id`,`author`,`introimage`,`bgimage`,`date`,`title`,`alias`,`pageOrder`,`showPageTitle`,`publishState`,`pageclass`,`description`,`keywords`,`introtext`,`intromediasize`,`introclass`,`pagetype`,`date_last_modified`) values (1,1,NULL,NULL,'2015-05-14','Home','index',99,1,1,NULL,NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.',NULL,NULL,'homepage','2015-05-14 15:04:11'),(2,1,NULL,NULL,'2015-05-14','404 Error Page','404',99,1,1,NULL,NULL,NULL,'',NULL,NULL,'404','2015-05-14 15:04:11'),(3,1,NULL,NULL,'2015-05-14','Sitemap','site-map',99,1,1,NULL,NULL,NULL,'',NULL,NULL,'sitemap','2015-05-14 15:04:11'),(4,1,NULL,NULL,'2015-05-14','Page Filtered Listing','tagged',99,1,1,NULL,NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.',NULL,NULL,'page_tag_list','2015-05-14 15:04:11'),(5,1,NULL,NULL,'2015-05-14','Contact Page','contact-page',99,1,1,NULL,NULL,NULL,'',NULL,NULL,'contact','2015-05-14 15:04:11'),(6,1,NULL,NULL,'2015-05-14','User Profile Page','user-profile',99,1,1,NULL,NULL,NULL,'',NULL,NULL,'user_profile','2015-05-14 15:04:11'),(7,1,1,NULL,'2015-05-14','Test Page 1','test-page-1',99,1,1,NULL,NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.',NULL,NULL,'one_columned','2015-05-14 15:04:11'),(8,1,2,NULL,'2015-05-14','Test Page 2','test-page-2',99,1,1,NULL,NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.',NULL,NULL,'two_columned','2015-05-14 15:04:11'),(9,1,3,NULL,'2015-05-14','Test Page 3','test-page-3',99,1,1,NULL,NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.',NULL,NULL,'three_columned','2015-05-14 15:04:11'),(10,1,4,NULL,'2015-05-14','Test Page 4','test-page-4',99,1,1,NULL,NULL,NULL,'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.',NULL,NULL,'one_columned','2015-05-14 15:04:11');

/*Table structure for table `pages_categories` */

DROP TABLE IF EXISTS `pages_categories`;

CREATE TABLE `pages_categories` (
  `page_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`page_id`,`category_id`),
  KEY `IDX_533F7E1BC4663E4` (`page_id`),
  KEY `IDX_533F7E1B12469DE2` (`category_id`),
  CONSTRAINT `FK_533F7E1B12469DE2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_533F7E1BC4663E4` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `pages_categories` */

insert  into `pages_categories`(`page_id`,`category_id`) values (1,1),(4,4),(7,1),(7,4),(8,1),(8,4),(9,1),(10,1);

/*Table structure for table `pages_tags` */

DROP TABLE IF EXISTS `pages_tags`;

CREATE TABLE `pages_tags` (
  `page_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`page_id`,`tag_id`),
  KEY `IDX_2476DEA6C4663E4` (`page_id`),
  KEY `IDX_2476DEA6BAD26311` (`tag_id`),
  CONSTRAINT `FK_2476DEA6BAD26311` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_2476DEA6C4663E4` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `pages_tags` */

insert  into `pages_tags`(`page_id`,`tag_id`) values (4,1),(7,1),(8,1),(9,1),(10,1);

/*Table structure for table `secondarycontent_blocks` */

DROP TABLE IF EXISTS `secondarycontent_blocks`;

CREATE TABLE `secondarycontent_blocks` (
  `page_id` int(11) NOT NULL,
  `contentblock_id` int(11) NOT NULL,
  PRIMARY KEY (`page_id`,`contentblock_id`),
  KEY `IDX_F8B56AB4C4663E4` (`page_id`),
  KEY `IDX_F8B56AB442ADBAC2` (`contentblock_id`),
  CONSTRAINT `FK_F8B56AB442ADBAC2` FOREIGN KEY (`contentblock_id`) REFERENCES `content_blocks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_F8B56AB4C4663E4` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `secondarycontent_blocks` */

/*Table structure for table `session` */

DROP TABLE IF EXISTS `session`;

CREATE TABLE `session` (
  `session_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `session_value` longblob NOT NULL,
  `session_time` int(11) NOT NULL,
  `sess_lifetime` int(11) NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `session` */

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metaDescription` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `metaKeywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fromTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `websiteTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `websiteTwitter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `websiteAuthor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `useWebsiteAuthor` tinyint(1) NOT NULL,
  `enableGoogleAnalytics` tinyint(1) NOT NULL,
  `googleAnalyticsId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailSender` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailRecepient` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `itemsPerPage` int(11) NOT NULL,
  `blogItemsPerPage` int(11) NOT NULL,
  `activateHttpCache` tinyint(1) NOT NULL,
  `activateSettings` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `settings` */

insert  into `settings`(`id`,`metaDescription`,`metaKeywords`,`fromTitle`,`websiteTitle`,`websiteTwitter`,`websiteAuthor`,`useWebsiteAuthor`,`enableGoogleAnalytics`,`googleAnalyticsId`,`emailSender`,`emailRecepient`,`itemsPerPage`,`blogItemsPerPage`,`activateHttpCache`,`activateSettings`) values (1,'Default Meta Description','Default Meta Keywords','Owner','Website Title',NULL,'Author',1,0,'UA-XXX-XXXXX','george@bardis.info','george@bardis.info',2,2,0,1);

/*Table structure for table `tags` */

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tagCategory` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_last_modified` datetime NOT NULL,
  `tagIcon` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_6FBC94268913051D` (`tagIcon`),
  CONSTRAINT `FK_6FBC94268913051D` FOREIGN KEY (`tagIcon`) REFERENCES `media__media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `tags` */

insert  into `tags`(`id`,`title`,`tagCategory`,`date_last_modified`,`tagIcon`) values (1,'Sample Tag 1',NULL,'2015-05-14 15:04:11',NULL),(2,'Sample Tag 2','blog','2015-05-14 15:04:11',NULL);

/*Table structure for table `timeline__action` */

DROP TABLE IF EXISTS `timeline__action`;

CREATE TABLE `timeline__action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `verb` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status_current` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status_wanted` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `duplicate_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `duplicate_priority` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `timeline__action` */

/*Table structure for table `timeline__action_component` */

DROP TABLE IF EXISTS `timeline__action_component`;

CREATE TABLE `timeline__action_component` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_id` int(11) DEFAULT NULL,
  `component_id` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6ACD1B169D32F035` (`action_id`),
  KEY `IDX_6ACD1B16E2ABAFFF` (`component_id`),
  CONSTRAINT `FK_6ACD1B16E2ABAFFF` FOREIGN KEY (`component_id`) REFERENCES `timeline__component` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_6ACD1B169D32F035` FOREIGN KEY (`action_id`) REFERENCES `timeline__action` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `timeline__action_component` */

/*Table structure for table `timeline__component` */

DROP TABLE IF EXISTS `timeline__component`;

CREATE TABLE `timeline__component` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `identifier` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1B2F01CDD1B862B8` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `timeline__component` */

/*Table structure for table `timeline__timeline` */

DROP TABLE IF EXISTS `timeline__timeline`;

CREATE TABLE `timeline__timeline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `context` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FFBC6AD59D32F035` (`action_id`),
  KEY `IDX_FFBC6AD523EDC87` (`subject_id`),
  CONSTRAINT `FK_FFBC6AD523EDC87` FOREIGN KEY (`subject_id`) REFERENCES `timeline__component` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_FFBC6AD59D32F035` FOREIGN KEY (`action_id`) REFERENCES `timeline__action` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `timeline__timeline` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
