<?php
namespace Structs\Admin;
abstract class Controller extends Struct{
	const ApplicationController = 0x0A1;
	const SpiritControler = 0x0A2;
	
	protected $_type = null;
	
	protected $_name;
	protected $_project;
	protected $_className;
	protected $_methods = array();
	protected $_endLine;
	protected $_filePath;
	
	public function type(){
		return $this->_type;
	}
	public function project(){
		return $this->_project;
	}
	public function name(){
		return $this->_name;
	}
	public function className(){
		return $this->_className;
	}
	public function methods(){
		return $this->_methods;
	}
	public function filePath(){
		return $this->_filePath;
	}
	public function endLine(){
		return $this->_endLine;
	}
	/**
	 * @param Method $method
	 */
	public function addMethod($method){
		$this->_methods[] = $method;
	}
	/**
	 * @param ReflectionClass $reflection
	 */
	protected function _createFromReflection($reflection){
		$this->_className = $reflection->getName();
		$this->_endLine = $reflection->getEndLine();
		$this->_filePath = $reflection->getFileName();
		foreach($reflection->getMethods() as $method){
			//Method::create($this, $method);
			$this->addMethod(Method::create($this, $method));
		}
	}
	protected function _create($className, $methods=array()){
		$this->_className = $className;
		$this->_methods = $methods;
	}
	public function instance($project, $classNameOrReflection, $methods=array()){
		$this->_project = $project;
		if(is_object($classNameOrReflection) && get_class($classNameOrReflection) == 'ReflectionClass'){
			$this->_createFromReflection($classNameOrReflection);
		}else{
			$this->_create($classNameOrReflection, $methods);
		}
	}
	public function generate(){
		$methodStr = "";
		foreach($this->_methods as $method){
			$methodStr .= $method->generate(true);
		}
		$classStr = <<<CLASSSTR
<?php
/**
 * \controller {$this->name()}
 */
class {$this->className()} extends BongAppController{
$methodStr
}
?>
CLASSSTR;
		$fp = fopen($this->filePath(), 'w');
		fwrite($fp, $classStr, strlen($classStr));
		fclose($fp);
	}
}
?>
