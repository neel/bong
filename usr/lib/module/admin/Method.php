<?php
namespace Structs\Admin;
class Method extends Struct{
	const ControllerMethod = 0x0012;
	const SpiritMethod = 0x0024;
	
	private $_controller;
	private $_name;
	private $_public;
	private $_arguments = array();
	private $_views = array();
	private $_hasDefaultView = false;
	protected $_type;
	protected $_startLine;
	protected $_endLine;

	public function type(){
		return $this->_type;
	}
	/**
	 * @param Controller $controller
	 * @param [string|ReflectionMethod] $reflectionOrName
	 * @param bool $public
	 * @param array<Argument> $args
	 */
	public function instance($controller, $reflectionOrName, $public=null, $args=array()){
		$this->_controller = $controller;
		if(is_string($reflectionOrName)){
			$name = $reflectionOrName;
			$this->_create($name, $public, $args);
		}else if(is_object($reflectionOrName) && get_class($reflectionOrName) == 'ReflectionMethod'){
			$reflection = $reflectionOrName;
			$this->_createFromReflection($reflection);
			$this->_startLine = $reflection->getStartLine();
			$this->_endLine = $reflection->getEndLine();
		}
		//$this->_controller->addMethod($this);
	}
	public function controller(){
		return $this->_controller;
	}	
	/**
	 * @load
	 * @param ReflectionMethod $reflection
	 */
	protected function _createFromReflection($reflection){
		$this->_name = $reflection->getName();
		$this->_public = $reflection->isPublic();
		foreach($reflection->getParameters() as $paramReflection){
			//Argument::create($this, $paramReflection);
			$this->addArgument(Argument::create($this, $paramReflection));
		}
	}
	/**
	 * @param Argument $argument
	 */
	public function addArgument($argument){
		$this->_arguments[] = $argument;
	}
	public function name(){
		return $this->_name;
	}
	public function arguments(){
		return $this->_arguments;
	}
	public function isPublic(){
		return $this->_public;
	}
	/**
	 * @store
	 * @param string $name
	 * @param bool $public
	 * @param array<Argument> $args
	 */
	private function _create($name, $public, $args){
		$this->_name = $name;
		$this->_public = $public;
		$this->_arguments = $args;
	}
	/**
	 * @param View $view
	 */
	public function addView($view){
		$this->_views[] = $view;
	}
	public function hasDefaultView(){
		return $this->_hasDefaultView;
	}
	public function setHasDefaultView(){
		$this->_hasDefaultView = true;
	}
	public function views(){
		return $this->_views;
	}
	public function numViews(){
		return count($this->_views);
	}
	public function viewByName($name){
		foreach($this->_views as $view){
			if($view->name() == $name)
				return $view;
		}
		return false;
	}
	public function code(){
		$length = ($this->_endLine - $this->_startLine);
		$fd = \fopen($this->_controller->filePath(), 'r');
		\fseekline($fd, $this->_startLine-1);
		$buffer = "";
		$c = 0;
		while($c <= $length){
			$buffer .= fgets($fd);
			++$c;
		}
		return rtrim($buffer);
	}
	public function spaceToTab($text){
		return preg_replace("/\G {2}/","\t$1", $text);
	}
	public function setCode($code){
		$size = filesize($this->_controller->filePath());
		$fd = @fopen($this->_controller->filePath(), 'rb');
		if(!$fd){
			return false;
		}
		//"php://temp/maxmemory:$size"
		$backup = fopen('php://temp/maxmemory:$size', 'w');
		$c = 0;
		while($c < $this->_startLine-1){
			fwrite($backup, fgets($fd));
			++$c;
		}
		//{ Write the Code
		$codeFd = fopen('data:text/plain,'.$code, 'rb');
		while(($line = fgets($codeFd)) !== false){
			fwrite($backup, $this->spaceToTab($line));
		}
		//}
		fwrite($backup, "\n");
		\fseekline($fd, $this->_endLine);
		while(!feof($fd)){
			fwrite($backup, fgets($fd));
		}
		fclose($fd);
		$fd = @fopen($this->_controller->filePath(), 'w');
		if(!$fd){
			return false;
		}
		fseek($fd, 0);
		fseek($backup, 0);
		while(!feof($backup)){
			fwrite($fd, fread($backup, 1), 1);
		}
		fclose($fd);
		fclose($backup);
		return true;
	}
}
?>
