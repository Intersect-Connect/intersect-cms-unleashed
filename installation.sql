-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 22 avr. 2021 à 23:28
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
  `slug` varchar(255) NOT NULL,
  `is_visible` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `cms_news`
--

INSERT INTO `cms_news` (`id`, `title`, `content`, `date`, `author`, `img_url`, `slug`, `is_visible`) VALUES
(1, 'Welcome on IntersectCMS', '<p><strong>Lorem ipsum dolor sit amet</strong>, consectetur adipiscing elit. Mauris non nulla vitae erat finibus molestie. Vestibulum sit amet turpis quam. Praesent scelerisque sem ipsum, at hendrerit nisl dapibus a. Quisque vulputate sem eu turpis fringilla pharetra. Aenean consectetur sodales leo, at mattis metus dictum quis. Morbi consequat nunc ultricies purus tincidunt, quis ultrices mi tincidunt. Nullam maximus semper arcu, eu mattis enim aliquet eget.</p>\r\n<p>Integer sem tortor, ornare sit amet dui quis, volutpat tristique velit. Nam blandit feugiat nulla a iaculis. Fusce sagittis vel orci eget tempor. Vestibulum ut nunc mattis, ullamcorper velit at, convallis neque. Maecenas sagittis, sem at hendrerit imperdiet, odio urna egestas leo, placerat fermentum sem erat scelerisque purus. Phasellus ultricies est sit amet enim imperdiet, sed tristique nibh porttitor. Nulla molestie libero ac felis accumsan, ac mattis ex venenatis. Nam at lectus tempus, lobortis urna eget, viverra leo. Ut semper et eros eu semper. Sed mattis, magna id vehicula aliquet, dui quam eleifend orci, at molestie justo metus vel enim. Sed vitae urna leo. Nullam dapibus nisl dapibus venenatis tristique. Praesent vel turpis iaculis, laoreet erat non, fringilla augue. In tincidunt ipsum nec ligula lobortis, vitae tempus tortor porta. Aenean gravida eget nibh eget hendrerit. Vivamus eu metus porttitor, mattis erat eu, lobortis magna.</p>\r\n<p>Nunc porttitor varius tellus, a vestibulum massa tristique a. Mauris sit amet est tellus. Curabitur lobortis laoreet faucibus. Cras sem enim, laoreet tempus leo id, sodales convallis tortor. Maecenas quis sapien porttitor, scelerisque tortor in, auctor urna. Ut pretium nisl laoreet ex feugiat sollicitudin. Curabitur aliquam quam lorem, quis consectetur neque hendrerit vitae. Sed pulvinar quam massa, in congue quam interdum at. Aliquam euismod consequat lorem, a malesuada enim dapibus et.</p>\r\n<p>Maecenas et tempus tellus. Mauris porta nisl eu ipsum luctus, et malesuada diam suscipit. Nunc condimentum metus ut varius scelerisque. Morbi eu sapien egestas, dignissim nunc eu, rutrum dui. Nullam convallis vehicula libero sed blandit. Praesent auctor dolor sapien, at venenatis nisi accumsan sed. Vestibulum porttitor a libero non sollicitudin. Aenean vitae neque nibh. Fusce sit amet vestibulum magna. Quisque vel lacinia felis. Nunc non fermentum tellus.</p>\r\n<p>Morbi eget dui a lorem tempor maximus. Donec mollis ligula sed tortor congue, in auctor magna pellentesque. Donec a arcu et eros placerat eleifend. Donec pretium, sem a vehicula pharetra, metus diam fermentum ligula, non convallis nisl leo non orci. Aliquam vestibulum placerat nibh, ac malesuada mi bibendum rutrum. Sed facilisis suscipit sapien in faucibus. Vivamus viverra, ipsum sed condimentum dignissim, elit felis pharetra orci, posuere hendrerit ipsum ligula sit amet sapien. Aliquam sollicitudin, velit nec porttitor porta, neque ex pharetra mauris, in fringilla elit ex dapibus sem. Curabitur nisi felis, sagittis sed ipsum fermentum, imperdiet euismod augue. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Etiam vel leo eget est molestie ultrices ac in lacus. Curabitur eu interdum nunc, vitae egestas mauris. Donec consectetur sodales luctus. Maecenas arcu est, placerat vel facilisis a, bibendum eu enim. Integer ornare lorem sit amet ligula euismod fringilla.</p>', '2020-05-20', 'ram', '08afd87bb8256468f673e4e34e26e232.png', 'welcime', 1);

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
  `is_visible` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `cms_pages`
--

INSERT INTO `cms_pages` (`id`, `category`, `unique_slug`, `name`, `content`, `is_visible`) VALUES
(1, 'game', 'download', 'Download', '<p><img style=\"float: left; margin: 0px 10px;\" src=\"https://www.freemmorpgmaker.com/wp-content/uploads/2017/04/dkMSxwUYbOuu-1-1.png\" width=\"313\" height=\"194\" />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris non nulla vitae erat finibus molestie. Vestibulum sit amet quam. Praesent scelerisque sem ipsum, at hendrerit nisl dapibus a. Quisque vulputate sem eu turpis pharetra. Aenean consectetur sodales leo, at mattis metus dictum quis. Morbi consequat nunc ultricies purus tincidunt, quis mi. Nullam maximus semper arcu, eu mattis enim aliquet eget.</p>\r\n<p>Integer sem tortor, ornare sit amet dui quis, volutpat tristique velit. Nam blandit feugiat nulla a iaculis. Fusce sagittis vel orci eget tempor. Vestibulum ut nunc mattis, ullamcorper velit at, convallis neque. Maecenas sagittis, sem at hendrerit imperdiet, odio urna egestas leo, placerat fermentum sem erat scelerisque purus. Phasellus ultricies est sit amet enim imperdiet, sed tristique nibh porttitor. Nulla molestie libero ac felis accumsan, ac mattis ex venenatis. Nam at lectus tempus, lobortis urna eget, viverra leo. Ut semper et eros eu semper. Sed mattis, magna id vehicula aliquet, dui quam eleifend orci, at molestie justo metus vel enim. Sed vitae urna leo. Nullam dapibus nisl dapibus venenatis tristique. Praesent vel turpis iaculis, laoreet erat non, fringilla augue. In tincidunt ipsum nec ligula lobortis, vitae tempus tortor porta. Aenean gravida eget nibh eget hendrerit. Vivamus eu metus porttitor, mattis erat eu, lobortis magna.</p>\r\n<p>Nunc porttitor varius tellus, a vestibulum massa tristique a. Mauris sit amet est tellus. Curabitur lobortis laoreet faucibus. Cras sem enim, laoreet tempus leo id, sodales convallis tortor. Maecenas quis sapien porttitor, scelerisque tortor in, auctor urna. Ut pretium nisl laoreet ex feugiat sollicitudin. Curabitur aliquam quam lorem, quis consectetur neque hendrerit vitae. Sed pulvinar quam massa, in congue quam interdum at. Aliquam euismod consequat lorem, a malesuada enim dapibus et.</p>\r\n<p style=\"text-align: center;\"><a href=\"https://mega.nz/\" target=\"_blank\" rel=\"noopener\">Download the game</a></p>', 1),
(2, 'wiki', 'test', 'Test', '<p>Test de page :D</p>', 1);

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
(3, 'api_token', '0VamjUdyhkKfWhOSQD943OBT8OpFuSUTj484wWM49qWSDKK8_8eVkN4gYqj6ZAdHL9eabc12WCuAEwlfDf5TS4xRNDKK4DzzBhlDPaZBr-p-x2i2eBY9dVBmPHXZoHQVkSQZHVzK0zIDgg-S6pTw34PhI5_PgIxgtzLxBITJW1AyvXwyOa6CDBrTTIixHVQfDEDzKm0juMQ1SoUy8ojfcT4-lhb8ItKboDtMr79int-HeO3qiWChKI4GHXeO71GysAOB_A5czWhh2EY4yBIu4HGkaFLGYfO2-XmAtXHV3JcLnVedcIFytdzur8-NNdghZzchwwhYRXalEnMU4rt5_mpnnu8AIRDZLGdUeRhRydI9mDWniyjLSTWcakUgwUZrIw5RIsReEsx0YcC3y7P1D2O1EtZQ9s65RHWC1gGxVhCf30UsOeeVdGoEnQH6dEeRj76xRy0jOfr--0pK00yLoRvcg82Am76hXAquO8XIQZoRdexa0p8Pkez5RH8WBvPuFCF3eias7n7dBETeGhTToDXPPp7fHeXpc4vLlYM7WlKTKiptTmtrLHzuqqsBUs4igcCFFtotZAOp9x4RRIPOrw', 'Token API'),
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
(37, 'discord_link', '#', 'Lien Discord');

-- --------------------------------------------------------

--
-- Structure de la table `cms_shop`
--

CREATE TABLE `cms_shop` (
  `id` int(11) NOT NULL,
  `id_item` varchar(255) NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `forceddescription` text NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `promotion` int(11) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '1',
  `name` varchar(255) DEFAULT NULL,
  'image' varchar(255) DEFAULT NULL
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
  `points` int(11) NOT NULL DEFAULT '0',
  `admin` int(1) NOT NULL DEFAULT '0',
  `password_token` varchar(255) DEFAULT NULL,
  `roles` json NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cms_news`
--
ALTER TABLE `cms_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `cms_pages`
--
ALTER TABLE `cms_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `cms_points_history`
--
ALTER TABLE `cms_points_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cms_settings`
--
ALTER TABLE `cms_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
