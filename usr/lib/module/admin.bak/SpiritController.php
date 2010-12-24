<?php
namespace Structs\Admin;
final class SpiritController extends Controller {
	protected function _createFromReflection($reflection){
		$_parts = explode('Abstractor', $reflection->getName());
		$this->_name = $_parts[0];
		parent::_createFromReflection($reflection);
	}
	protected function _create($name, $methods=array()){
		$this->_name = $name;
		parent::_create($name.'Abstractor', $methods);
		$this->_filePath = Path::instance()->evaluate(':'.$this->project()->name().".apps.spirit.@$name.php");;
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
}
?>
