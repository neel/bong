<?php
class Loader extends Singleton {
	private $includedFiles;
	
	public function inc($absFilePath){
		if(!array_key_exists($absFilePath, $this->includedFiles)){
			$this->includedFiles[] = $absFilePath;
			include($absFilePath);
		}
	}
	public function installedModules(){
		
	}
	public function module($moduleName){
		$modulePath = Path::instance()->evaluate('lib.module');
		if(file_exists($modulePath."/$moduleName") && is_dir($modulePath."/$moduleName")){
			if(file_exists($modulePath."/$moduleName/$moduleName.xml")){
				if(is_readable($modulePath."/$moduleName/$moduleName.xml")){
					foreach(ModuleConf::instance()->dependencies() as $dep){
						Loader::instance()->module($dep);
					}
					foreach(ModuleConf::instance()->includes() as $inc){
						Loader::instance()->inc($modulePath."/$moduleName/".trim($inc, '/'));
					}
				}else{
					return -2;
				}
			}else{
				return -1;
			}
		}
		return 0;
	}
}
?>