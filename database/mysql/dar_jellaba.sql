-- Dar Jellaba : base de données MySQL (structure + données de base + démo)
-- Compte admin : admin@darjellaba.ma / ChangeMe-2026!   (à changer !)
-- Import :  mysql -u root < database/mysql/dar_jellaba.sql

CREATE DATABASE IF NOT EXISTS `darjellaba` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `darjellaba`;


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
DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `topic` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `title_ar` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_fr` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `excerpt_ar` text COLLATE utf8mb4_unicode_ci,
  `excerpt_fr` text COLLATE utf8mb4_unicode_ci,
  `body_ar` longtext COLLATE utf8mb4_unicode_ci,
  `body_fr` longtext COLLATE utf8mb4_unicode_ci,
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,'la-djellaba-marocaine-un-vetement-qui-se-transmet','craft','الجلابة المغربية: لباس يتوارثه المغاربة','La djellaba marocaine : un vêtement qui se transmet','من الجبة الصوفية البسيطة إلى الجلابة الراقية بالسفيفة، حكاية لباس رافق المغاربة في اليومي والمناسبات.','De la tenue en laine simple à la djellaba raffinée ornée de sfifa, histoire d\'un vêtement du quotidien et des grandes occasions.','<p>الجلابة لباس طويل فضفاض بقب (غطاء رأس مدبب)، وهي من أكثر الألبسة التقليدية حضورا فالمغرب، رجالا ونساء. كتلبس فالجمعة والأعياد والمناسبات العائلية، وكتخدم بأثواب مختلفة حسب الفصل: الصوف للبرد، والقطن والكريب للأيام المعتدلة.</p><h2>اختلاف الأذواق من مدينة لمدينة</h2><p>كل منطقة عندها لمستها: من الجلابة البيضاء البسيطة، للمخططة بالألوان، لجلابة النساء المطرزة بالسفيفة والعقاد. وتبقى القصة الجيدة والثوب المريح هما سر جلابة تعيش سنين.</p><h2>كيفاش تختار جلابتك</h2><ul><li>حدد المناسبة: يومي، جمعة، عيد أو عرس.</li><li>اختار الثوب حسب الفصل.</li><li>شوف نوع الخياطة والتزيين: سفيفة، عقاد أو طرز يدوي.</li><li>تأكد من المقاس عبر <a href=\"/size-guide\">جدول المقاسات</a>.</li></ul>','<p>La djellaba est un vêtement long et ample à capuche pointue, porté par les hommes comme par les femmes pour la prière du vendredi, les fêtes et les réunions de famille. Laine pour l\'hiver, coton ou crêpe pour la mi-saison.</p><h2>Comment choisir</h2><ul><li>Définissez l\'occasion.</li><li>Choisissez le tissu selon la saison.</li><li>Regardez la finition : sfifa, aqqad ou broderie main.</li><li>Vérifiez votre taille avec le guide des tailles.</li></ul>',NULL,1,'2026-11-14 10:38:04','2026-11-14 10:38:04','2026-11-14 10:38:04'),(2,'le-caftan-marocain-dans-les-mariages','wedding','القفطان المغربي في الأعراس','Le caftan marocain dans les mariages','القفطان والتكشيطة حاضرين فكل عرس مغربي: ألوان، أثواب فاخرة وتفاصيل يدوية.','Caftan et takchita sont de tous les mariages marocains : couleurs, tissus nobles et détails faits main.','<p>فالعرس المغربي كتبدل العروسة والضيوف أكثر من لبسة: قفطان، تكشيطة (قطعتين أو أكثر)، وأحيانا الجلابة. الأثواب الفاخرة بحال المخمل والساتان والأورجانزا مع الترصيع والطرز اليدوي كتعطي للمناسبة بريقها.</p><h2>لباس العريس</h2><p>العريس كيختار غالبا جلابة أنيقة بيضاء أو بلون هادئ، مع البلغة والطربوش، أو دراعية فاخرة لليلة الحناء.</p><h2>نصائح</h2><ul><li>اختار لون يناسب تفاصيل العرس والإضاءة.</li><li>جرب القفطان قبل المناسبة بوقت كافي.</li><li>إلا بغيتي قياس خاص، سول على التفصال.</li></ul>','<p>Lors d\'un mariage marocain, la mariée et les invités portent souvent plusieurs tenues : caftan, takchita (deux pièces ou plus), parfois la djellaba. Velours, satin et organza, rehaussés de perlage et de broderie main, donnent tout leur éclat à la fête.</p><h2>La tenue du marié</h2><p>Djellaba élégante blanche ou sobre, babouches et tarbouche, ou daraa raffinée pour la soirée du henné.</p>',NULL,1,'2026-11-13 10:38:04','2026-11-14 10:38:04','2026-11-14 10:38:04'),(3,'sfifa-et-aqqad-lart-de-la-passementerie','craft','السفيفة والعقاد: فن التزيين بالخيوط','Sfifa et aqqad : l\'art de la passementerie','التزيين اللي كيعطي للجلابة والقفطان هويتهم: خيوط مجدولة وعقد صغيرة بيد الحرفي.','La passementerie qui donne leur identité à la djellaba et au caftan : cordons tressés et petits boutons noués à la main.','<p><strong>السفيفة</strong> شريط مزخرف من الخيوط المجدولة كيتحط على حواف الجلابة والقفطان: على الصدر والأكمام والقب. و<strong>العقاد</strong> هي العقد الصغيرة المصنوعة بالخيط واللي كتعوض الأزرار وكتزين الفتحة الأمامية.</p><h2>عمل يدوي دقيق</h2><p>الحرفي كيصنع الخيوط ويجدلها ويخيطها بعناية، وكل قطعة كتاخد وقت. علاش القطع المزينة بالسفيفة والعقاد كتكون أغلى وأخلد.</p><h2>كيفاش تعتني بها</h2><ul><li>الغسيل بالماء الفاتر أو التنظيف الجاف.</li><li>تفادى الفرك القوي على التطريز.</li><li>علق الجلابة بدل ما تطويها بزاف.</li></ul>','<p>La <strong>sfifa</strong> est un galon décoratif de fils tressés posé sur les bords du vêtement ; l\'<strong>aqqad</strong> désigne les petits nœuds qui remplacent les boutons. Un travail minutieux d\'artisan, qui fait toute la valeur de la pièce.</p>',NULL,1,'2026-11-12 10:38:04','2026-11-14 10:38:04','2026-11-14 10:38:04'),(4,'la-soiree-du-henne-traditions-et-couleurs','henna','ليلة الحناء: عادات وألوان','La soirée du henné : traditions et couleurs','ليلة مليئة بالفرح والأغاني والألوان الزاهية، ولباسها كيعكس الفرح.','Une soirée de joie, de chants et de couleurs vives, où le vêtement reflète la fête.','<p>ليلة الحناء من أجمل لحظات العرس المغربي: كتجتمع العائلة والصديقات، وكتتزين العروسة بالحناء. اللباس فهاد الليلة كيكون عادة بألوان زاهية: الفوشيا، الأخضر، الأزرق الملكي والذهبي.</p><h2>لبسة الحناء</h2><p>القفطان الخفيف بالأورجانزا أو الساتان خيار مفضل، وكيتكمل بالحلي التقليدية. بالنسبة للرجال، دراعية أو جلابة بلون دافئ.</p>','<p>La soirée du henné réunit la famille et les amies autour de la mariée. On y porte des couleurs vives : fuchsia, vert, bleu roi et doré ; caftan léger en organza ou satin pour elle, daraa ou djellaba chaude pour lui.</p>',NULL,1,'2026-11-11 10:38:04','2026-11-14 10:38:04','2026-11-14 10:38:04'),(5,'tenue-de-laid-le-plaisir-des-habits-neufs','eid','لباس العيد: فرحة الكسوة الجديدة','Tenue de l\'Aïd : le plaisir des habits neufs','من العادات الجميلة أن نلبسو الجديد فالعيد، وخصوصا الأطفال.','Étrenner de nouveaux habits à l\'Aïd est une belle coutume, surtout pour les enfants.','<p>فالعيد كيحرص المغاربة على لبس الجديد: جلابة للأب والولد، قفطان أو جلابة للأم والبنت. الصباح كتبدا بالصلاة وكتتكمل بالزيارات العائلية، واللباس التقليدي كيزيد الفرحة.</p><h2>أفكار للعائلة</h2><ul><li>جلابات بألوان منسجمة للأسرة كلها.</li><li>طقم جابادور للولد وقفطان للبنت.</li><li>بلغة جديدة لكل واحد.</li></ul>','<p>À l\'Aïd, on étrenne des habits neufs : djellaba pour le père et le fils, caftan ou djellaba pour la mère et la fille. Une journée qui commence par la prière et se poursuit en visites familiales.</p>',NULL,1,'2026-11-10 10:38:04','2026-11-14 10:38:04','2026-11-14 10:38:04'),(6,'lelegance-du-nord-tetouan-et-chefchaouen','north','أناقة الشمال: تطوان وشفشاون','L\'élégance du Nord : Tétouan et Chefchaouen','الشمال المغربي ورث من الأندلس ذوقا رفيعا فاللباس والخياطة.','Le Nord du Maroc a hérité de l\'Andalousie un goût raffiné pour le vêtement et la couture.','<p>تطوان، المعروفة بـ\"الحمامة البيضاء\"، وشفشاون بأزقتها الزرقاء، من المدن اللي حافظات على تراث غني فالخياطة والتطريز. للمنطقة طابعها فالألوان والتفاصيل، وكتعتز بحرفييها.</p><p>فدار الجلابة كنحاولو نكرمو هاد الذوق الشمالي بتشكيلة فيها الجلابات والقفاطن والبلغة لكل المناسبات.</p>','<p>Tétouan, la « Colombe blanche », et Chefchaouen, ses ruelles bleues, ont préservé un riche patrimoine de couture et de broderie. Chez Dar Jellaba, nous rendons hommage à ce goût du Nord.</p>',NULL,1,'2026-11-09 10:38:04','2026-11-14 10:38:04','2026-11-14 10:38:04'),(7,'ramadan-la-djellaba-et-les-rituels','ramadan','رمضان: الجلابة والطقوس','Ramadan : la djellaba et les rituels','جلابة مريحة وأنيقة لأيام وليالي رمضان.','Une djellaba confortable et élégante pour les jours et les nuits de Ramadan.','<p>فرمضان كتولي الجلابة اللباس المفضل للتراويح والزيارات والسهرات العائلية. الأثواب الخفيفة بحال الكريب والقطن كتعطي راحة، والألوان الهادئة كتناسب الأجواء الروحانية.</p>','<p>Pendant Ramadan, la djellaba devient la tenue idéale pour les tarawih, les visites et les soirées en famille. Tissus légers et couleurs douces conviennent à l\'ambiance spirituelle du mois.</p>',NULL,1,'2026-11-08 10:38:04','2026-11-14 10:38:04','2026-11-14 10:38:04');
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `segment` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_fr` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `accessory` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_segment_slug_unique` (`segment`,`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'men','jellaba','جلابة','Djellaba',0,0,1),(2,'men','jabador','جابادور','Jabador',0,1,1),(3,'men','daraa','دراعية','Daraa',0,2,1),(4,'men','caftan','قفطان','Caftan',0,3,1),(5,'men','selham','سلهام','Selham',0,4,1),(6,'men','burnous','برنوس','Burnous',0,5,1),(7,'men','qachaba','قشابة','Qachabia',0,6,1),(8,'men','tarbouch','طربوش','Tarbouche',1,7,1),(9,'men','belgha','بلغة','Babouches',1,8,1),(10,'women','jellaba','جلابة','Djellaba',0,0,1),(11,'women','caftan','قفطان','Caftan',0,1,1),(12,'women','takchita','تكشيطة','Takchita',0,2,1),(13,'women','belgha','بلغة','Babouches',1,3,1),(14,'women','accessoires','إكسسوارات','Accessoires',1,4,1),(15,'boys','jellaba','جلابة','Djellaba',0,0,1),(16,'boys','jabador','جابادور','Jabador',0,1,1),(17,'boys','caftan','قفطان','Caftan',0,2,1),(18,'boys','set','طقم','Ensemble',0,3,1),(19,'boys','belgha','بلغة','Babouches',1,4,1),(20,'girls','jellaba','جلابة','Djellaba',0,0,1),(21,'girls','caftan','قفطان','Caftan',0,1,1),(22,'girls','set','طقم','Ensemble',0,2,1),(23,'girls','belgha','بلغة','Babouches',1,3,1);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_11_07_100000_create_shop_tables',1),(5,'2026_11_07_110000_create_orders_and_articles',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `variant_id` bigint unsigned DEFAULT NULL,
  `name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `made_to_measure` tinyint(1) NOT NULL DEFAULT '0',
  `price` int unsigned NOT NULL,
  `qty` int unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_variant_id_foreign` (`variant_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ref` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `subtotal` int unsigned NOT NULL,
  `shipping` int unsigned NOT NULL DEFAULT '0',
  `total` int unsigned NOT NULL,
  `locale` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ar',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_ref_unique` (`ref`),
  KEY `orders_status_created_at_index` (`status`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `path` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'products/demo/men-white-classic.svg',1),(2,2,'products/demo/men-striped-olive.svg',1),(3,3,'products/demo/men-black-tone.svg',1),(4,11,'products/demo/women-caramel-blue.svg',1),(5,12,'products/demo/women-pink-sfifa.svg',1);
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
DROP TABLE IF EXISTS `product_term`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_term` (
  `product_id` bigint unsigned NOT NULL,
  `term_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`term_id`),
  KEY `product_term_term_id_foreign` (`term_id`),
  CONSTRAINT `product_term_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_term_term_id_foreign` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `product_term` DISABLE KEYS */;
INSERT INTO `product_term` VALUES (1,42),(3,42),(5,42),(6,42),(8,42),(9,42),(13,42),(15,42),(16,42),(5,43),(13,43),(14,43),(6,44),(14,44),(1,45),(4,45),(9,45),(11,45),(12,45),(17,45),(19,45),(2,46),(18,46),(11,47),(20,47),(19,48),(17,49),(18,49),(1,50),(3,50),(8,50),(10,50),(2,51),(4,51),(7,51),(10,51),(12,51),(16,51),(20,51);
/*!40000 ALTER TABLE `product_term` ENABLE KEYS */;
DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segment` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `name_ar` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_fr` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `desc_ar` text COLLATE utf8mb4_unicode_ci,
  `desc_fr` text COLLATE utf8mb4_unicode_ci,
  `price` int unsigned NOT NULL,
  `old_price` int unsigned DEFAULT NULL,
  `fabric_id` bigint unsigned DEFAULT NULL,
  `stitch_id` bigint unsigned DEFAULT NULL,
  `made_to_measure` tinyint(1) NOT NULL DEFAULT '0',
  `badge` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `views` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_fabric_id_foreign` (`fabric_id`),
  KEY `products_stitch_id_foreign` (`stitch_id`),
  KEY `products_active_segment_category_id_index` (`active`,`segment`,`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `products_fabric_id_foreign` FOREIGN KEY (`fabric_id`) REFERENCES `terms` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_stitch_id_foreign` FOREIGN KEY (`stitch_id`) REFERENCES `terms` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'djellaba-homme-blanche-sfifa','men',1,'جلابة رجالية بيضاء بالسفيفة','Djellaba homme blanche sfifa','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',690,NULL,26,36,0,'new',1,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(2,'djellaba-homme-rayee-vert-et-noir','men',1,'جلابة رجالية مخططة أخضر وأسود','Djellaba homme rayée vert et noir','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',790,890,31,37,0,'sale',1,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(3,'djellaba-homme-noire-elegante','men',1,'جلابة رجالية سوداء راقية','Djellaba homme noire élégante','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',850,NULL,29,38,1,NULL,1,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(4,'jabador-homme-bleu-roi','men',2,'جابادور رجالي أزرق ملكي','Jabador homme bleu roi','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',620,NULL,30,41,0,NULL,0,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(5,'daraa-homme-doree-mariage','men',3,'دراعية رجالية ذهبية للعرس','Daraa homme dorée mariage','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',1450,NULL,28,39,1,'new',1,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(6,'caftan-homme-bordeaux','men',4,'قفطان رجالي نبيذي','Caftan homme bordeaux','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',1100,1300,32,37,1,'sale',0,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(7,'selham-laine-gris','men',5,'سلهام صوف رمادي','Selham laine gris','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',1250,NULL,29,41,0,NULL,0,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(8,'burnous-blanc','men',6,'برنوس أبيض','Burnous blanc','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',1600,NULL,29,41,0,NULL,0,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(9,'tarbouche-marocain-rouge','men',8,'طربوش مغربي أحمر','Tarbouche marocain rouge','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',90,NULL,29,41,0,NULL,0,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(10,'babouches-cuir-jaune','men',9,'بلغة جلد أصفر','Babouches cuir jaune','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',160,NULL,30,41,0,NULL,0,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(11,'djellaba-femme-caramel-sfifa-bleue','women',10,'جلابة نسائية بنية بالسفيفة الزرقاء','Djellaba femme caramel sfifa bleue','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',890,NULL,26,36,0,'new',1,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(12,'djellaba-femme-rose-sfifa-grise','women',10,'جلابة نسائية وردية بسفيفة رمادية','Djellaba femme rose sfifa grise','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',820,950,26,36,0,'sale',1,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(13,'caftan-femme-emeraude','women',11,'قفطان نسائي زمردي','Caftan femme émeraude','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',1890,NULL,27,40,1,'new',1,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(14,'caftan-femme-fuchsia-henne','women',11,'قفطان نسائي فوشيا للحناء','Caftan femme fuchsia henné','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',1650,NULL,34,39,1,NULL,1,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(15,'takchita-mariee-doree','women',12,'تكشيطة عروس ذهبية','Takchita mariée dorée','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',3900,NULL,32,40,1,NULL,1,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(16,'babouches-femme-brodees','women',13,'بلغة نسائية مطرزة','Babouches femme brodées','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',210,NULL,32,39,0,NULL,0,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(17,'djellaba-garcon-blanche','boys',15,'جلابة ولد أبيض','Djellaba garçon blanche','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',390,NULL,30,36,0,'new',1,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(18,'ensemble-jabador-garcon-circoncision','boys',18,'طقم جابادور ولد للختان','Ensemble jabador garçon circoncision','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',520,NULL,27,39,0,NULL,0,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(19,'caftan-fille-rose','girls',21,'قفطان بنت وردي','Caftan fille rose','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',450,NULL,27,40,0,'new',1,1,0,'2026-11-14 10:38:04','2026-11-14 10:38:04'),(20,'djellaba-fille-turquoise','girls',20,'جلابة بنت تركوازية','Djellaba fille turquoise','منتج تجريبي: استبدله بوصفك الخاص من لوحة التحكم.','Produit de démonstration : remplacez ce texte depuis l\'administration.',420,520,26,36,0,'sale',0,1,0,'2026-11-14 10:38:05','2026-11-14 10:38:05');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `key` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('about_ar','دار الجلابة متجر للباس المغربي التقليدي الجاهز. نختار بعناية الثوب والخياطة (السفيفة والعقاد) لنقدم لكم لباسا يليق بمناسباتكم.'),('about_fr','Dar Jellaba est une boutique de vêtements traditionnels marocains prêts à porter. Nous choisissons avec soin les tissus et les finitions (sfifa, aqqad).'),('announce_ar','التوصيل لجميع مدن المغرب · الأداء عند الاستلام · تبديل مجاني'),('announce_fr','Livraison partout au Maroc · Paiement à la livraison · Échange gratuit'),('delivery_ar','التوصيل لجميع المدن خلال 24 إلى 72 ساعة.'),('delivery_fr','Livraison dans toutes les villes sous 24 à 72 h.'),('hero_text_ar','جلابات وقفاطن جاهزة للرجال والنساء والأطفال، لكل مناسبة: العرس، الحنّاء، الأعياد ورمضان.'),('hero_text_fr','Djellabas et caftans prêts à porter pour hommes, femmes et enfants, pour chaque occasion : mariage, henné, fêtes et Ramadan.'),('hero_title_ar','أناقة مغربية أصيلة'),('hero_title_fr','L\'élégance marocaine authentique'),('site_name_ar','دار الجلابة'),('site_name_fr','Dar Jellaba'),('tagline_ar','لباس تقليدي مغربي للرجال والنساء والأطفال'),('tagline_fr','Tenues traditionnelles marocaines pour hommes, femmes et enfants');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
DROP TABLE IF EXISTS `terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `terms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_fr` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `hex` varchar(9) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort` int unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `terms_type_slug_unique` (`type`,`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `terms` DISABLE KEYS */;
INSERT INTO `terms` VALUES (1,'color','white','أبيض','Blanc','#F5F2EA',0,1),(2,'color','cream','كريمي / بيج','Crème / Beige','#E8DCC2',1,1),(3,'color','camel','جملي','Camel','#C19A6B',2,1),(4,'color','caramel','كراميل','Caramel','#B5703A',3,1),(5,'color','brown','بني / قهوة','Marron','#6B4226',4,1),(6,'color','cinnamon','قرفة','Cannelle','#B5532F',5,1),(7,'color','orange','برتقالي','Orange','#E07B39',6,1),(8,'color','mustard','خردلي','Moutarde','#D4A72C',7,1),(9,'color','gold','ذهبي','Doré','#C8A24A',8,1),(10,'color','olive','أخضر زيتي','Vert olive','#6B7A3A',9,1),(11,'color','emerald','أخضر زمردي','Vert émeraude','#0F7B5F',10,1),(12,'color','mint','أخضر نعناعي','Menthe','#9CC9A8',11,1),(13,'color','turquoise','تركوازي','Turquoise','#1FA3A3',12,1),(14,'color','sky','أزرق سماوي','Bleu ciel','#7FB2E5',13,1),(15,'color','royal','أزرق ملكي','Bleu roi','#2347A8',14,1),(16,'color','indigo','نيلي','Indigo','#1F3A5F',15,1),(17,'color','purple','بنفسجي','Violet','#6B3FA0',16,1),(18,'color','lilac','ليلكي','Lilas','#B79AD6',17,1),(19,'color','pink','وردي','Rose','#E8A0B4',18,1),(20,'color','fuchsia','فوشيا','Fuchsia','#C2185B',19,1),(21,'color','red','أحمر','Rouge','#B3202A',20,1),(22,'color','burgundy','نبيذي','Bordeaux','#6D1A2B',21,1),(23,'color','grey','رمادي','Gris','#9A9A9A',22,1),(24,'color','silver','فضي','Argent','#C0C0C0',23,1),(25,'color','black','أسود','Noir','#1B1B1B',24,1),(26,'fabric','crepe','كريب','Crêpe',NULL,0,1),(27,'fabric','satin','ساتان','Satin',NULL,1,1),(28,'fabric','silk','حرير','Soie',NULL,2,1),(29,'fabric','wool','صوف','Laine',NULL,3,1),(30,'fabric','cotton','قطن','Coton',NULL,4,1),(31,'fabric','twill','تويل','Twill',NULL,5,1),(32,'fabric','velvet','مخمل','Velours',NULL,6,1),(33,'fabric','linen','كتان','Lin',NULL,7,1),(34,'fabric','organza','أورجانزا','Organza',NULL,8,1),(35,'fabric','mlf','ملف','Mlf',NULL,9,1),(36,'stitch','sfifa','سفيفة','Sfifa',NULL,0,1),(37,'stitch','aqqad','عقاد','Aqqad',NULL,1,1),(38,'stitch','maamal','معمّل','Ma\'mel',NULL,2,1),(39,'stitch','handmade','طرز يدوي','Broderie main',NULL,3,1),(40,'stitch','beads','ترصيع','Perlage',NULL,4,1),(41,'stitch','plain','سادة (بدون تطريز)','Uni (sans broderie)',NULL,5,1),(42,'occasion','wedding','عرس','Mariage',NULL,0,1),(43,'occasion','henna','حنّاء','Henné',NULL,1,1),(44,'occasion','engagement','خطوبة','Fiançailles',NULL,2,1),(45,'occasion','eid-fitr','عيد الفطر','Aïd el-Fitr',NULL,3,1),(46,'occasion','eid-adha','عيد الأضحى','Aïd el-Adha',NULL,4,1),(47,'occasion','ramadan','رمضان','Ramadan',NULL,5,1),(48,'occasion','aqiqa','عقيقة','Aqiqa',NULL,6,1),(49,'occasion','circumcision','ختان','Circoncision',NULL,7,1),(50,'occasion','friday','الجمعة','Vendredi',NULL,8,1),(51,'occasion','daily','يومي','Quotidien',NULL,9,1);
/*!40000 ALTER TABLE `terms` ENABLE KEYS */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@darjellaba.ma',NULL,'$2y$12$wwM/IkIxbfFL8v4qeyaXmePSrDVYQWwfdxhwCE9B18zhQn7BItA3G',NULL,'2026-11-14 10:38:04','2026-11-14 10:38:04');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
DROP TABLE IF EXISTS `variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `variants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `color_id` bigint unsigned DEFAULT NULL,
  `size` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `stock` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `variants_product_id_foreign` (`product_id`),
  KEY `variants_color_id_foreign` (`color_id`),
  CONSTRAINT `variants_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `terms` (`id`) ON DELETE SET NULL,
  CONSTRAINT `variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=211 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `variants` DISABLE KEYS */;
INSERT INTO `variants` VALUES (1,1,1,'M',6),(2,1,1,'L',4),(3,1,1,'XL',6),(4,1,1,'XXL',4),(5,1,2,'M',1),(6,1,2,'L',0),(7,1,2,'XL',1),(8,1,2,'XXL',3),(9,2,10,'M',3),(10,2,10,'L',2),(11,2,10,'XL',2),(12,2,10,'XXL',2),(13,2,25,'M',2),(14,2,25,'L',0),(15,2,25,'XL',6),(16,2,25,'XXL',4),(17,3,25,'M',2),(18,3,25,'L',3),(19,3,25,'XL',3),(20,3,25,'XXL',5),(21,3,16,'M',6),(22,3,16,'L',4),(23,3,16,'XL',4),(24,3,16,'XXL',6),(25,4,15,'M',4),(26,4,15,'L',6),(27,4,15,'XL',5),(28,4,15,'XXL',4),(29,4,16,'M',4),(30,4,16,'L',2),(31,4,16,'XL',4),(32,4,16,'XXL',6),(33,4,14,'M',4),(34,4,14,'L',4),(35,4,14,'XL',5),(36,4,14,'XXL',3),(37,5,9,'M',6),(38,5,9,'L',5),(39,5,9,'XL',0),(40,5,9,'XXL',3),(41,5,2,'M',6),(42,5,2,'L',2),(43,5,2,'XL',4),(44,5,2,'XXL',2),(45,6,22,'M',3),(46,6,22,'L',1),(47,6,22,'XL',1),(48,6,22,'XXL',6),(49,6,11,'M',1),(50,6,11,'L',5),(51,6,11,'XL',5),(52,6,11,'XXL',3),(53,7,23,'M',6),(54,7,23,'L',1),(55,7,23,'XL',6),(56,7,23,'XXL',5),(57,7,5,'M',0),(58,7,5,'L',2),(59,7,5,'XL',2),(60,7,5,'XXL',2),(61,7,25,'M',2),(62,7,25,'L',2),(63,7,25,'XL',2),(64,7,25,'XXL',3),(65,8,1,'M',2),(66,8,1,'L',5),(67,8,1,'XL',6),(68,8,1,'XXL',6),(69,8,3,'M',4),(70,8,3,'L',5),(71,8,3,'XL',1),(72,8,3,'XXL',5),(73,9,21,'M',6),(74,9,21,'L',2),(75,9,21,'XL',4),(76,9,21,'XXL',1),(77,10,8,'M',3),(78,10,8,'L',6),(79,10,8,'XL',0),(80,10,8,'XXL',3),(81,10,1,'M',2),(82,10,1,'L',0),(83,10,1,'XL',1),(84,10,1,'XXL',5),(85,10,2,'M',0),(86,10,2,'L',6),(87,10,2,'XL',1),(88,10,2,'XXL',6),(89,11,4,'S',5),(90,11,4,'M',1),(91,11,4,'L',5),(92,11,4,'XL',0),(93,11,6,'S',0),(94,11,6,'M',3),(95,11,6,'L',6),(96,11,6,'XL',6),(97,11,19,'S',0),(98,11,19,'M',0),(99,11,19,'L',6),(100,11,19,'XL',0),(101,12,19,'S',5),(102,12,19,'M',2),(103,12,19,'L',2),(104,12,19,'XL',5),(105,12,18,'S',0),(106,12,18,'M',4),(107,12,18,'L',6),(108,12,18,'XL',0),(109,12,2,'S',2),(110,12,2,'M',5),(111,12,2,'L',3),(112,12,2,'XL',1),(113,13,11,'S',1),(114,13,11,'M',4),(115,13,11,'L',3),(116,13,11,'XL',3),(117,13,15,'S',6),(118,13,15,'M',0),(119,13,15,'L',0),(120,13,15,'XL',4),(121,13,22,'S',5),(122,13,22,'M',0),(123,13,22,'L',5),(124,13,22,'XL',5),(125,14,20,'S',6),(126,14,20,'M',6),(127,14,20,'L',2),(128,14,20,'XL',0),(129,14,7,'S',6),(130,14,7,'M',5),(131,14,7,'L',3),(132,14,7,'XL',5),(133,14,9,'S',2),(134,14,9,'M',2),(135,14,9,'L',2),(136,14,9,'XL',4),(137,15,9,'S',5),(138,15,9,'M',0),(139,15,9,'L',5),(140,15,9,'XL',0),(141,15,1,'S',4),(142,15,1,'M',5),(143,15,1,'L',6),(144,15,1,'XL',0),(145,15,24,'S',3),(146,15,24,'M',5),(147,15,24,'L',1),(148,15,24,'XL',1),(149,16,9,'S',2),(150,16,9,'M',3),(151,16,9,'L',4),(152,16,9,'XL',4),(153,16,19,'S',0),(154,16,19,'M',4),(155,16,19,'L',4),(156,16,19,'XL',5),(157,16,13,'S',1),(158,16,13,'M',2),(159,16,13,'L',6),(160,16,13,'XL',3),(161,17,1,'4',5),(162,17,1,'6',0),(163,17,1,'8',0),(164,17,1,'10',2),(165,17,1,'12',2),(166,17,14,'4',5),(167,17,14,'6',2),(168,17,14,'8',1),(169,17,14,'10',5),(170,17,14,'12',4),(171,18,15,'4',0),(172,18,15,'6',3),(173,18,15,'8',0),(174,18,15,'10',1),(175,18,15,'12',4),(176,18,1,'4',1),(177,18,1,'6',6),(178,18,1,'8',2),(179,18,1,'10',2),(180,18,1,'12',3),(181,18,9,'4',3),(182,18,9,'6',0),(183,18,9,'8',5),(184,18,9,'10',1),(185,18,9,'12',1),(186,19,19,'4',4),(187,19,19,'6',0),(188,19,19,'8',1),(189,19,19,'10',3),(190,19,19,'12',0),(191,19,18,'4',3),(192,19,18,'6',6),(193,19,18,'8',2),(194,19,18,'10',6),(195,19,18,'12',3),(196,19,12,'4',4),(197,19,12,'6',2),(198,19,12,'8',4),(199,19,12,'10',3),(200,19,12,'12',5),(201,20,13,'4',0),(202,20,13,'6',2),(203,20,13,'8',3),(204,20,13,'10',2),(205,20,13,'12',6),(206,20,19,'4',4),(207,20,19,'6',3),(208,20,19,'8',4),(209,20,19,'10',1),(210,20,19,'12',5);
/*!40000 ALTER TABLE `variants` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

