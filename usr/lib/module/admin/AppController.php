<?php
namespace Structs\Admin;
final class AppController extends Controller{
	private $_layout = null;
	private $_params = null;
	private $_view = null;
	
	protected function _createFromReflection($reflection){
		$this->_name = pathinfo($reflection->getFileName(), PATHINFO_FILENAME);
		parent::_createFromReflection($reflection);
		foreach($reflection->getMethods() as $method){
			if($method->getDeclaringClass()->getName() == $reflection->getName())
				$this->addMethod(ControllerMethod::create($this, $method));
		}
		$layoutPath = \Path::instance()->evaluate(':'.$this->project()->name().".apps.layout.+{$this->name()}.@layout.php");//Application Layout
		$paramsPath = \Path::instance()->evaluate(':'.$this->project()->name().".apps.layout.+{$this->name()}.@params.php");//Application Params
		$viewPath = \Path::instance()->evaluate(':'.$this->project()->name().".apps.view.+{$this->name()}.@view.php");//Application View
		if(file_exists($layoutPath)){
			$this->_layout = Layout::create($layoutPath);
		}
		if(file_exists($paramsPath)){
			$this->_params = Params::create($paramsPath);
		}
		if(file_exists($viewPath)){
			$this->_view = ControllerAppView::create($this);
		}
	}
	
	protected function _create($name, $methods=array()){
		$this->_name = $name;
		parent::_create($name.'Controller', $methods);
		$this->_filePath = \Path::instance()->evaluate(':'.$this->project()->name().".apps.controller.@$name.php");
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
	protected function interfaces(){
		return array();
	}
	protected function controllerBase(){
		return 'BongAppController';
	}
	public function genLayout(){
		if(!$this->_layout){
			$viewPath = \Path::instance()->evaluate(':'.$this->project()->name().".apps.layout.+{$this->name()}.@layout.php");
			$this->_layout = ControllerLayout::create($viewPath.'/layout.php');
			return $this->_layout->generate();
		}
		return false;
	}
	public function genParams(){
		if(!$this->_params){
			$viewPath = \Path::instance()->evaluate(':'.$this->project()->name().".apps.layout.+{$this->name()}.@params.php");
			$this->_params = Params::create($viewPath.'/params.php');
			return $this->_params->generate();
		}
		return false;
	}
	public function layout(){
		return $this->_layout;
	}
	public function params(){
		return $this->_params;
	}
	public function view(){
		return $this->_view;
	}
}
?>
