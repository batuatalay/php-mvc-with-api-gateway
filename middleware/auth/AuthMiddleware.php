<?php
require_once BASE . "/helper/session.helper.php";

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class Auth {
    public function __construct(
        public readonly string $role = 'user'
    ) {}

    public function handle($next, $params) {
        if (SessionHelper::getUserRole() == $this->role) {
            echo "Admin user detected!<br>";
        } else {
            echo "Regular user detected!<br>";
        }
        
        return $next($params);
    }
} 