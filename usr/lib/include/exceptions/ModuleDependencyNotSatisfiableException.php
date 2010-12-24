<?php
final class ModuleDependencyNotSatisfiableException extends ModuleException {
	public function __construct($module) {
		parent::__construct($module, "bong.system.module.ModuleDependencyNotSatisfiableException", 5008);
	}
	protected function templatize(){
		return "Module ".$this->module." Dependency Not Satisfiable";
	}
}

?>