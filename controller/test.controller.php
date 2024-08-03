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
 * 
 */
class Test extends SimpleController{

	public static function testFunction1 () {
		echo PHP_EOL . 'test Function 1';
	
	}

	public static function testFunction2() {
		echo PHP_EOL . 'test Function 2';
		
	}
}