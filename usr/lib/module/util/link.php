<?php
class Link{
	public static function self(){
		return $_SERVER['REQUEST_URI'];
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
			$link .=  '?'.http_build_query($args);
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
