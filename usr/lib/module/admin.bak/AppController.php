<?php
namespace Structs\Admin;
final class AppController extends Controller{
	protected function _createFromReflection($reflection){
		$this->_name = pathinfo($reflection->getFileName(), PATHINFO_FILENAME);
		parent::_createFromReflection($reflection);
	}
	
	protected function _create($name, $methods=array()){
		parent::_create($name.'Controller', $methods);
		$this->_filePath = \Path::instance()->evaluate(':'.$this->project()->name().".apps.controller.@$name.php");;
	}
	/**
	 * 
	 * @param Project $project
	 * @param [string|ReflectionClass] $reflectionOrName
	 * @param array<Method> $methods
	 */
	public function instance($project, $reflectionOrName, $methods=array()){
		$this->_type = Controller::ApplicationController;
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
