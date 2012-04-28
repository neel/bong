<?php
final class SpiritEngineProxy extends ContentEngine{
	public function run(){
		$spiritEngine = $this->executeLogic();
		$this->responseBuffer = $spiritEngine->response();
	}
	public function executeLogic(){
		require_once($this->model($__i_dmp));
		$modelName = $this->modelName();
		$modelReflection = new ReflectionClass($modelName);
		$model = $modelReflection->newInstance();
		$controller = null;
		if(isset($this->navigation->controllerName)){
			require($this->controller());
			$controllerName = ucfirst($this->navigation->controllerName.'Controller');
			$controllerReflection = new ReflectionClass($controllerName);
			$controller = $controllerReflection->newInstanceArgs(array($model));
			if(isset($this->navigation->controllerMethodName)){
				$controllerReflectionObject = new ReflectionObject($controller);
				try{
					$methodReflection = $controllerReflectionObject->getMethod($this->navigation->controllerMethodName);
				}catch(ReflectionException $ex){
					throw new MethodNotFoundException($this->navigation->controllerMethodName, $this->navigation->controllerName, $this->projectName);
				}
				if($methodReflection){
					$methodReflection->invokeArgs($controller, $this->navigation->controllerMethodArgs);
				}
			}
		}
		$spiritEngine = new SpiritEngine($controller);
		if(isset($this->navigation->spiritInstanceId)){
			$spiritEngine->setActiveInstance($this->navigation->spiritInstanceId);
		}
		$spiritEngine->spirit($this->navigation->spiritName);
		if(isset($this->navigation->spiritInstanceId)){
			$spiritEngine->setActiveInstance($this->navigation->spiritInstanceId);
		}
		if($this->navigation->methodName)
			$this->responseBuffer = $spiritEngine->run($this->navigation->spiritName, $this->navigation->methodName, $this->navigation->args);
		$this->storeXDO($spiritEngine->xdo());
		return $spiritEngine;
	}
	private function model(&$index){
		$models = array(
			Path::instance()->currentProject('apps.model.+&controller.-&method.php'),//Application Model
			Path::instance()->currentProject('apps.model.+&controller.@model.php'),//Controller Model
			Path::instance()->currentProject('common.@model.php'),//Project Model
			Path::instance()->evaluate('share.apps.@model.php')//Bong Model
		);
		foreach($models as $i => $model){
			if(file_exists($model)){
				$index = $i;
				return $model;
			}
		}
	}
	private function modelName(){
		$index = -1;
		$modelPath = $this->model($index);
		switch($index){
			case 0:
				$modelName = ucfirst($this->navigation->controllerName).ucfirst($this->navigation->methodName).'Model'; 
			break;
			case 1:
				$modelName = ucfirst($this->navigation->controllerName).'Model'; 
			break;
			case 2:
				$modelName = ucfirst(Runtime::currentProject()->name).'Model'; 
			break;
			case 3:
				$modelName = 'BongAppModel';
			break;
			default:
				//TODO Handle Error
		}
		return $modelName;
	}
	private function controller(){
		return Path::instance()->currentProject("apps.+{$this->navigation->controllerName}.@{$this->navigation->controllerName}.php");
	}
}
?>
