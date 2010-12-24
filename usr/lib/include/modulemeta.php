<?php
final class ModuleMeta extends StdMap{
	/**
	 * also set's the Module Name and Module Path
	 * @param String $name
	 */
	public function __construct($name){
		parent::set("moduleName", $name);
		parent::set("modulePath", Path::instance()->evaluate("lib.module")."/$name");
	}
	
	/**
	 * Checks for the Phisical Existance of the Module
	 * e.g. Check wheather the Module Folder exists or not
	 * @return bool
	 */
	public function moduleExists(){
		return (file_exists($this->modulePath) && is_dir($this->modulePath));
	}
}
?>