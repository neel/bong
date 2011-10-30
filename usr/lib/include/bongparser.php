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
			$spiritName = $this->parent()->attrByname('name')->value()->value();
			$methodName = $this->parent()->attrByname('call') ? $this->parent()->attrByname('call')->value()->value() : 'main';
			$args = $this->_params;
			$this->_params = array();
			return $this->_ftor($spiritName, $methodName, $args, $tag->name());
		}else if($tag->name() == 'param'){
			return '';
		}
	}
	public function standalone($tag){
		$tagName = $tag->name();
		if(in_array($tagName, array('spirit', 'embed'))){
			$this->_params = array();
			$spiritName = $tag->attrByname('name')->value()->value();
			$methodName = $tag->attrByname('call') ? $tag->attrByname('call')->value()->value() : 'main';
			$instanceId = $tag->attrByname('instance') ? $tag->attrByname('instance')->value()->value() : null;
			return $this->_ftor($spiritName, $methodName, array(), $tagName, $instanceId);
		}else if($tagName == 'param'){
			$value = $tag->attrByname('value')->value()->value();
			$this->_params[] = $value;
			return '';
		}
	}
    public function __call($method, $args){
        if(is_callable(array($this, $method))){
            return call_user_func_array($this->$method, $args);
        }
    }
}
?>
