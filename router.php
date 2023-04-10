<?php
	class BaseRouter {
		private $uri;
		private $endpoints = []; 
		
		public function __construct() {
			$dirName = dirname($_SERVER['SCRIPT_NAME']);
			$baseName = basename($_SERVER['SCRIPT_NAME']);
			$this->uri = $_SERVER["REQUEST_URI"];
			include_once "endpoints.php";
			$this->endpoints = $allEndPoints;
		}

		public function run($method = "GET", $url, $callback, $parameters = null) {
			if($method == $_SERVER["REQUEST_METHOD"]) {
				$endpoints = $this->endpoints[$method];
				foreach ($endpoints as $endpoint) {
					if ($url === $endpoint) {
						$this->$method($url, $callback, $parameters);
					}
				}
			}
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
		public function delete($url, $callback, $parameters = null) {
			$parameters = $this->getParamaters($url);
			if(!empty($parameters)) {
				$url = str_replace($parameters[0], $parameters[1], $url);
				$parameters = $parameters[1];
			}
			if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
				$this->runFunction($url, $callback, $parameters);
			}
		}
		
		private function runFunction($url, $callback, $parameters = null) {
			if (preg_match('@^' . $url . '$@', $this->uri)) {
				if (is_callable($callback)) {
					call_user_func($callback, $parameters);
					return 0;
				};
				$controller = explode("@", $callback);
				$controllerFile = BASE . '/controller/' . strtolower($controller[0]) . ".controller.php";

				if(file_exists($controllerFile)) {
					require $controllerFile;
					call_user_func([new $controller[0], $controller[1]], $parameters);
				}
			}
		}

		private function getParamaters($url){
			$data = explode('/', $this->uri);
			$param = explode('/', $url);
			foreach($param as $key => $pr) {
				if(strpos($pr, "#") !== false) {
					return [$pr, $data[$key]];
				}
			}
		}
	}
