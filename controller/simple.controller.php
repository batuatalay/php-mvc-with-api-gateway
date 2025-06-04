<?php
require_once BASE . "/middleware/common.middleware.php";
/**
 * Base Controller Class
 */
class SimpleController
{
	public static function view($file, $view, $args = null) {
        include BASE . "/model/language.model.php";
    	if(file_exists(BASE . "/view/" .$file. "/" . $view . ".php")) {
    		include BASE . "/view/" .$file. "/" . $view . ".php";
    	}
    }
    public static function header($title = null) {
    	if(file_exists(BASE . "/view/static/header.php")) {
    		include BASE . "/view/static/header.php";
    	}
    }

    public static function footer($site, $script = null) {
    	if(file_exists(BASE . "/view/static/footer.php")) {
    		include BASE . "/view/static/footer.php";
    	}
    }

    public static function isLogin($location) {
        if (isset($_SESSION[$location])) {
            return $_SESSION[$location];
        } else {
            return false;
        }
    }
    public static function getFromAPI($url) {
        $ch = curl_init(); 
        $url = ENV . $url;
        curl_setopt($ch,CURLOPT_URL,$url); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 
        
        $output=curl_exec($ch);
        curl_close($ch); 
        return json_decode($output); 
    }
    public static function returnData($response) {
        echo json_encode(['status' => $response['code'], 'message' => $response['message']]);
    }

    public static function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    public static function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
