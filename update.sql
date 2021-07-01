-- CMS Settings, add new features settings
INSERT INTO `cms_settings` (`id`, `setting`, `default_value`, `description`) VALUES (NULL, 'tinymce_key', 'no-api-key', 'Key for use editor in administration'), (NULL, 'max_level', '100', 'Max level on your game');

-- CMS Pages
ALTER TABLE `cms_pages` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

-- CMS News
ALTER TABLE `cms_news` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `cms_news` ADD `category_id` int(11)


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