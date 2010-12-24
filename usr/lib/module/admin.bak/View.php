<?php
namespace Structs\Admin;
abstract class View extends Struct{
	protected $_method;
	protected $_name;
	
	public function name(){
		return $this->_name;
	}
	public function method(){
		return $this->_method;
	}
	abstract public function filePath(); 
	public function generate(){
		$viewStr = <<<VIEWSTR
<?php
/**
 * \view {$this->_method->controller()->className()}:{$this->_method->name()}:{$this->name()}
 */
?>
VIEWSTR;
		$fp = fopen($this->filePath(), 'w');
		fwrite($fp, $viewStr, strlen($viewStr));
		fclose($fp);
	}
	public function _create($name){
		$this->_name = $name;
	}
	public function _createFromReflection(/*$reflection*/){}
	public function instance($method, $name){
		$this->_method = $method;
		$this->_create($name);
	}
}
?>