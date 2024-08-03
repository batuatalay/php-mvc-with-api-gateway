<?php 
spl_autoload_register( function($className) {
    if($className == "SimpleController") {
        $fullPath = "simple.controller.php";
    } else {
        $extension = ".controller.php";
        $fullPath = strtolower($className) . $extension;
    }
    require_once $fullPath;
});
/**
 * for API usege only you can add only echo
 * 
 */
class Main extends SimpleController{

    public function __construct($arr = []) {
        if(!Login::loginCheck() && 1!=1) { // 1==1 for bypass login
            header("Location: /login");
        }
    }

    public static function getMainPage() {
        echo 'Main Page';exit;
    }
}