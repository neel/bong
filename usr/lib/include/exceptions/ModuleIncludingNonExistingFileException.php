<?php
final class ModuleIncludingNonExistingFileException extends ModuleException{
	public function __construct($module, $file){
		parent::__construct($module, "bong.system.module.ModuleIncludingNonExistingFile", 808);
		$this->registerParam(new BongExceptionParam("incfile", "File", true));
		$this->incfile = $file;
	}
	protected function templatize(){
		return "Module ".$this->module." trying to including non existing file ".$this->incfile;
	}
}

?>