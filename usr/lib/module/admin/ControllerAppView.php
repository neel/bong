<?php
namespace Structs\Admin;
final class ControllerAppView extends View{
	public function filePath(){
		return \Path::instance()->evaluate(':'.$this->_controller->project()->name().'.apps.view.+'.$this->_controller->name().'.@view.php');
	}
	public function generate(){
		$viewStr = <<<VIEWSTR
<?php
/**
 * \view {$this->_method->controller()->className()}:{$this->_method->name()}:{$this->name()}
 */
?>
VIEWSTR;
		$fp = fopen($this->filePath(), 'w');
		fwrite($fp, $viewStr, strlen($viewStr));
		fclose($fp);
	}
	public function _create($controller){
		$this->_controller = $controller;
		$this->_filePath = $this->filePath();
	}
	public function instance($controller){
		$this->_create($controller);
	}
}
?>
