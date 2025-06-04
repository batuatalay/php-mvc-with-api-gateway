<?php
require_once BASE . "/router.php";
require_once BASE . "/middleware/common.middleware.php";

class Router extends BaseRouter {
    public function get($url, $callback, $parameters = null) {
        parent::get($url, $callback, $parameters);
    }

    public function post($url, $callback, $parameters = null) {
        parent::post($url, $callback, $parameters);
    }
}

$route = new Router();
$route->get('/initialize', "Initialize@getMainPage");