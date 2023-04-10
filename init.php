<?php
define("DEVELOPMENT", true);
if(DEVELOPMENT) {
	define("ENV", "http://localhost/");
	define("BASE", "your directory location");
} else {
	define("ENV", "your website url");
	define("BASE", "your website directory location");
}
define("DBHOST", "localhost");
define("DBNAME", "dbname");
if(DEVELOPMENT) {
	define("DBUSERNAME", "test");
	define("DBPASSWORD", "test");
} else {
	define("DBUSERNAME", "test");
	define("DBPASSWORD", "test");
}

