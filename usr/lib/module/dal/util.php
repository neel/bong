<?php
namespace \DB;
class Util{
	static function hstore($assoc=array()){
		$rets = array();
		foreach($assoc as $key => $val){
			if(is_string($key)){
				$first_char = $key[0];
				if(!is_numeric($first_char)){
					$rets[] = "{$key}=>\"{$val}\"";
				}
			}
		}
		return implode(',', $rets);
	}
}
?>