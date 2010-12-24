<?php
class DefaultController extends BongAppController{
	public function main($name="UnNamed", $age=20){
		$this->xdo->name = $name;
		if(!$this->cached())
			$this->xdo->age = $age;
		//$this->switchView('alternateView');
	}
	public function hallo(){
		$this->renderViewAsXSLT();
	}
}
?>
