<?php
final class SpiritEngineProxy extends ContentEngine{
	public function run(){
		$spiritEngine = $this->executeLogic();
		$this->responseBuffer = $spiritEngine->response();
	}
	public function executeLogic(){
		$controller = null;
		if(isset($this->navigation->controllerName)){
			require($this->controller());
			$controllerName = ucfirst($this->navigation->controllerName.'Controller');
			$controllerReflection = new ReflectionClass($controllerName);
			$controller = $controllerReflection->newInstance();
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
	
	private function controller(){
		return Path::instance()->currentProject("apps.+{$this->navigation->controllerName}.@{$this->navigation->controllerName}.php");
	}
}
?>
