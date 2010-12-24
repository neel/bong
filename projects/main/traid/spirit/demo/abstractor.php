<?php
class DemoAbstractor extends SpiritAbstractor implements StaticBound, SerializableXDO, ControllerFeeded, SessionedSpirit{
	public function ctor(){
		//echo "CALLED\n\n";
	}
	public function main(){
		$this->xdo->name = $this->controller->xdo->name;
	}
	public function age(){
		$this->xdo->age = $this->controller->xdo->age;
	}
}
?>
