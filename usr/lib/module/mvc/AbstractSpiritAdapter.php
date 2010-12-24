<?php
abstract class AbstractSpiritAdapter{
	abstract public function call($methodName, $args=array());
	protected function prn($methodName, $args=array()){
		$buff = $this->call($methodName, $args);
		echo $buff;
		return $buff;
	}
	public function __call($methodName, $args=array()){
		return $this->prn($methodName, $args);
	}
}
?>