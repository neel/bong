<?php
//namespace Bong\Util;
abstract class Singleton{
	private static $__instances = array();

	private function __clone(){}
	static public function instance(){
		$called_class = get_called_class();
		if(array_key_exists($called_class, self::$__instances)){
			return self::$__instances[$called_class];
		}else{
			$argc = func_num_args();
			$argv = func_get_args();
			$reflection = new ReflectionClass($called_class);
			if($argc > 0){
				self::$__instances[$called_class] = $reflection->newInstanceArgs($argv);
			}else{
				self::$__instances[$called_class] = $reflection->newInstance();
			}
			return self::$__instances[$called_class];
		}
	}
}
?>