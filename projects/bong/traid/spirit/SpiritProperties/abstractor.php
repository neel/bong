<?php
/**
 * \controller SpiritProperties
 */
class SpiritPropertiesAbstractor extends SpiritAbstractor implements StaticBound, MemoryXDO, ControllerFeeded, SessionedSpirit{
	public function main(){
		$this->data->controller = $this->controller->data->controller;
	}
}
?>
