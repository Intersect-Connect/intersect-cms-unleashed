-- CMS_SETTING
-- Insertion des liens réseaux sociaux
INSERT INTO cms_settings (setting, default_value, description) VALUES ('facebook_link', '#', 'Lien Facebook');
INSERT INTO cms_settings (setting, default_value, description) VALUES ('twitter_link', '#', 'Lien Twitter');
INSERT INTO cms_settings (setting, default_value, description) VALUES ('youtube_link', '#', 'Lien Youtube');
INSERT INTO cms_settings (setting, default_value, description) VALUES ('instagram_link', '#', 'Lien Instagram');
INSERT INTO cms_settings (setting, default_value, description) VALUES ('discord_link', '#', 'Lien Discord');

-- Suppression de la colonne value
ALTER TABLE cms_settings DROP COLUMN value;

-- CMS_SHOP
-- Ajout de la colonne "Name"
ALTER TABLE cms_shop ADD name VARCHAR(255)

-- CMS_USER
-- Ajout de la colonne "roles"
ALTER TABLE cms_user ADD roles JSON
