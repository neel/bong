<?php
//namespace Bong\Util;
class Conf extends ConfigurationAdapter implements XPathConfig{
	public function __construct(){
		parent::__construct(rtrim(getcwd(), "/")."/etc/common.xml");
	}
	private function directiveToXPath($path, &$projectDir=""){
		$branches = explode('.', $path);
		$branches = array_map(function($value){
			if(preg_match("~\-(\w+)~", $value, $m)){
				return "bong:pref[@catagory='$m[1]']";
			}else if(preg_match("~\@\w+~", $value)){
				return $value;
			}else{
				return "bong:pref[@key='$value']";
			}
		}, $branches);
		return "//bong:settings/".implode('/', $branches);
	}
	public function evaluate($path){
		$xpath = $this->directiveToXPath($path);
		$nodes = $this->xpath->query($xpath);
		$pathElems = array();
		if($nodes->length == 1){
			$node = $nodes->item(0);
			$valueNode = $node->attributes->getNamedItem("value");
			if($valueNode){
				return $valueNode->nodeValue;
			}else{
				if($node instanceof DOMAttr){
					return $nodes->nodeValue;
				}else{
					return simplexml_import_dom($node);
				}
			}
		}else if ($nodes->length > 1){
			$nodeList = array();
			for($i=0;$i<$nodes->length;++$i){
				if($nodes->item($i) instanceof DOMAttr){
					$nodeList[] = $nodes->item($i)->nodeValue;
				}elseif($nodes->item($i)->attributes->getNamedItem("value")){
					$nodeList[] = $nodes->item($i)->attributes->getNamedItem("value")->nodeValue;
				}else{
					$nodeList[] = simplexml_import_dom($nodes->item($i));
				}
			}
			return $nodeList;
		}else{
			return null;
		}
	}
}
?>