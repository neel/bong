<?php
namespace Structs\Admin;
final class Project extends Struct{
	private $_name;
	private $_dir;
	private $_controllers = array();
	private $_spirits = array();
	private $_resources = array();
	
	private function _create($name, $dir){
		$this->_name = $name;
		$this->_dir = $dir;
	}
	private function _createFromReflection($projectName){
		$this->_name = $projectName;
		$this->_dir = \Fstab::instance()->projectDirectory($projectName);
		$controllerBasePath = rtrim(\Path::instance()->evaluate(":{$this->_name}.apps.controller"), "/\\");
		foreach(glob($controllerBasePath."/*.php") as $filePath){
			$fileName = pathinfo($filePath, PATHINFO_FILENAME);
			$controllerClassName = $fileName.'Controller';
			if(!class_exists($controllerClassName)){
				require($filePath);
			}
			if(class_exists($controllerClassName)){
				$reflection = new \ReflectionClass($controllerClassName);
				$this->_controllers[] = AppController::create($this, $reflection);
			}
		}
		$spiritBasePath = rtrim(\Path::instance()->evaluate(":{$this->_name}.spiritPath"), "/\\");

		foreach(glob($spiritBasePath."/*", GLOB_ONLYDIR) as $dirName){
			$spiritName = pathinfo($dirName, PATHINFO_FILENAME);
			$spiritFileName = rtrim($dirName, "/\\").'/abstractor.php';
			$spiritAbstractorClassName = $spiritName.'Abstractor';

			if(!class_exists($spiritAbstractorClassName) && file_exists($spiritFileName)){
				require($spiritFileName);
			}

			if(class_exists($spiritAbstractorClassName)){
				$reflection = new \ReflectionClass($spiritAbstractorClassName);
				$this->_spirits[] = SpiritController::create($this, $reflection);
			}
		}
	}
	public function instance($name, $dir = null){
		if(is_null($dir)){
			return $this->_createFromReflection($name);
		}else{
			return $this->_create($name, $dir);
		}
	}
	public function addController($controller){
		$this->_controllers[] = $controller;
	}
	public function addSpirit($spirit){
		$this->_spirits[] = $spirit;
	}
	public function generate(){
		$projectLocation = Fstab::instance()->projectLocation($this->_name);
		if(!opendir($projectLocation)){
			mkdir($projectLocation, 0777);
		}
		foreach($this->_controllers as $controller){
			$controller->generate();
		}
		foreach($this->_spirits as $spirit){
			$spirit->generate();
		}
	}
	public function name(){
		return $this->_name;
	}
	public function controllers(){
		return $this->_controllers;
	}
}
?>
