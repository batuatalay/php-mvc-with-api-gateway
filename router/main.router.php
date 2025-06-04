<?php
require_once BASE . "/router.php";
require_once BASE . "/middleware/common.middleware.php";

class Router extends BaseRouter {
}

$route = new Router();
$route->get('/main', "Main@getMainPage");
$route->get('/main/dashboard', "Main@getDashboard");
$route->get('/login/signOut', "Login@signOut");