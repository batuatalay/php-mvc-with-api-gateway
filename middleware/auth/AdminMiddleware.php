<?php
require_once BASE . "/helper/session.helper.php";

#[Attribute]
class AdminAttribute {
    public function __construct() {}

    public function handle($next, $params) {
        if (!SessionHelper::isLoggedIn()) {
            header("Location: /login");
            exit;
        }
        if (!SessionHelper::isAdmin()) {
            http_response_code(403);
            header("Location: /main");
            exit("Access denied. Admin privileges required.");
        }
        echo "AdminMiddleware executed!<br>";
        return $next($params);
    }
} 