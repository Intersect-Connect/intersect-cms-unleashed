-- CMS Settings, add new features settings
INSERT INTO `cms_settings` (`id`, `setting`, `default_value`, `description`) VALUES (NULL, 'tinymce_key', 'no-api-key', 'Key for use editor in administration'), (NULL, 'max_level', '100', 'Max level on your game');

-- CMS Pages
ALTER TABLE `cms_pages` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

-- CMS News
ALTER TABLE `cms_news` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;