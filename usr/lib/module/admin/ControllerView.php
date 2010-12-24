<?php
namespace Structs\Admin;
final class ControllerView extends View{
	public function filePath(){
		return \Path::instance()->evaluate(':'.$this->method()->controller()->project()->name().'.apps.view.+'.$this->method()->controller()->name().'.-'.$this->method()->name().'.@'.$this->name().'.php');
	}
}
?>
