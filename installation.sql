-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 28 juin 2021 à 15:01
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
  `title` text NOT NULL,
  `content` longtext NOT NULL,
  `date` date NOT NULL,
  `author` text NOT NULL,
  `img_url` text NOT NULL,
  `slug` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;
ALTER TABLE `cms_news` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `cms_news` ADD `category_id` int(11);

-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Structure de la table `cms_news_category`
--

CREATE TABLE `cms_news_category` (
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;
ALTER TABLE `cms_news_category` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE cms_news ADD CONSTRAINT FK_DA45342D12469DE2 FOREIGN KEY (category_id) REFERENCES cms_news_category (id)
ALTER TABLE `cms_news_category` ADD `color` VARCHAR(255) NOT NULL AFTER `name`;

-- --------------------------------------------------------

--
-- Structure de la table `cms_pages`
--

CREATE TABLE `cms_pages` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `unique_slug` varchar(255) NOT NULL,
  `name` text NOT NULL,
  `content` longtext NOT NULL,
  `is_visible` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;


INSERT INTO `cms_pages` (`id`, `category`, `unique_slug`, `name`, `content`, `is_visible`) VALUES
(1,'game', 'download', 'Download', '<p><img style=\"float: left; margin: 0px 10px;\" src=\"https://www.freemmorpgmaker.com/wp-content/uploads/2017/04/dkMSxwUYbOuu-1-1.png\" width=\"313\" height=\"194\" />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris non nulla vitae erat finibus molestie. Vestibulum sit amet quam. Praesent scelerisque sem ipsum, at hendrerit nisl dapibus a. Quisque vulputate sem eu turpis pharetra. Aenean consectetur sodales leo, at mattis metus dictum quis. Morbi consequat nunc ultricies purus tincidunt, quis mi. Nullam maximus semper arcu, eu mattis enim aliquet eget.</p>\r\n<p>Integer sem tortor, ornare sit amet dui quis, volutpat tristique velit. Nam blandit feugiat nulla a iaculis. Fusce sagittis vel orci eget tempor. Vestibulum ut nunc mattis, ullamcorper velit at, convallis neque. Maecenas sagittis, sem at hendrerit imperdiet, odio urna egestas leo, placerat fermentum sem erat scelerisque purus. Phasellus ultricies est sit amet enim imperdiet, sed tristique nibh porttitor. Nulla molestie libero ac felis accumsan, ac mattis ex venenatis. Nam at lectus tempus, lobortis urna eget, viverra leo. Ut semper et eros eu semper. Sed mattis, magna id vehicula aliquet, dui quam eleifend orci, at molestie justo metus vel enim. Sed vitae urna leo. Nullam dapibus nisl dapibus venenatis tristique. Praesent vel turpis iaculis, laoreet erat non, fringilla augue. In tincidunt ipsum nec ligula lobortis, vitae tempus tortor porta. Aenean gravida eget nibh eget hendrerit. Vivamus eu metus porttitor, mattis erat eu, lobortis magna.</p>\r\n<p>Nunc porttitor varius tellus, a vestibulum massa tristique a. Mauris sit amet est tellus. Curabitur lobortis laoreet faucibus. Cras sem enim, laoreet tempus leo id, sodales convallis tortor. Maecenas quis sapien porttitor, scelerisque tortor in, auctor urna. Ut pretium nisl laoreet ex feugiat sollicitudin. Curabitur aliquam quam lorem, quis consectetur neque hendrerit vitae. Sed pulvinar quam massa, in congue quam interdum at. Aliquam euismod consequat lorem, a malesuada enim dapibus et.</p>\r\n<p style=\"text-align: center;\"><a href=\"https://mega.nz/\" target=\"_blank\" rel=\"noopener\">Download the game</a></p>', 1);

INSERT INTO `cms_pages` (`id`, `category`, `unique_slug`, `name`, `content`, `is_visible`) VALUES (2,'Legal', 'legal-notice', 'Legal Notice', '<p>your text</p>', '1'), (3,'Legal', 'terms-and-conditions', 'Terms and conditions', '<p>your text<p>', '1'), (4,'Legal', 'confidentiality', 'Confidentiality', '<p>your text<p>', '1');

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
(1, 'api_password', 'password_account_api', 'API Password'),
(2, 'api_server', 'http://yourserver:yourport', 'API Server'),
(3, 'api_token', '', 'Token API'),
(4, 'api_username', 'username_account_api', 'API User'),
(5, 'base_url', 'http://intersectcms-rework.teste/', 'CMS Base URL'),
(7, 'credit_dedipass_private_key', 'ff5d27e41f27baa414bb62633474066fd072047a', 'Dedipass private key'),
(8, 'credit_dedipass_public_key', '7992623d79b6598878f1166d10a71856', 'Dedipass public key'),
(9, 'current_lang', 'en', 'Lang Used'),
(10, 'game_title', 'My Game', 'Game Name'),
(11, 'seo_description', 'yourdescription', 'Description SEO'),
(12, 'theme', 'BritaniaR', 'Theme Name'),
(13, 'use_custom_game_pages', 'true', 'Use custom game pages'),
(14, 'use_nav_community', 'true', 'Use community nav tab'),
(15, 'use_right_community_button', 'true', 'Display right menu community buttons'),
(16, 'use_wiki', 'true', 'Use custom wiki'),
(33, 'facebook_link', 'https://facebook.com/', 'Lien Facebook'),
(34, 'twitter_link', 'https://facebook.com/', 'Lien Twitter'),
(35, 'youtube_link', 'https://facebook.com/', 'Lien Youtube'),
(36, 'instagram_link', 'https://facebook.com/', 'Lien Instagram'),
(37, 'discord_link', 'https://facebook.com/', 'Lien Discord'),
(38, 'activate_forum', '1', 'Activate forums parts.'),
(39, 'tinymce_key', 'no-api-key', 'Key for use editor in administration'),
(40, 'max_level', '10', 'Max level on your game');

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
  `roles` json NOT NULL,
  `discord` varchar(255) DEFAULT NULL
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cms_pages`
--
ALTER TABLE `cms_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cms_points_history`
--
ALTER TABLE `cms_points_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cms_settings`
--
ALTER TABLE `cms_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

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
