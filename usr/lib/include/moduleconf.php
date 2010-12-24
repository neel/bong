<?php
/**
 * @internal
 * @author Neel Basu
 */
class ModuleConf extends ConfigurationAdapter{
	public function __construct($moduleConfPath){
		parent::__construct($moduleConfPath);
	}
	private function query($xpath){
		$nodes = $this->xpath->query($xpath);
		$ret = array();
		for($i=0;$i<$nodes->length;++$i){
			$ret[] = $nodes->item($i)->nodeValue;
		}
		return $ret;
	}
	private function processInfoBlocks($infoNodesList){
		$ret = array();
		for($i=0;$i<$infoNodesList->length;++$i){
			$node  = $infoNodesList->item($i);
			$name  = $node->attributes->getNamedItem("name")->nodeValue;
			$value = $node->attributes->getNamedItem("value")->nodeValue;
			$ret[$name] = $value;
		}
		return $ret;
	}

	/**
	 * Makes a list of Modules must be loaded before loading this modle
	 * and returns that list as a numarically indexed array
	 * @return array
	 */
	public function dependencies(){
		return $this->query("//bong:module/bong:dependencies/bong:dependency/@module");
	}
	
	public function initialization(){
		return $this->query("//bong:module/bong:initialization/bong:run/@file");
	}

	/**
	 * returns the Files to be Included as a numarically indexed array
	 * @return array
	 */
	public function includes(){
		return $this->query("//bong:module/bong:includes/bong:include/@file");
	}

	/**
	 * Makes a Key Value Pair Out of Meta Blocks in XML
	 * 	<bong:meta>
	 *		<bong:info name="author" value="Neel Basu" />
	 *		<bong:info name="version" value="0.0.0.1" />
	 *  </bong:meta>
	 *  
	 *  to
	 *  
	 *  array('author' => 'Neel Basu', 'version' => '0.0.0.1')
	 *  @return array
	 */
	public function meta(){
		return $this->processInfoBlocks($this->xpath->query("//bong:module/bong:meta/bong:info"));
	}
}
?>