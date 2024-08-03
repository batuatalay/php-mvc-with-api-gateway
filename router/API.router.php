<?php
require_once BASE . "/router.php";
class Router extends BaseRouter {
}
$route = new Router();
$route->get('/API/first', "main@getMainPage");
$route->post('/API/second', "login@signOut");