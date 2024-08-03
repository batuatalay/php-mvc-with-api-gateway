<?php
require_once BASE . "/router.php";
class Router extends BaseRouter {
}
$route = new Router();
$route->get('/main', "main@getMainPage");
$route->get('/main/dashboard', "main@getDashboard");
