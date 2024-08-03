<?php
require_once BASE . "/router.php";
class Router extends BaseRouter {
}
$route = new Router();
$route->get('/login', "Login@loginPage");
$route->post('/login/signIn', "Login@signIn");
$route->get('/login/signOut', "Login@signOut");
