<?php
class ControllerNotFoundException extends FileNotFoundException{
	public function __construct($controllerName){
		$controllerPath = Path::instance()->currentProject("apps.+&$controllerName.@&$controllerName.php");
		parent::__construct($controllerPath);
		$this->registerParam(new BongExceptionParam("controllerName", "controller Name", true));
		$this->registerParam(new BongExceptionParam("controllerPath", "controller Path", true));
		$this->setParam("controllerName", $controllerName);
		$this->setParam("controllerPath", $controllerPath);
	}
	protected function templatize(){
		return "\nUncaught Exception <<".$this->hierarchy().">> Thrown\nCotroller ".$this->controllerName.' not Found Searched on `'.$this->controllerPath.'`';
	}
}
?>
