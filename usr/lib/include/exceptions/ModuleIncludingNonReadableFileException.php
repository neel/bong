<?php
final class ModuleIncludingNonReadableFileException extends ModuleException {
	public function __construct($module, $file) {
		parent::__construct($module, "bong.system.module.ModuleIncludingNonReadableFile", 806);
		$this->registerParam(new BongExceptionParam("file", "File", true));
		$this->file = $file;
	}
	protected function templatize(){
		return "Module ".$this->module." trying to including not readable file ".$this->file;
	}
}

?>