<?php
class Resource{
	public static function base(){
		return preg_replace('~\..+~', '', MemPool::instance()->get("bong.url.base"));
	}
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
class SysResource{
	public static function css($cssName){
		return MemPool::instance()->get("bong.url.base")."/sys/rc/css/$cssName";
	}
	public static function js($jsName){
		return Resource::base()."/sys/rc/js/$jsName";
	}
	public static function image($imageName){
		return MemPool::instance()->get("bong.url.base")."/sys/rc/img/$imageName";
	}
}
