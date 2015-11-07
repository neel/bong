<?php
class Link{
	public static function self(){
		$request_uri = $_SERVER['REQUEST_URI'];
		$i = strpos($request_uri, '?');
		if($i > 0){
			$request_uri = substr_replace($request_uri, "", $i);
		}
		return $request_uri;
	}
	public static function uri(){
		return $_SERVER['PATH_INFO'];
	}
	public static function base(){
		return pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
	}
	public static function create($controller, $method, $args=null, $project=null, $absolute=false){
		$link = "/$controller/$method";
		if(is_array($args)){
			$app_params = array();
			$get_params = array();
			$arg_indexes = array_keys($args);
			foreach($arg_indexes as $i => $key){
				if($key == (string)$i){
					//numerical index
					//App param
					$app_params[] = $args[$key];
				}else{
					//associative index
					//GET Param
					$get_params[$key] = $args[$key];
				}
			}
			if(count($app_params) > 0)
				$link .=  '/'.implode('/', $app_params);
			if(count($get_params) > 0)
				$link .=  '?'.http_build_query($get_params);
		}
		if($project){
			$link = "/~$project".$link;
		}else{
			if($absolute){
				$link = "/~".Runtime::currentProject()->name.$link;
			}
		}
		return '/'.trim(trim(self::base(), '/')."$link", '/');
	}
}
?>
