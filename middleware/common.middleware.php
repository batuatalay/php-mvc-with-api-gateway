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

#[Attribute]
class LoginAttribute {
    public function __construct() {}

    public function handle($next, $params) {
        echo "LoginMiddleware executed!<br>";
        if (!SessionHelper::isLoggedIn()) {
            header("Location: /login");
            exit;
        }
        return $next($params);
    }
}

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

interface MiddlewareInterface {
    public function handle($request, $next);
}

// 3. Middleware Implementations

class AuthMiddleware implements MiddlewareInterface {
    public function handle($request, $next) {
        $auth = $request['middleware_data']['auth'] ?? null;
        
        if (!$auth) {
            return $next($request);
        }
        
        // Session kontrolü
        if (!isset($_SESSION['user_id']) && $auth->required) {
            http_response_code(401);
            header('Location: /login');
            exit('Authentication required');
        }
        
        // Role kontrolü
        if ($auth->role !== 'user' && (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $auth->role)) {
            http_response_code(403);
            exit('Insufficient permissions');
        }
        
        return $next($request);
    }
}

class LoginMiddleware implements MiddlewareInterface {
    public function handle($request, $next) {
        $login = $request['middleware_data']['login'] ?? null;
        
        if (!$login) {
            return $next($request);
        }
        
        echo "LoginMiddleware executed!<br>";
        
        return $next($request);
    }
}
class AdminMiddleware implements MiddlewareInterface {
    public function handle($request, $next) {
        $admin = $request['middleware_data']['admin'] ?? null;
        
        if (!$admin) {
            return $next($request);
        }
        
        echo "AdminMiddleware executed!<br>";
        
        return $next($request);
    }
}

class CacheMiddleware implements MiddlewareInterface {
    public function handle($request, $next) {
        $cache = $request['middleware_data']['cache'] ?? null;
        
        if (!$cache) {
            return $next($request);
        }
        
        $cacheKey = $cache->key ?: md5($_SERVER['REQUEST_URI']);
        $cacheFile = __DIR__ . "/cache/{$cacheKey}.cache";
        
        // Cache'den oku
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cache->ttl) {
            echo file_get_contents($cacheFile) . "<br>";
            return null; // Response'u durdur
        }
        
        // Output buffering başlat
        ob_start();
        $response = $next($request);
        $output = ob_get_contents();
        ob_end_clean();
        
        // Cache'e yaz
        if (!is_dir(dirname($cacheFile))) {
            mkdir(dirname($cacheFile), 0755, true);
        }
        file_put_contents($cacheFile, $output);
        
        echo $output . "<br>";
        return $response;
    }
}

// 4. Middleware Manager

class MiddlewareManager {
    private static $middlewares = [
        'auth' => AuthMiddleware::class,
        'cache' => CacheMiddleware::class,
        'login' => LoginMiddleware::class,
        'admin' => AdminMiddleware::class
    ];
    
    public static function process($controllerName, $methodName, $context, $params) {
        $reflection = new ReflectionClass($controllerName);
        $method = $reflection->getMethod($methodName);
        
        // Metod üzerindeki middleware'leri al
        $attributes = $method->getAttributes();
        
        foreach($attributes as $attribute) {
            $middlewareInstance = $attribute->newInstance();
            if(method_exists($middlewareInstance, 'handle')) {
                $params = $middlewareInstance->handle(
                    function($p) use ($controllerName, $methodName) {
                        return call_user_func([new $controllerName, $methodName], $p);
                    },
                    $params
                );
            }
        }
        
        return $params;
    }
}
?>