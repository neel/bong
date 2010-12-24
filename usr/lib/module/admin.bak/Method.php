<?php
namespace Structs\Admin;
final class Method extends Struct{
	private $_name;
	private $_public;
	private $_arguments = array();
	private $_controller;
	private $_views = array();
	private $_viewDirectoryCreated = false;
	private $_hasDefaultView = false;
	private $_hasParams = false;
	private $_hasLayout = false;
	private $_inherited = false;
	
	/**
	 * @param Controller $controller
	 * @param [string|ReflectionMethod] $reflectionOrName
	 * @param bool $public
	 * @param array<Argument> $args
	 */
	public function instance($controller, $reflectionOrName, $public=null, $args=array()){
		$this->_controller = $controller;
		if(is_string($reflectionOrName)){
			$name = $reflectionOrName;
			$this->_create($name, $public, $args);
		}else if(is_object($reflectionOrName) && get_class($reflectionOrName) == 'ReflectionMethod'){
			$reflection = $reflectionOrName;
			$this->_createFromReflection($reflection);
		}
		//$this->_controller->addMethod($this);
	}
	/**
	 * @param Argument $argument
	 */
	public function addArgument($argument){
		$this->_arguments[] = $argument;
	}
	public function controller(){
		return $this->_controller;
	}
	public function name(){
		return $this->_name;
	}
	public function arguments(){
		return $this->_arguments;
	}
	public function isPublic(){
		return $this->_public;
	}
	public function isAction(){
		return ($this->isPublic() && !$this->inherited());
	}
	/**
	 * @load
	 * @param ReflectionMethod $reflection
	 */
	private function _createFromReflection($reflection){
		$this->_name = $reflection->getName();
		$this->_public = $reflection->isPublic();
		$this->_inherited = !($reflection->getDeclaringClass()->getName() == $this->_controller->className());
		foreach($reflection->getParameters() as $paramReflection){
			//Argument::create($this, $paramReflection);
			$this->addArgument(Argument::create($this, $paramReflection));
		}
		//{ Add Views
		if($this->isAction()){
			$viewPath = \Path::instance()->evaluate(':'.$this->controller()->project()->name().'.apps.view.+'.$this->controller()->name().'.-'.$this->name());
			if(file_exists($viewPath)){
				$this->setViewDirectoryCreated();
				$dh = opendir($viewPath);
				$viewsFound = array();
				while(false !== ($file = readdir($dh))) {
			        if($file != "." && $file != ".." && strtolower(pathinfo($file, PATHINFO_EXTENSION)) == 'php') {
			            $viewFilePath = rtrim($viewPath, '/')."/$file";
			            if($file == 'layout.php'){
			            	$this->setHasLayout();
			            }else if($file == 'params.php'){
			            	$this->setHasParams();
			            }else{
			            	$viewsFound[$file] = $viewFilePath;
			            	$this->addView(ControllerView::create($this, pathinfo($file, PATHINFO_FILENAME)));
			            }
			        }
			    }
			    closedir($dh);
			    if(array_key_exists('view.php', $viewsFound)){
			    	$this->setHasDefaultView();
			    }
			}
		}
		//}
	}
	/**
	 * @store
	 * @param string $name
	 * @param bool $public
	 * @param array<Argument> $args
	 */
	private function _create($name, $public, $args){
		$this->_name = $name;
		$this->_public = $public;
		$this->_arguments = $args;
	}
	/**
	 * @param View $view
	 */
	public function addView($view){
		$this->_views[] = $view;
	}
	public function viewDirectoryCreated(){
		return $this->_viewDirectoryCreated;
	}
	public function hasDefaultView(){
		return $this->_hasDefaultView;
	}
	public function setViewDirectoryCreated(){
		$this->_viewDirectoryCreated = true;
	}
	public function setHasDefaultView(){
		$this->_hasDefaultView = true;
	}
	public function setHasLayout(){
		$this->_hasLayout = true;
	}
	public function setHasParams(){
		$this->_setHasParams = true;
	}
	public function hasLayout(){
		return $this->_hasLayout;
	}
	public function hasParams(){
		return $this->_hasParams;
	}
	public function inherited(){
		return $this->_inherited;
	}
	public function generate($buffer = false){
		$access = $this->_public ? 'public' : 'private';
		$argsStr = implode(', ', $this->_arguments);
		$writeStr = <<<METHODSTR
		
	$access function {$this->_name}($argsStr){
		/*TODO Not Implemented Yet*/
	}
	
METHODSTR;
		$this->generateView();
		if(!$buffer){
			$endLine = $this->_controller->endLine();
			$fp = fopen($this->_controller->filePath(), 'r+');
			fseekline($fp, $endLine);
			$classEndPos = fstrrpos($fp, '}');
			fseek($fp, $classEndPos);
			fwrite($fp, $writeStr, strlen($writeStr));
			fclose($fp);
		}else{
			return $writeStr;
		}
	}
	public function generateView(){
		foreach($this->_views as $view){
$viewStr = <<<VIEWSTR
/**
 * \view {$this->_controller->className()}:{$this->name()}:{$view->name()}
 */	
VIEWSTR;
			$fp = fopen($view->filePath(), 'w');
			fwrite($fp, $viewStr, strlen($viewStr));
			fclose($fp);
		}
	}
}
?>
