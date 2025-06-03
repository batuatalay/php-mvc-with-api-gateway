<?php
require_once BASE . "/router.php";
require_once BASE . "/middleware/common.middleware.php";

class Router extends BaseRouter {

    // get and post methods are overridden
    public function get($url, $callback, $parameters = null) {
        parent::get($url, $callback, $parameters);
    }

    public function post($url, $callback, $parameters = null) {
        parent::post($url, $callback, $parameters);
    }
    
    // getParameters method is made public
    public function getParameters($url) {
        $data = explode('/', $this->uri);
        $param = explode('/', $url);
        foreach($param as $key => $pr) {
            if(strpos($pr, "#") !== false) {
                return [$pr, $data[$key]];
            }
        }
    }
}

$route = new Router();
$route->get('/main', "Main@getMainPage");
$route->get('/main/dashboard', "Main@getDashboard");
$route->get('/login/signOut', "Login@signOut");