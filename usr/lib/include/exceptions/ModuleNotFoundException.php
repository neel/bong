<?php
final class ModuleNotFoundException extends ModuleException{
	public function __construct($module){
		parent::__construct($module, "bong.system.module.ModuleNotFound", 4048);
	}
	protected function templatize(){
		return "Module ".$this->module." on ".$this->modulePath." cannot be Found";
	}
}
?>