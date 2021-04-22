-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 21 avr. 2021 à 15:25
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
(3, 'api_token', 'TrB_i2pF3N-HJo6H9-oq9XTMbeYbPQ-XgP440NxAvxCNI-WjU0YaocM0jrh9D09-mKsdcd91CWreMHlYZxGckgqzEmT35PWT8s8VtQpMkBFb2mptsRPusd6fj0KW-eGOwoDZ8TLWlcD-PEw_ABHFKXu2Banfl5w90H8Z1zJpW1Bf_fE05Rm_wuh7PK2FtI6oAIx2trCfz5ebFXJfBvmbea3tKVuH-AKl1x22rCeNXrf-mBfI11hl83G1eo0-FJ0zlYd4h_JNegi6mr6HlVXfa-7EaluyohnNeLUDkGiHt9VeReeJoR4LwurEt_iMtCJM9jPBHPo5ljn9ADn15YU4fqho9QoY-qJmGjDE3w-BjHDbpsOuepjwdWuoSeuh0tZyLBD4qGkGWta9erpKg2CbR43Rh_0iIjrxbQky2kEa-CQx5BZizP21AnMTo0K1deWMh-cel8GxeommM30dbIue5JdSem3IpyMDG3mpKnnu869z0m4UusiXf0JesT9iJdWiaYgcKeGEpVUstJy7bqDu4h9oEkJtDGX7ndhH4X3WKAAPNPRGLGIxSguGhcLbVVL2cAjhfELBKnvuyjvh9mb13w', 'Token API'),
(4, 'api_username', 'apiAccount', 'API User'),
(5, 'base_url', 'http://intersectcms-rework.teste/', 'CMS Base URL'),
(6, 'credit_dedipass_html_data', '<div data-dedipass=\"[this]\" data-dedipass-custom=\"\">', 'Dedipass HTML data-dedipass value'),
(7, 'credit_dedipass_private_key', '$dedipass = file_get_contents(\'http://api.dedipass.com/v1/pay/?public_key=[not this]&private_key=[this]&code=\' . $code);', 'Dedipass private key'),
(8, 'credit_dedipass_public_key', '$dedipass = file_get_contents(\'http://api.dedipass.com/v1/pay/?public_key=[this]&private_key=[not this]&code=\' . $code);', 'Dedipass public key'),
(9, 'current_lang', 'en', 'Lang Used'),
(10, 'game_title', 'My Game', 'Game Name'),
(11, 'seo_description', 'yourdescription', 'Description SEO'),
(12, 'theme', 'Britania', 'Theme Name'),
(13, 'use_custom_game_pages', 'true', 'Use custom game pages'),
(14, 'use_nav_community', 'true', 'Use community nav tab'),
(15, 'use_right_community_button', 'true', 'Display right menu community buttons'),
(16, 'use_wiki', 'true', 'Use custom wiki'),
(33, 'facebook_link', 'sqdqsdqsds', 'Lien Facebook'),
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
  `name` varchar(255) DEFAULT NULL
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
  `roles` json NOT NULL DEFAULT '[]'
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
