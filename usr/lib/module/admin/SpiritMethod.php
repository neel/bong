<?php
namespace Structs\Admin;
final class SpiritMethod extends Method{
	/**
	 * @load
	 * @param ReflectionMethod $reflection
	 */
	protected function _createFromReflection($reflection){
		$this->_type = Method::SpiritMethod;
		parent::_createFromReflection($reflection);
		//{ Add Views
		if($this->isPublic()){
			$viewPath = \Path::instance()->evaluate(':'.$this->controller()->project()->name().'.*'.$this->controller()->name().'.view.-'.$this->name());
			if(file_exists($viewPath)){
				$dh = opendir($viewPath);
				$viewsFound = array();
				while(false !== ($file = readdir($dh))) {
			        if($file != "." && $file != ".." && strtolower(pathinfo($file, PATHINFO_EXTENSION)) == 'php'){
			        	$viewFilePath = rtrim($viewPath, '/')."/$file";
			            $viewsFound[$file] = $viewFilePath;
			            $this->addView(SpiritView::create($this, pathinfo($file, PATHINFO_FILENAME)));
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
}
?>
