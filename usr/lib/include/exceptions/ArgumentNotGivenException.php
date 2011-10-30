<?php
class ArgumentNotGivenException extends BongException{
	public function __construct($methodName, $controllerName, $projectName){
		parent::__construct('bong.mvc.app.method.ArgumentNotGiven', '40415');
		$this->registerParam(new BongExceptionParam('controllerName', 'Controller Name', true));
		$this->registerParam(new BongExceptionParam('projectName', 'project Name', true));
		$this->registerParam(new BongExceptionParam('methodName', 'method Name', true));
		$this->setParam('controllerName', $controllerName);
		$this->setParam('methodName', $methodName);
		$this->setParam('projectName', $projectName);
	}
	public function templatize(){
		return "\nUncaught Exception <<".$this->hierarchy().">> Thrown\n"."Method".$this->methodName." of Cotroller ".$this->controllerName.'on Project '.$this->projectName.' needs to get values for non default Arguments';
	}
}

?>
