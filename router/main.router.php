<?php
require_once BASE . "/router.php";
class Router extends BaseRouter {
}
$route = new Router();
$route->run('GET', '/main', "main@getMainPage");
$route->run('POST', '/main/index', "main@getIndex");
