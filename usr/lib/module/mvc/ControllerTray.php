<?php
final class ControllerTray extends AbstractDataTray {
	public $alternateView = null;
	
	/**
	 * \set
	 * 		scrap/plain
	 * 		scrap/html
	 * 		scrap/xml
	 * 		scrap/xhtml
	 * 
	 * 		page/*
	 * 
	 * @var string
	 */
	public $responceType = null;
	public $responseType = 'scrap/plain';
	public $contentType = null;
	public $xsltView = false;
	public $renderLayout = true;
	public $renderView = true;
	public $trim = false;
	public $stripWhiteSpaces = true;
	/**
	 * If True <bong:*> tags are Parsed from the response
	 */
	public $bongParsing = true;
	
	public function responceMainType(){
		$r = explode('/', $this->responceType);
		return $r[0];
	}
	public function responseSubType(){
		$r = explode('/', $this->responceType);
		return $r[1];
	}
}
?>
