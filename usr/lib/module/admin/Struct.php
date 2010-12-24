<?php
namespace Structs\Admin;
abstract class Struct{
	public static function create(){
		$args = func_get_args();
		$className = get_called_class();
		$o = new $className;
		call_user_func_array(array($o, 'instance'), $args);
		return $o;
	}
}
?>
