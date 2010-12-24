<?php
namespace Structs\Admin;
final class ControllerMethod extends Method{
	private $_hasParams = false;
	private $_hasLayout = false;
	private $_viewDirectoryCreated = false;
	private $_layout = null;
	private $_params = null;
	
	/**
	 * @load
	 * @param ReflectionMethod $reflection
	 */
	protected function _createFromReflection($reflection){
		$this->_type = Method::ControllerMethod;
		parent::_createFromReflection($reflection);
		//{ Add Views
		if($this->isPublic()){
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
			            	$this->_layout = ControllerLayout::create($viewPath.'/layout.php');
			            }else if($file == 'params.php'){
			            	$this->setHasParams();
			            	$this->_params = Params::create($viewPath.'/params.php');
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
	public function genLayout(){
		if(!$this->_hasLayout){
			$viewPath = \Path::instance()->evaluate(':'.$this->controller()->project()->name().'.apps.view.+'.$this->controller()->name().'.-'.$this->name());
			$this->_layout = ControllerLayout::create($viewPath.'/layout.php');
			$this->setHasLayout();
			return $this->_layout->generate();
		}
		return false;
	}
	public function genParams(){
		if(!$this->_hasParams){
			$viewPath = \Path::instance()->evaluate(':'.$this->controller()->project()->name().'.apps.view.+'.$this->controller()->name().'.-'.$this->name());
			$this->_params = Params::create($viewPath.'/params.php');
			$this->setHasParams();
			return $this->_params->generate();
		}
		return false;
	}
	public function viewDirectoryCreated(){
		return $this->_viewDirectoryCreated;
	}

	public function setViewDirectoryCreated(){
		$this->_viewDirectoryCreated = true;
	}

	public function setHasLayout(){
		$this->_hasLayout = true;
	}
	public function setHasParams(){
		$this->_hasParams = true;
	}
	public function hasLayout(){
		return $this->_hasLayout;
	}
	public function hasParams(){
		return $this->_hasParams;
	}
	public function layout(){
		return $this->_layout;
	}
	public function params(){
		return $this->_params;
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
