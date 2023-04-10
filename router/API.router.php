<?php
require_once BASE . "/router.php";
class Router extends BaseRouter {
}
$route = new Router();
$route->run('GET', '/API/first', "main@getMainPage");
$route->run('POST', '/API/second', "main@getIndex");