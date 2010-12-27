<?php
namespace Structs\Admin;
abstract class View extends Struct{
	protected $_method;
	protected $_name;
	protected $_filePath;
	
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
 * \View {$this->_method->controller()->className()}:{$this->_method->name()}:{$this->name()}
 */
?>
VIEWSTR;
		$baseDir = pathinfo($this->filePath(), PATHINFO_DIRNAME);
		if(!is_dir($baseDir)){
			if(!@mkdir($baseDir, 0777, true)){
				return false;
			}
		}
		$fp = @fopen($this->filePath(), 'w');
		if(!$fp){
			return false;
		}
		fwrite($fp, $viewStr, strlen($viewStr));
		fclose($fp);
		return true;
	}
	public function _create($name){
		$this->_name = $name;
	}
	public function _createFromReflection(/*$reflection*/){}
	public function instance($method, $name){
		$this->_method = $method;
		$this->_create($name);
		$this->_filePath = $this->filePath();
	}
	public function source(){
		return file_get_contents($this->_filePath);
	}
}
?>
