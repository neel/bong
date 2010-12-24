<?php
abstract class ModuleException extends BongException {
	public function __construct($moduleName, $exceptionName, $exceptionCode) {
		parent::__construct($exceptionName, $exceptionCode);
		$this->registerParam(new BongExceptionParam("module", "Module", true));
		$this->registerParam(new BongExceptionParam("modulePath", "Module Path", true));
		$module = new ModuleMeta($moduleName);
		$this->module = $moduleName;
		$this->modulePath = $module->modulePath;
	}
}

?>