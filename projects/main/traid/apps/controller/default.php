<?php
class DefaultController extends BongAppController{
	public function main($name=null, $age=null){
		http::contentType('text/html');
		$this->bootStrapJs();
		if(!$this->cached() || $name)
			$this->xdo->name = $name;
		if(!$this->cached() || $age)
			$this->xdo->age = $age;
		//$this->switchView('alternateView');
	}
	public function hallo(){
		$this->renderViewAsXSLT();
	}
}
?>
