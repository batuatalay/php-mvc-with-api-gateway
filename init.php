<?php
define("DEVELOPMENT", true);
if(DEVELOPMENT) {
	define("ENV", "http://localhost:8080/");
	define("BASE", "/var/www/html/");
} else {
	define("ENV", "your website url");
	define("BASE", "your website directory location");
}
define("DBHOST", "localhost");
define("DBNAME", "dbname");
if(DEVELOPMENT) {
	define("DBUSERNAME", "ironman");
	define("DBPASSWORD", "1q2w3e4r");
} else {
	define("DBUSERNAME", "test");
	define("DBPASSWORD", "test");
}

