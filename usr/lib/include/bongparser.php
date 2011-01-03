<?php
class BongParser extends \SuSAX\AbstractParser{
	private $_currentTag = null;
	private $_ftor = null;
	private $_params = array();
	
	public function __construct($ftor){
		$this->_ftor = $ftor;
	}
	public function open($tag){
		$this->_params = array();
		$this->_currentTag = in_array($tag->name(), array('spirit', 'embed')) ? $tag->name() : null;
		return "";
	}
	public function close($tag){
		if($tag->name() == $this->_currentTag){
			$spiritName = $tag->attrByname('name')->value();
			$methodName = $tag->attrByname('call') ? $tag->attrByname('call')->value() : 'main';
			$args = $this->_params;
			$this->_params = array();
			return $this->_ftor($spiritName, $methodName, $tagName, $args);
		}else if($tag->name() == 'param'){
			return '';
		}
	}
	public function standalone($tag){
		$tagName = $tag->name();
		if(in_array($tagName, array('spirit', 'embed'))){
			$this->_params = array();
			$spiritName = $tag->attrByname('name')->value();
			$methodName = $tag->attrByname('call') ? $tag->attrByname('call')->value() : 'main';
			return $this->_ftor($spiritName, $methodName, $tagName, array());
		}else if($tagName == 'param'){
			$key = $tag->attrByname('name')->value();
			$value = $tag->attrByname('value')->value();
			$this->_params[$key] = $value;
			return '';
		}
	}
}
?>
