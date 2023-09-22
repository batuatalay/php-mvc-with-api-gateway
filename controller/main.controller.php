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
class Main extends SimpleController{

	public static function getMainPage() {
		//Test::testFunction1();
		self::view('main', 'main', '');
	}

	public static function getIndex() {
		echo 'index Page' . PHP_EOL;
		Test::testFunction2();
		self::view('main', 'index', '');

	}
}