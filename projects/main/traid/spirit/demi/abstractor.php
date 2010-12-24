<?php
final class DemiAbstractor extends SpiritAbstractor implements InstanceBound, SerializableXDO, SpiritFeeded, SessionedSpirit{
	public function main(){
		if(!$this->cached())
			$this->happy();
	}
	public function sad(){
		$this->xdo->smily = ":(";
	}
	public function happy(){
		$this->xdo->smily = ":)";
	}
}
?>