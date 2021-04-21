Current version: 2.0

Installation Guide :
https://github.com/Thomasfds/intersect-cms/wiki/Installation-(FR)

### For new installations, remember to retrieve the SQL queries from patch 1.1

Supporting the Creator: https://www.paypal.me/thomaaasfds

 -------------------------
 
# Intersect CMS Rework

[![N|Solid](https://s3.us-east-2.amazonaws.com/ascensiongamedev/filehost/a4727b61d3221e25d4960d124f383986.png)](https://www.freemmorpgmaker.com/)

IntersectCMS uses the Intersect Engine API to process data as simply as possible through a web interface. Whether you use the sqlite or mysql database, this CMS will automatically adapt itself thanks to the game engine API. Part of the CMS uses a MySQL database, so it is necessary to have one for the proper functioning of the site's functionalities.

# Features

- News (DB)
- Register (API)
- Login (API + DB)
- Ranking (API)
- Account Informations (API + DB)
- Shop (API + DB)
    - Quantity
    - History
    - Categories 
    - Discount
    - Item visibility
- Points system with history (DB)
- Account Points/Credit (currently working with https://dedipass.com/fr/) (DB)
- Server status (API)
- Custom Pages (for game and wiki sections) (DB)
- Basic administration with Rich Text editor (TinyMCE)
    - News (DB)
    - Shop (API + DB)
    - Pages (DB)
    - CMS Settings (DB)
    - CMS Translation (DB)
- Basic Translation system (DB) (currently only en and fr langs)
- Multi-templates system
    - You can create as many templates as you want without even touch PHP code
    - Basic HTML knowledge is required
    - 'public\themes\[theme name]' directory contains themes assets, 'public\general' contains base assets used by all themes (like objects images), and 'application\templates\[theme name]'' contains twig views

### Tech

IntersectCMS uses a number of open source projects to work properly:

* [Slimframework](https://www.slimframework.com/) - PHP micro framework
* [Twig](https://twig.symfony.com/) - flexible, fast, and secure template engine for PHP
* [RedBeanPHP](https://www.redbeanphp.com/index.php) - fast and easy to use ORM for PHP
* [PHP-DI](http://php-di.org/) - dependency injection container
* [Nyholm PSR7](https://github.com/Nyholm/psr7) - PSR-7 implementation

### Installation

IntersectCMS requirements :

- Web server with URL rewriting
- PHP 7.2 or newer

Installation step by step :

- Download the repository
- Upload the folder on your web server directory
- Point your virtual host document root to the CMS `public/` directory. (Virtual host is the mandatory way to access to your project. If you try to access directly from the container folder you will encounter an error) (Moar simple: search google "point virtual host apache")
- Ensure `cache/` (if the directory is not created, do it manually) and `logs/` are web writable.
- Create a database for the CMS
- Execute the SQL patch `cms_database.sql` to the freshly created DB
- Edit `[CMS Dir]\application\App\Preferences.php` and replace `$db...` values by yours (Ip, User, Password, Port (should be 3306 in most cases) and Name)
- Finally, edit the `cms_settings` table in the database with your API infos (you will be able to customize the others settings in the administration panel)
You will maybe need to apply some SQL patchs due to updates, please execute them in order (from `patch_1.sql` to `patch_X.sql`.

When you will login for the first time, your game account will create a web account (based on your unique game ID) in the database automatically (in the `cms_users` table). There, you can choose if an user can access the administration, or add shop points to the player.

### Screenshots

![N|Solid](https://i.postimg.cc/sX55nFyD/1.png)
![N|Solid](https://i.postimg.cc/Xq8dbwpR/2.png)
![N|Solid](https://i.postimg.cc/Y0kYZXqN/3.png)
![N|Solid](https://i.postimg.cc/6qY2StPw/4.png)

### Todos

 - Work on new templates
 - More payement gateway
 - Add more settings

License
----

MIT


**Glhf with your MMORPG. But hey, keep my name here or here, it's always appreciated :)**
