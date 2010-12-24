<?php
class Resource{
	public static function css($cssName){
		return MemPool::instance()->get("bong.url.base")."/rc/css/$cssName";
	}
	public static function js($jsName){
		return MemPool::instance()->get("bong.url.base")."/rc/js/$jsName";
	}
	public static function image($imageName){
		return MemPool::instance()->get("bong.url.base")."/rc/img/$imageName";
	}
	public static function link($link=''){
		return MemPool::instance()->get("bong.url.base").$link;
	}
	public static function self($params=''){
		return MemPool::instance()->get("bong.url.root").MemPool::instance()->get('bong.url.path').$params;
	}
}
