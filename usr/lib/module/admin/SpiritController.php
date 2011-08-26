<?php
namespace Structs\Admin;
final class SpiritController extends Controller{
	private $_binding = null;
	private $_serialization = null;
	private $_feeder = null;
	private $_session = null;
	
	protected function _createFromReflection($reflection){
		$_parts = explode('Abstractor', $reflection->getName());
		$this->_name = $_parts[0];
		parent::_createFromReflection($reflection);
		foreach($reflection->getMethods() as $method){
			if($method->getDeclaringClass()->getName() == $reflection->getName())
				$this->addMethod(SpiritMethod::create($this, $method));
		}
		$interfaces = $reflection->getInterfaceNames();
		foreach($interfaces as $interface){
			switch(true){
				case preg_match('~Bound$~', $interface) > 0:
					$this->_binding = $interface;
					break;
				case preg_match('~XDO$~', $interface) > 0:
					$this->_serialization = $interface;
					break;
				case preg_match('~Feeded$~', $interface) > 0:
					$this->_feeder = $interface;
					break;
				case preg_match('~Spirit$~', $interface) > 0:
					$this->_session = $interface;
					break;
			}
		}
	}
	protected function _create($name, $methods=array()){
		$this->_name = $name;
		parent::_create($name.'Abstractor', $methods);
		$this->_filePath = \Path::instance()->evaluate(':'.$this->project()->name().".*".$this->name().".@abstractor.php");;
	}
	/**
	 * 
	 * @param Project $project
	 * @param [string|ReflectionClass] $reflectionOrName
	 * @param array<Method> $methods
	 */
	public function instance($project, $reflectionOrName, $methods=array()){
		$this->_type = Controller::SpiritControler;
		$this->_project = $project;
		if(is_string($reflectionOrName)){
			$name = $reflectionOrName;
			$this->_create($name, $methods);
		}else if(is_object($reflectionOrName) && get_class($reflectionOrName) == 'ReflectionClass'){
			$reflection = $reflectionOrName;
			$this->_createFromReflection($reflection);
		}
		//$this->_project->addController($this);
	}
	public function generate(){
		$dirPath = pathinfo($this->_filePath, PATHINFO_DIRNAME);
		if(!file_exists($dirPath)){
			mkdir($dirPath, 0777);
		}
		if(!file_exists($dirPath."/presentation")){
			mkdir($dirPath."/presentation", 0777);
		}
		parent::generate(false);
	}
	public function binding(){
		return $this->_binding;
	}
	public function serialization(){
		return $this->_serialization;
	}
	public function feeder(){
		return $this->_feeder;
	}
	public function session(){
		return $this->_session;
	}
	public function setBinding($binding){
		$this->_binding = $binding;
	}
	public function setSerialization($serialization){
		$this->_serialization = $serialization;
	}
	public function setFeeder($feeder){
		$this->_feeder = $feeder;
	}
	public function setSession($session){
		$this->_session = $session;
	}
	protected function interfaces(){
		return array($this->binding(), $this->serialization(), $this->feeder(), $this->session());
	}
	protected function controllerBase(){
		return 'SpiritAbstractor';
	}
}
?>
