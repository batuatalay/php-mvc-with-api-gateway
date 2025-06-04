<?php 
require_once BASE . "/helper/session.helper.php";

#[Prefix('login')]
class Login extends SimpleController{

    public static function loginPage() {
        $userData = [
            'id' => 1,
            'name' => 'Admin User',
            'username' => 'admin',
            'user_role' => 'admin',
            'permissions' => ['read', 'write', 'delete']
        ];
        
        SessionHelper::createUserSession($userData);
    }

    public function signOut(){
        SessionHelper::destroySession();
        header("Location: /login");
        exit;
    }

    public static function loginCheck() {
        return SessionHelper::isLoggedIn();
    }

    public static function changeUser() {
        SessionHelper::changeUser();
        header("Location: /main");
        exit;
    }
}