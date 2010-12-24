<?php
/**
 * Factory Class Implements Factory Pattern
 * If the Class is Singleton its instance() method is called so even if Factory is used to produce a Singleton
 * Object Only 1 Instance is returned
 * To make your Class \b producable through Factory You need to register it in the Factory First
 * to Register do Factory::register('ClassName')
 * If you want to SubClass Factory say EngineFactory that Groups Only Engines or RouterFactory Similerly
 * you must implement group() function that just returns groupName as String e.g. return "engine" in EngineFactory::group()
 * @example Factory::produce('ClassName');
 * @author Neel Basu
 */
class Factory{
	private static $dict = array();
	
	public static function register($className=null){
		$producableClassName = $className;
		$key = $className;
		if(self::isGroup()){
			if(strpos($key, '.') === false){
				$key = static::group().".$key";
			}
			if(strpos($producableClassName, '.') >= 0){
				$producableClassName = substr($producableClassName, strpos($producableClassName, '.'));
			}
		}
		if(class_exists($producableClassName)){
			self::$dict[$key] = new ReflectionClass($className);
		}
		
	}
	public static function unregister($name){
		if(array_key_exists($name, self::$name)){
			self::$dict[$name] = null;
		}
	}
	/*overridable*/
	protected static function group(){}
	private static function isGroup(){
		return strlen(static::group()) > 0;
	}
	public static function produce($name, $args=null){
		$key = self::isGroup() ? static::group().'.'.$name : $name;
		if(array_key_exists($key, self::$dict)){
			if(self::$dict[$key]->isSubclassOf("Singleton")){
				//Call the instance()Static Method to create an object instead 
				if(!$args)
					return self::$dict[$key]->getMethod('instance')->invode();
				elseif(is_array($args))
					return self::$dict[$key]->getMethod('instance')->invodeArgs($args);
				else
					return self::$dict[$key]->getMethod('instance')->invodeArgs(array($args));
			}else{
				if(!$args)
					return self::$dict[$key]->newInstance();
				elseif(is_array($args))
					return self::$dict[$key]->newInstanceArgs($args);
				else
					return self::$dict[$key]->newInstanceArgs(array($args));
			}
		}
		
	}
}
?>