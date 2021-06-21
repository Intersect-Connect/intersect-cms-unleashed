-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 01 juin 2021 à 12:00
-- Version du serveur :  5.7.24
-- Version de PHP : 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `otda`
--

-- --------------------------------------------------------

--
-- Structure de la table `cms_news`
--

CREATE TABLE `cms_news` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `date` date NOT NULL,
  `author` text NOT NULL,
  `img_url` text NOT NULL,
  `slug` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

ALTER TABLE `cms_news` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;


--
-- Déchargement des données de la table `cms_news`
--

INSERT INTO `cms_news` (`id`, `title`, `content`, `date`, `author`, `img_url`, `slug`) VALUES
(1, 'Welcome on IntersectCMS', '<p><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <iframe src=\"https://www.youtube.com/embed/KgFv33OtISM?ab_channel=Maverick\" width=\"560\" height=\"314\" allowfullscreen=\"allowfullscreen\"></iframe></strong></p>\r\n<p>&nbsp;</p>\r\n<p><strong>Lorem ipsum dolor sit amet</strong>, consectetur adipiscing elit. Mauris non nulla vitae erat finibus molestie. Vestibulum sit amet turpis quam. Praesent scelerisque sem ipsum, at hendrerit nisl dapibus a. Quisque vulputate sem eu turpis fringilla pharetra. Aenean consectetur sodales leo, at mattis metus dictum quis. Morbi consequat nunc ultricies purus tincidunt, quis ultrices mi tincidunt. Nullam maximus semper arcu, eu mattis enim aliquet eget.</p>\r\n<p>Integer sem tortor, ornare sit amet dui quis, volutpat tristique velit. Nam blandit feugiat nulla a iaculis. Fusce sagittis vel orci eget tempor. Vestibulum ut nunc mattis, ullamcorper velit at, convallis neque. Maecenas sagittis, sem at hendrerit imperdiet, odio urna egestas leo, placerat fermentum sem erat scelerisque purus. Phasellus ultricies est sit amet enim imperdiet, sed tristique nibh porttitor. Nulla molestie libero ac felis accumsan, ac mattis ex venenatis. Nam at lectus tempus, lobortis urna eget, viverra leo. Ut semper et eros eu semper. Sed mattis, magna id vehicula aliquet, dui quam eleifend orci, at molestie justo metus vel enim. Sed vitae urna leo. Nullam dapibus nisl dapibus venenatis tristique. Praesent vel turpis iaculis, laoreet erat non, fringilla augue. In tincidunt ipsum nec ligula lobortis, vitae tempus tortor porta. Aenean gravida eget nibh eget hendrerit. Vivamus eu metus porttitor, mattis erat eu, lobortis magna.</p>\r\n<p>Nunc porttitor varius tellus, a vestibulum massa tristique a. Mauris sit amet est tellus. Curabitur lobortis laoreet faucibus. Cras sem enim, laoreet tempus leo id, sodales convallis tortor. Maecenas quis sapien porttitor, scelerisque tortor in, auctor urna. Ut pretium nisl laoreet ex feugiat sollicitudin. Curabitur aliquam quam lorem, quis consectetur neque hendrerit vitae. Sed pulvinar quam massa, in congue quam interdum at. Aliquam euismod consequat lorem, a malesuada enim dapibus et.</p>\r\n<p>Maecenas et tempus tellus. Mauris porta nisl eu ipsum luctus, et malesuada diam suscipit. Nunc condimentum metus ut varius scelerisque. Morbi eu sapien egestas, dignissim nunc eu, rutrum dui. Nullam convallis vehicula libero sed blandit. Praesent auctor dolor sapien, at venenatis nisi accumsan sed. Vestibulum porttitor a libero non sollicitudin. Aenean vitae neque nibh. Fusce sit amet vestibulum magna. Quisque vel lacinia felis. Nunc non fermentum tellus.</p>\r\n<p>Morbi eget dui a lorem tempor maximus. Donec mollis ligula sed tortor congue, in auctor magna pellentesque. Donec a arcu et eros placerat eleifend. Donec pretium, sem a vehicula pharetra, metus diam fermentum ligula, non convallis nisl leo non orci. Aliquam vestibulum placerat nibh, ac malesuada mi bibendum rutrum. Sed facilisis suscipit sapien in faucibus. Vivamus viverra, ipsum sed condimentum dignissim, elit felis pharetra orci, posuere hendrerit ipsum ligula sit amet sapien. Aliquam sollicitudin, velit nec porttitor porta, neque ex pharetra mauris, in fringilla elit ex dapibus sem. Curabitur nisi felis, sagittis sed ipsum fermentum, imperdiet euismod augue. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Etiam vel leo eget est molestie ultrices ac in lacus. Curabitur eu interdum nunc, vitae egestas mauris. Donec consectetur sodales luctus. Maecenas arcu est, placerat vel facilisis a, bibendum eu enim. Integer ornare lorem sit amet ligula euismod fringilla.</p>', '2020-05-20', 'ram', '08afd87bb8256468f673e4e34e26e232.png', 'welcime');

-- --------------------------------------------------------

--
-- Structure de la table `cms_pages`
--

CREATE TABLE `cms_pages` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `unique_slug` varchar(255) NOT NULL,
  `name` text NOT NULL,
  `content` text NOT NULL,
  `is_visible` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

ALTER TABLE `cms_pages` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

--
-- Déchargement des données de la table `cms_pages`
--

INSERT INTO `cms_pages` (`id`, `category`, `unique_slug`, `name`, `content`, `is_visible`) VALUES
('game', 'download', 'Download', '<p><img style=\"float: left; margin: 0px 10px;\" src=\"https://www.freemmorpgmaker.com/wp-content/uploads/2017/04/dkMSxwUYbOuu-1-1.png\" width=\"313\" height=\"194\" />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris non nulla vitae erat finibus molestie. Vestibulum sit amet quam. Praesent scelerisque sem ipsum, at hendrerit nisl dapibus a. Quisque vulputate sem eu turpis pharetra. Aenean consectetur sodales leo, at mattis metus dictum quis. Morbi consequat nunc ultricies purus tincidunt, quis mi. Nullam maximus semper arcu, eu mattis enim aliquet eget.</p>\r\n<p>Integer sem tortor, ornare sit amet dui quis, volutpat tristique velit. Nam blandit feugiat nulla a iaculis. Fusce sagittis vel orci eget tempor. Vestibulum ut nunc mattis, ullamcorper velit at, convallis neque. Maecenas sagittis, sem at hendrerit imperdiet, odio urna egestas leo, placerat fermentum sem erat scelerisque purus. Phasellus ultricies est sit amet enim imperdiet, sed tristique nibh porttitor. Nulla molestie libero ac felis accumsan, ac mattis ex venenatis. Nam at lectus tempus, lobortis urna eget, viverra leo. Ut semper et eros eu semper. Sed mattis, magna id vehicula aliquet, dui quam eleifend orci, at molestie justo metus vel enim. Sed vitae urna leo. Nullam dapibus nisl dapibus venenatis tristique. Praesent vel turpis iaculis, laoreet erat non, fringilla augue. In tincidunt ipsum nec ligula lobortis, vitae tempus tortor porta. Aenean gravida eget nibh eget hendrerit. Vivamus eu metus porttitor, mattis erat eu, lobortis magna.</p>\r\n<p>Nunc porttitor varius tellus, a vestibulum massa tristique a. Mauris sit amet est tellus. Curabitur lobortis laoreet faucibus. Cras sem enim, laoreet tempus leo id, sodales convallis tortor. Maecenas quis sapien porttitor, scelerisque tortor in, auctor urna. Ut pretium nisl laoreet ex feugiat sollicitudin. Curabitur aliquam quam lorem, quis consectetur neque hendrerit vitae. Sed pulvinar quam massa, in congue quam interdum at. Aliquam euismod consequat lorem, a malesuada enim dapibus et.</p>\r\n<p style=\"text-align: center;\"><a href=\"https://mega.nz/\" target=\"_blank\" rel=\"noopener\">Download the game</a></p>', 1);

INSERT INTO `cms_pages` (`id`, `category`, `unique_slug`, `name`, `content`, `is_visible`) VALUES ('Legal', 'legal-notice', 'Legal Notice', '<p>your text</p>', '1'), ('Legal', 'terms-and-conditions', 'Terms and conditions', '<p>your text<p>', '1'), ('Legal', 'confidentiality', 'Confidentiality', '<p>your text<p>', '1');

-- --------------------------------------------------------

--
-- Structure de la table `cms_points_history`
--

CREATE TABLE `cms_points_history` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `user_id` text NOT NULL,
  `code` varchar(255) NOT NULL,
  `points_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `cms_settings`
--

CREATE TABLE `cms_settings` (
  `id` int(11) NOT NULL,
  `setting` varchar(255) NOT NULL,
  `default_value` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `cms_settings`
--

INSERT INTO `cms_settings` (`id`, `setting`, `default_value`, `description`) VALUES
(1, 'api_password', 'f2d81a260dea8a100dd517984e53c56a7523d96942a834b9cdc249bd4e8c7aa9', 'API Password'),
(2, 'api_server', 'http://localhost:5400', 'API Server'),
(3, 'api_token', 'PCpn4HO7EPZtA4vCYnxptqp2Iz634x8kUqDza_BheD8uAz1dg3lbOoQHSuDTTTaw5K5AbZ1Sp8MTSd4YSm74JvdUNDwMP6pVciG7wK8aY-3Kuvef8jwOAfy817KSh7Xsa68SAUPR5sR1urU4bQf6I6IF2Cd9kys8YZcTO_2JYm6yVO4MWy5bt3gdnsQC10DN7Bgz42Roc34lrD1DgVRpAbjJ6EWSEGkPh7th9Hk3ttbRkZ9xSSxaNEICiqg5E6hNW9UOdLwqPIqSGm_IC6bRD-_kD2ZXGNdr5nai_7LofUZ6KcTlvpymQXXK5ojolEjUqgBYipgYHe0nk8g7dwMvBZI0rm6PdtkOqIW57PB0Z73ykZ_SqTX1Zgsxn3hqaeoVp6ySbvBGkXreJVmFtNSFCHDftc2VLFxuBoMyYixivliiasGmf-6pNVlitOxF2gL7re50wPs2bXvr92IbHYUTcU0nyn9iozFGRwy4q45yXx7ttz6cNFK-4StZm7foaLJvKBldohgaLC-9D9-6axS8kBbeje1MrC7N3cvZWJwUEzVph_82ST9ENunp0-VLZn0flawi2lhXn30ap6Z8Q8jdvQ', 'Token API'),
(4, 'api_username', 'apiAccount', 'API User'),
(5, 'base_url', 'http://intersectcms-rework.teste/', 'CMS Base URL'),
(7, 'credit_dedipass_private_key', 'ff5d27e41f27baa414bb62633474066fd072047a', 'Dedipass private key'),
(8, 'credit_dedipass_public_key', '7992623d79b6598878f1166d10a71856', 'Dedipass public key'),
(9, 'current_lang', 'en', 'Lang Used'),
(10, 'game_title', 'My Game', 'Game Name'),
(11, 'seo_description', 'yourdescription', 'Description SEO'),
(12, 'theme', 'Britania', 'Theme Name'),
(13, 'use_custom_game_pages', 'true', 'Use custom game pages'),
(14, 'use_nav_community', 'true', 'Use community nav tab'),
(15, 'use_right_community_button', 'true', 'Display right menu community buttons'),
(16, 'use_wiki', 'true', 'Use custom wiki'),
(33, 'facebook_link', 'https://facebook.com/', 'Lien Facebook'),
(34, 'twitter_link', '#', 'Lien Twitter'),
(35, 'youtube_link', '#', 'Lien Youtube'),
(36, 'instagram_link', '#', 'Lien Instagram'),
(37, 'discord_link', '#', 'Lien Discord'),
(38, 'activate_forum', '1', 'Activate forums parts.');

INSERT INTO `cms_settings` (`id`, `setting`, `default_value`, `description`) VALUES (NULL, 'tinymce_key', 'no-api-key', 'Key for use editor in administration'), (NULL, 'max_level', '100', 'Max level on your game');

-- --------------------------------------------------------

--
-- Structure de la table `cms_shop`
--

CREATE TABLE `cms_shop` (
  `id` int(11) NOT NULL,
  `id_item` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `forceddescription` text NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `promotion` int(11) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `cms_shop_history`
--

CREATE TABLE `cms_shop_history` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `shop_id` int(11) NOT NULL,
  `user_id` text NOT NULL,
  `credits_now` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `cms_users`
--

CREATE TABLE `cms_users` (
  `webId` int(11) NOT NULL,
  `id` text NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `points` int(11) NOT NULL,
  `admin` int(11) NOT NULL,
  `password_token` varchar(255) DEFAULT NULL,
  `roles` json NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cms_news`
--
ALTER TABLE `cms_news`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cms_pages`
--
ALTER TABLE `cms_pages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cms_points_history`
--
ALTER TABLE `cms_points_history`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cms_settings`
--
ALTER TABLE `cms_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting` (`setting`);

--
-- Index pour la table `cms_shop`
--
ALTER TABLE `cms_shop`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cms_shop_history`
--
ALTER TABLE `cms_shop_history`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cms_users`
--
ALTER TABLE `cms_users`
  ADD PRIMARY KEY (`webId`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cms_news`
--
ALTER TABLE `cms_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT pour la table `cms_pages`
--
ALTER TABLE `cms_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT pour la table `cms_points_history`
--
ALTER TABLE `cms_points_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cms_settings`
--
ALTER TABLE `cms_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `cms_shop`
--
ALTER TABLE `cms_shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cms_shop_history`
--
ALTER TABLE `cms_shop_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cms_users`
--
ALTER TABLE `cms_users`
  MODIFY `webId` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
