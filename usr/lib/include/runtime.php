<?php
final class Runtime{
	private static $_loadedModules = array();
	private static $_currentProject = null; 
	

	/**
	 * @return Project
	 */
	public static final function currentProject(){
		return Fstab::instance()->project(Runtime::$_currentProject);
	}

	/**
	 * @param String $currentProject
	 */
	public static final function setCurrentProject($currentProject) {
		Runtime::$_currentProject = $currentProject;
	}
	
	/**
	 * Load's the given Module
	 * throws ModuleNotFound exception if given an non existing module name
	 * throws ModuleConfNotFound if Module's configuration not found
	 * throws ModuleRequirementNotSatisfiable Exception if at least one of the Module's Requirementsd cannot be statisfied
	 * retuirns success value
	 * @param String $moduleName
	 * @return bool
	 */
	public static function loadModule($moduleName){
		if(!self::moduleExists($moduleName)){
			debug_print_backtrace();
			throw new ModuleNotFoundException($moduleName);
		}
		if(self::moduleLoaded($moduleName)){
			return true;
		}else{
			$moduleMeta = self::moduleInformation($moduleName);
			$moduleConf = new ModuleConf($moduleMeta->modulePath.'/'.$moduleName.'.xml');
			$dependencies = $moduleConf->dependencies();
			$includes = $moduleConf->includes();
			$initializers = $moduleConf->initialization();
			foreach($dependencies as $module){
				if(!self::loadModule($module)){
					throw new ModuleDependencyNotSatisfiableException($module);
				}
			}
			foreach($includes as $file){
				$file = rtrim($moduleMeta->modulePath)."/$file";
				if(!is_file($file)){
					throw new ModuleIncludingNonExistingFileException($moduleName, $file);
				}else if(!is_readable($file)){
					throw new ModuleIncludingNonReadableFileException($moduleName, $file);
				}else{
					require($file);
				}
			}
			foreach($initializers as $file){
				require(rtrim($moduleMeta->modulePath)."/$file");
			}
			self::$_loadedModules[] = $moduleName;
			return true;
		}
		return false;
	}
	
	/**
	 * Checks Wheather Module is Already loaded or Not
	 * @param String $moduleName
	 * @return bool
	 */
	public static function moduleLoaded($moduleName){
		return in_array($moduleName, self::$_loadedModules);
	}
	
	/**
	 * Check Wheather Module exists or not
	 * @param String $moduleName
	 * @return bool
	 */
	public static function moduleExists($moduleName){
		$moduleMeta = new ModuleMeta($moduleName);
		return $moduleMeta->moduleExists();
	}
	
	/**
	 * Return's the information of a givenModule 
	 * @param String $moduleName
	 * @return ModuleInfo
	 */
	public static function moduleInformation($moduleName){
		$moduleMeta = new ModuleMeta($moduleName);
		if($moduleMeta->moduleExists()){
			/**
			 * Bring Module Informations from XML Configuration to moduleMeta Object
			 */
			$moduleConf = new ModuleConf($moduleMeta->modulePath.'/'.$moduleName.'.xml');
			foreach($moduleConf->meta() as $key => $value){
				$moduleMeta->set($key, $value);
			}
			return $moduleMeta;
		}
		return null;
	}
	
	public static function import($moduleName){
		
	}
}
?>
