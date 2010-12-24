<?php
final class SpiritMeta extends ControllerMeta{
	public $spirit;
	public $spiritMethod = null;
	
	public function __construct($spirit){
		$this->spirit = $spirit;
	}
}

?>