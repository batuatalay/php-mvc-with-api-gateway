<?php
	class BaseRouter {
		protected $uri;
		protected $endpoints = []; 
		
		public function __construct() {
			if(DEVELOPMENT != true && 
				ENV != "http://localhost/" && 
				($_SERVER['HTTP_HOST'] != ENV || $_SERVER['HTTP_HOST'] != "localhost")) {
				echo 'test<br>';
	            exit;
	        }
			$dirName = dirname($_SERVER['SCRIPT_NAME']);
			$baseName = basename($_SERVER['SCRIPT_NAME']);
			$this->uri = $_SERVER["REQUEST_URI"];
		}

		public function get($url, $callback, $parameters = null) {
			if ($_SERVER["REQUEST_METHOD"] == "GET") {
				$parameters = $this->getParamaters($url);
				if(!empty($parameters)) {
					$url = str_replace($parameters[0], $parameters[1], $url);
					$parameters = $parameters[1];
				}
				$this->runFunction($url, $callback, $parameters);
			}
		}

		public function post($url, $callback, $parameters = null) {
			$parameters = $this->getParamaters($url);
			if(!empty($parameters)) {
				$url = str_replace($parameters[0], $parameters[1], $url);
				$parameters = $parameters[1];
			}
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$parameters = empty($parameters) ? $_POST : $parameters;
				$this->runFunction($url, $callback, $parameters);
			}
		}
		
		protected function getParamaters($url) {
			if (!$this->uri) return null;
			$data = explode('/', $this->uri);
			$param = explode('/', $url);
			foreach($param as $key => $pr) {
				if(strpos($pr, "#") !== false) {
					return [$pr, $data[$key]];
				}
			}
			return null;
		}

		protected function runFunction($url, $callback, $parameters = null) {
			if (preg_match('@^' . $url . '$@', $this->uri)) {
				if (is_callable($callback)) {
					call_user_func($callback, $parameters);
					return 0;
				}
				
				$controller = explode("@", $callback);
				$controllerName = $controller[0];
				$methodName = $controller[1];
				
				$controllerFile = BASE . '/controller/' . strtolower($controllerName) . ".controller.php";
				if(file_exists($controllerFile)) {
					require_once $controllerFile;
					
					$reflection = new ReflectionClass($controllerName);
					$method = $reflection->getMethod($methodName);
					
					// Get all attributes
					$attributes = $method->getAttributes();
					
					if (!empty($attributes)) {
						// Execute middleware for each attribute
						$next = function($p) use ($controllerName, $methodName) {
							return call_user_func([new $controllerName, $methodName], $p);
						};

						foreach ($attributes as $attribute) {
							$middlewareInstance = $attribute->newInstance();
							if (method_exists($middlewareInstance, 'handle')) {
								$next = function($p) use ($middlewareInstance, $next) {
									return $middlewareInstance->handle($next, $p);
								};
							}
						}

						return $next($parameters);
					}
					
					// If no attributes exist, execute the method directly
					return call_user_func([new $controllerName, $methodName], $parameters);
				}
			}
		}
	}
