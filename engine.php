<?php
require_once("core/utility.php");
require_once("core/APIKey.php");
require_once("core/RiotAPI.php");
require_once("home/home.php");


class Engine {

	public static $errorFlag = false;
	public static $response = null;

	//prints back ajax
	static function s_print($e, $code){
		//$code = ($code) ? $code : "400";
		http_response_code($code);
		echo json_encode($e);
	}

	//gets ajax data
	static function input(){
		return json_decode(file_get_contents("php://input"));
	}

	//routes the incoming instructions
	static function func_route($class, $function, $data){
		//creats object of specified class
		$object = new $class();
		//load the object with $data
		$object->load($data);

		//attempt to call specified function, if it has result return it, else return 0
		if($out = call_user_func(array($object, $function))){	
			self::s_print($out, self::$response['response_code']);
		} 
		//when we should be getting something other than 200
		else {
			self::s_print(0, self::$response['response_code']);
		}
	}
}

if ( $_GET['method'] == 'route'){
	//get post data
	$data = Engine::input();

	//perform instructions
	Engine::func_route($data->class, $data->function, $data->data);
}

?>