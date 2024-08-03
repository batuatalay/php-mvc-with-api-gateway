<?php 
spl_autoload_register( function($className) {
    if($className == "SimpleController") {
        $fullPath = "simple.controller.php";
    }else {
        $extension = ".controller.php";
        $fullPath = strtolower($className) . $extension;
    }
    require_once $fullPath;
});

class Login extends SimpleController{

    public static function loginPage() {
        if(self::loginCheck()) {
            header("Location: /main");
        } else {
            self::view('login', 'index', '');
        }
    }

    public function signIn ($data) {
       
    }
    
    public function signOut(){
        setcookie("uzmUsername", "", time() - 3600, "/");
        header("Location: /login");
    }

    public function loginCheck() {
        
    }
}