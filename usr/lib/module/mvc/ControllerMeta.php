<?php
class ControllerMeta{
	/**
	 * Containing Project
	 * @var Project
	 */
	public $project;
	public $controller;
	public $method;
	public $url;
	
	public function __construct(){
		$this->project = Fstab::instance()->project(Mempool::instance()->get("bong.project.current"));
		$this->controller = Mempool::instance()->get("bong.mvc.controller");
		$this->method = Mempool::instance()->get("bong.mvc.method");
		$this->url = Mempool::instance()->get("bong.url.path");
		$this->base = MemPool::instance()->get("bong.url.base");
		$this->root = MemPool::instance()->get("bong.url.root");
	}
}
?>
