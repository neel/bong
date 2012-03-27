<?php
class XMLUnPacker{
	private $_dom = null;
	private $_xpath = null;
	private $_iterationStack = null;
	public $_store = null;
	public $_idVect = array();
	
	public function __construct($fileName){
		$this->_dom = new DomDocument("1.0", "utf-8");
		$this->_dom->load($fileName);
		$this->_xpath = new DOMXPath($this->_dom);
		$this->_xpath->registerNamespace('bong', 'http://lab.zigmoyd.net/xmlns/bong');
		$this->_iterationStack = new RefStack();
	}
	/**
	 * @param DomElement $domNode
	 */
	private function _nodeType($domNode){
		if($domNode->tagName == 'bong:meta')
			return XMLTypeFromTypeName('meta');
		if($this->_nodeIsRecursive($domNode))
			return XMLTypeFromTypeName('recursion');
		return XMLTypeFromTypeName($domNode->attributes->getNamedItemNS('http://lab.zigmoyd.net/xmlns/bong', 'type')->nodeValue);
	}
	/**
	 * @param DomElement $domNode
	 */
	private function _nodeTagName($domNode){
		return $domNode->tagName;
	}
	/**
	 * @param DomElement $domNode
	 */
	private function _nodeClass($domNode){
		if($domNode->hasAttributeNS('http://lab.zigmoyd.net/xmlns/bong', 'class'))
			return $domNode->attributes->getNamedItemNS('http://lab.zigmoyd.net/xmlns/bong', 'class')->nodeValue;
	}
	private function _nodeHasId($domNode){
		return $domNode->hasAttributeNS('http://lab.zigmoyd.net/xmlns/bong', 'id');
	}
	private function _nodeId($domNode){
		return $domNode->attributes->getNamedItemNS('http://lab.zigmoyd.net/xmlns/bong', 'id')->nodeValue;
	}
	/**
	 * 
	 * @param DomElement $domNode
	 */
	private function _nodeIsRecursive($domNode){
		return $domNode->hasAttributeNS('http://lab.zigmoyd.net/xmlns/bong', 'refer');
	}
	private function _nodeRecurseTargetId($domNode){
		return $domNode->attributes->getNamedItemNS('http://lab.zigmoyd.net/xmlns/bong', 'refer')->nodeValue;
	}
	private function _nodeClassName($domNode){
		$className = $this->_nodeClass($domNode);
		if(empty($className)){
			return $this->_nodeTagName($domNode);
		}
		return $className;
	}
	/**
	 * @param DomElement $domNode
	 */
	private function _nodeIndex($domNode){
		return $domNode->attributes->getNamedItemNS('http://lab.zigmoyd.net/xmlns/bong', 'index')->nodeValue;
	}
	/**
	 * @param DomNode $domNode
	 */
	private function _nodeIsInternal($domNode){
		return ($domNode->namespaceURI == 'http://lab.zigmoyd.net/xmlns/bong');
	}
	/**
	 * @param DomElement $domNode
	 * @return array
	 */
	private function _nodeProperties($domNode){
		$attr = array();
		foreach($domNode->attributes as $attribute){
			if(!$this->_nodeIsInternal($attribute)){
				$attr[$attribute->name] = $attribute->value;
			}
		}
		return $attr;
	}

	/**
	 * Inserts the Given construct to the construct's top element's children list
	 * @param Array / Object $construct
	 */
	private function __insertConstruct(&$construct, $node){
		if($this->_nodeHasId($node)){
			$this->_idVect[$this->_nodeId($node)] =& $construct;
		}
		if(!$this->_iterationStack->isEmpty()){
			if(is_object($this->_iterationStack->top())){
				$key = $this->_nodeTagName($node);
				if(is_string($key)){
					$this->_iterationStack->top()->{$key} =& $construct;
				}else{
					exit('Unexpected '.__FILE__.':'.__LINE__);
				}
			}else if(is_array($this->_iterationStack->top())){
				$parentConstruct =& $this->_iterationStack->top();
				$key = $this->_nodeIndex($node);
				if($key)
					$parentConstruct[$key] =& $construct;
				else
					$parentConstruct[] =& $construct;
			}
		}else{
			$this->_store =& $construct;
		}
	}

	private function _travarse($domNode){
		$nodeType = $this->_nodeType($domNode);
		//echo ">> _travarse'ing {$domNode->tagName} {$nodeType->type()} T\n";
		switch(true){
			case $nodeType->isObject():{
				$className = $this->_nodeClassName($domNode);
				$scalerProperties = $this->_nodeProperties($domNode);
				$construct = new $className;
				foreach($scalerProperties as $key => $value){
					$construct->{$key} = $value;
				}

				$this->__insertConstruct($construct, $domNode);
				$this->_iterationStack->push($construct);
				$children = $domNode->childNodes;
				$length = $children->length;
				for($i=0;$i<$length;++$i){
					$currentChild = $children->item($i);
					$this->_travarse($currentChild);
				}
				$this->_iterationStack->pop();
			}break;
			case $nodeType->isSequence():{
				$construct = array();
				$this->__insertConstruct($construct, $domNode);
				$this->_iterationStack->push($construct);
				$children = $domNode->childNodes;
				$length = $children->length;
				for($i=0;$i<$length;++$i){
					$currentChild = $children->item($i);
					$this->_travarse($currentChild);
				}
				$this->_iterationStack->pop();
			}break;
			case $nodeType->isScaler():{
				//echo ">> _travarse'ing/isScaler: {$domNode->textContent}\n";
				$this->__insertConstruct($domNode->textContent, $domNode);
			}break;
			case $nodeType->isRecursive():{
				$construct =& $this->_idVect[$this->_nodeRecurseTargetId($domNode)];
				$this->__insertConstruct($construct, $domNode);
			}break;
			case $nodeType->isMeta():{
				
			}break;
				exit('Unexpected '.__FILE__.':'.__LINE__);
		}
	}
	
	public function unpack(){
		$this->_travarse($this->_dom->documentElement);
		return $this->_store;
	}
}
?>
