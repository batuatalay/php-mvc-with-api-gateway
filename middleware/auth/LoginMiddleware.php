<?php
require_once BASE . "/helper/session.helper.php";

#[Attribute]
class LoginAttribute {
    public function __construct() {}

    public function handle($next, $params) {
        if (!SessionHelper::isLoggedIn()) {
            header("Location: /login");
            exit;
        }
        echo "LoginMiddleware executed!<br>";
        return $next($params);
    }
} 