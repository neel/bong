<?php
//namespace Bong\Util;
abstract class ConfigurationAdapter extends Singleton{
	protected $dom;
	protected $xpath;
	protected $filePath;
	
	public function __construct($filePath){
		$this->filePath = $filePath;
		$this->dom = new DOMDocument("1.0", "utf-8");
		$this->dom->load($filePath);
		$this->xpath = new DOMXPath($this->dom);
		$this->xpath->registerNamespace('bong', 'http://lab.zigmoyd.net/xmlns/bong');
	}
}
?>
