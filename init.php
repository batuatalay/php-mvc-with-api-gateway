<?php
define("DEVELOPMENT", true);
if(DEVELOPMENT) {
	define("ENV", "http://localhost:80/");
	define("BASE", "/var/www/html/");
	define("DBHOST", "adminzone-db");
	define("DBNAME", "tyr-db");
	define("PANEL", "http://localhost/");

	define("EMAIL", "-");
	define("EMAILPASSWORD", "-");
	define("SMTPHOST","-");

} else {
	define("ENV", "-");
	define("BASE", "-");
	define("DBHOST", "localhost");
	define("DBNAME", "-");
	define("PANEL", "-");

	define("EMAIL", "-");
	define("EMAILPASSWORD", "-");
	define("SMTPHOST","-");
}

if(DEVELOPMENT) {
	define("DBUSERNAME", "root");
	define("DBPASSWORD", "1q2w3e4r");
} else {
	define("DBUSERNAME", "-");
	define("DBPASSWORD", "-");
}