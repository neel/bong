<?php
namespace FSM;
class State{
	const GET = 0x0bc1;
	const POST = 0x0bc2;
	const AJAXGET = 0x0bc3;
	const AJAXPOST = 0x0bc4;
	
	private $_method;
	private $_url;
	
	public function __construct($method, $url){
		$this->_method = $method;
		$this->_url = $url;
	}
	public function method(){
		return $this->_method;
	}
	public function url(){
		return $this->_url;
	}
	public function isGet(){
		return ($this->_method == State::GET);
	}
	public function isPost(){
		return ($this->_method == State::POST);
	}
}
?>
