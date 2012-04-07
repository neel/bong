<?php
class XMLPacker{
	private $__data;
	//private $__root;
	private $__recurse = array();
	private $__hashIdMap = array();
	private $__xml_recursion_marker;
	private $__c = 0;
	private $__dom;
	private $__domPtr;
	private $__idAttrNodes = array();
	private $__idAttrNodesReferred = array();
	
	private function uniqueId(){
		return "bong".md5(uniqid(" ", true).$this->__c++);
	}
	/**
	 * Create an XML Element with $tagName and append's that node to the Current XMLNode Pointer and
	 * point the XMLNode Pointer to the newly created Node
	 * assign's a unique ID to the Node just Created.
	 * returns the unique ID Assigned
	 * \return string
	 */
	private function createElement($tagName, $attributes=array(), $uuid=null){
		$uid = $uuid ? $uuid : $this->uniqueId();
		if(strpos($tagName, ' ')){
			debug_print_backtrace();
		}
		$element = $this->__dom->createElement($tagName);
		//{ Create an ID
		$attr_id = $this->__dom->createAttribute('bong:id');
		$attr_id->appendChild($this->__dom->createTextNode($uid));
		$element->appendChild($attr_id);
		$this->__idAttrNodes[] = &$attr_id;
		//}
		//{ Write Attributes
		foreach($attributes as $k => $v){
			$attr = $this->__dom->createAttribute($k);
			$attr->appendChild($this->__dom->createTextNode($v));
			$element->appendChild($attr);
		}
		$this->__domPtr->appendChild($element);
		//}
		//{ Write XPath
		//$attr_xpath = $this->__dom->createAttribute('bong:xpath');
		//$attr_xpath->appendChild($this->__dom->createTextNode(getNodeXPath($element)));
		//$element->appendChild($attr_xpath);
		//}
		$this->__domPtr = $element;
		return $uid;
	}
	private function createAtribute($attrName, $attrValue){
		$attrNode = $this->__dom->createAttribute($attrName);
		$attrScalarNode = $this->__dom->createTextNode($attrValue);
		$attrNode->appendChild($attrScalarNode);
		$this->__domPtr->appendChild($attrNode);
	}
	private function createTextNode($text){
		$this->__domPtr->appendChild($this->__dom->createTextNode($text));
	}
	public function __construct(&$data){
		$this->__data = &$data;
		//$this->__root = $root;
		$this->__xml_recursion_marker = ("__bong_".md5(uniqid("bong", true)));
		$this->__dom = new DOMDocument('1.0', 'utf-8');
		$this->__domPtr = $this->__dom;
	}
	private function createRootElement(){
		$attr = array('bong:type' => XMLType($this->__data)->type());
		if($attr['bong:type'] == 'object')
			$attr['bong:class'] = get_class($this->__data);
		$root = $this->createElement('bong:xdo', $attr);
		$bong_ns = $this->__dom->createAttributeNS("http://lab.zigmoyd.net/xmlns/bong", 'bong:dummy');
		$meta = $this->__dom->createElement('bong:meta');
		$ts_attr = $this->__dom->createAttribute('ts');
		$ts_attr->appendChild($this->__dom->createTextNode(time()));
		$meta->appendChild($ts_attr);
		$this->__domPtr->appendChild($meta);
		$this->__dom->appendChild($this->__domPtr);
	}
	public function toXML(){
		$this->createRootElement();
		$this->toXML_internal(&$this->__data);
		$this->clearRecursionProbes(&$this->__data);
		$this->clearUnrefferedIds();
		return $this->__dom;
	}
	private function toXML_internal(&$o){
		if(is_object($o)){
			if($this->isRecursive(&$o)){
				$this->handleRecurseNode(&$o);
			}else{
				$this->probeRecursionSecurity(&$o);
				foreach($o as $k => $v){
					$attr = array('bong:type' => XMLType($v)->type());
					if(!XMLType(&$v)->isScaler()){
						if(XMLType($v)->isObject()){
							$attr['bong:class'] = get_class($v);
						}
						$this->createElement($k, $attr);
						$this->toXML_internal(&$v);		
						$this->__domPtr = $this->__domPtr->parentNode;				
					}else{
						$this->createAtribute($k, $v);
					}
				}
			}
		}elseif(is_array($o)){
			if($this->isRecursive(&$o)){
				$this->handleRecurseNode(&$o);
			}else{
				$this->probeRecursionSecurity(&$o);
				foreach($o as $i => $v){
					if(is_string($i) && $i == $this->__xml_recursion_marker)
						return;
					$className = is_int($i) ? (is_object($v) ? get_class($v) : (is_array($v) ? 'bong:sequence' : 'bong:item') ) : (is_string($i) ? $i : null);
					if(strlen($className) < 1)
						$className = "stdClass";
					$attr = array();
					//if(is_int($i))
						$attr['bong:index'] = $i;
					$attr['bong:type'] = XMLType($v)->type();
					//$attr['bong:class'] = $className;
					if(strpos($className, ' ')){
						$className = 'bong:item';
					}
					$this->createElement($className, $attr);
					$this->toXML_internal(&$v);
					$this->__domPtr = $this->__domPtr->parentNode;
				}
			}
		}else{
			$this->createTextNode($o);
		}
	}
	private function handleRecurseNode(&$o){
		$attr = array('bong:refer' => '0x0');
		if(is_object($o)){
			$attr['bong:refer'] = $this->__hashIdMap['h'.spl_object_hash($o)];
		}else if(is_array($o)){
			$attr['bong:refer'] = $o[$this->__xml_recursion_marker];
		}
		$this->__idAttrNodesReferred[] = $attr['bong:refer'];
		$refer_attr = $this->__dom->createAttribute('bong:refer'); 
		$refer_attr->appendChild($this->__dom->createTextNode($attr['bong:refer']));
		$this->__domPtr->appendChild($refer_attr);
		//$this->createElement('bong:recursion', $attr);
		$this->__domPtr = $this->__domPtr->parentNode;
	}
	private function probeRecursionSecurity(&$o){
		if(is_object($o)){
			array_push($this->__recurse, spl_object_hash($o));
			$this->__hashIdMap['h'.spl_object_hash($o)] = $this->__domPtr->attributes->getNamedItem('bong:id')->nodeValue;
		}else if(is_array($o)){
			$o[$this->__xml_recursion_marker] = $this->__domPtr->attributes->getNamedItem('bong:id')->nodeValue;
		}
	}
	private function isRecursive(&$o){
		if(is_object($o)){
			return in_array(spl_object_hash($o), $this->__recurse);
		}elseif(is_array($o)){
			$_s = isset($o[$this->__xml_recursion_marker]);
			if($_s){
				return true;
			}else{
				return false;
			}
		}
	}
	private function clearRecursionProbes(&$o){
		if(is_array($o)){
			if(isset($o[$this->__xml_recursion_marker]))
				unset($o[$this->__xml_recursion_marker]);
			foreach($o as $v){
				if(is_array($v) && isset($v[$this->__xml_recursion_marker])){
					$this->clearRecursionProbes(&$v);
				}
			}
		}
		$this->__c = 0;
	}
	private function clearUnrefferedIds(){
		foreach($this->__idAttrNodes as $attrNode){
			if(!in_array($attrNode->nodeValue, $this->__idAttrNodesReferred)){
				$attrNode->ownerElement->removeAttributeNode($attrNode);
			}
		}
	}
}
?>
