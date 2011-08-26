<?php
abstract class BongController{
	protected $arguments = null;
	public $meta;
	/**
	 * @var AbstractXDO
	 */
	public $xdo;
	public $data;
	
	abstract public function ctor();
	public function serialize(){
		//echo get_class($this->xdo)."\n";
		//if(get_class($this->xdo) == 'SpiritXDO')
		//	var_dump($this->xdo->spiritName());
		$this->xdo->serialize();
	}
	public function cached(){
		return $this->xdo->serialized();
	}
	public function storage(){
		return $this->xdo->storage();
	}
	public function __construct(){
		if(isset($_POST) && isset($_POST['__bong_argument'])){
			$this->arguments = json_decode($_POST['__bong_argument']);
		}
	}
}
?>
