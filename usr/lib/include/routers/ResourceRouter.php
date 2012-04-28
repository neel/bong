<?php
final class ResourceRouter extends AbstractContentRouter{
	public function __construct(/*$engineName*/){
		parent::__construct("ResourceEngine");
		Runtime::loadModule('util');
	}
	/**
	 * Expects an Array with **Exactly** one Argument Thats teh Shared Component's Name
	 * e.g. the Image Name / The CSS Name etc..
	 * Set's the ContentName via navigation->contentName which will be accessed Via Engine Latter
	 */
	public function buildNavigation($path){
		$this->navigation->systemResource = (MemPool::instance()->get("bong.router.pattern") == 'resource.sys');
		$itr = $this->navigation->systemResource ? 3 : 2;
		$this->navigation->resourceType = $path[$itr-1];
		$path = array_slice($path, $itr);
		$this->navigation->contentName = implode('/', $path);
		$extParts = explode('.', array_pop($path));
		$ext = count($extParts) == 2 ? $extParts[1] : null;
		$this->navigation->extension = $ext;
		$preferedExtension = null;
		switch($this->navigation->resourceType){
			case 'sys':
			case 'scrap':
				$preferedExtension = null;
				break;
			case 'img':
				$preferedExtension = 'png';
				break;
			case 'js':
				$preferedExtension = 'js';
				break;
			case 'css':
				$preferedExtension = 'css';
				break;
			case 'xslt':
				$preferedExtension = 'xsl';
				break;
			default:
		}
		$this->navigation->preferedExtension = $preferedExtension;
		$this->navigation->followSpecifiedExtension = $this->navigation->resourceType == 'img' ? !(!$ext && $preferedExtension) : (is_null($preferedExtension) || $ext == $preferedExtension);
		$this->navigation->estimatedContentName = !$this->navigation->followSpecifiedExtension ? (rtrim($this->navigation->contentName, '.').'.'.$preferedExtension) : $this->navigation->contentName;
		//print_r($this->navigation);
	}
}
RouterFactory::register('ResourceRouter');
?>