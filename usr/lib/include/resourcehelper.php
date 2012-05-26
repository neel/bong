<?php
class Resource{
	/**
	 * left / trimmed cause // was comming on left when using with the default controller
	 */
	public static function base(){
		return rtrim(preg_replace('~\..+~', '', MemPool::instance()->get("bong.url.base")), '/');
	}
	public static function css($cssName){
		return rtrim(MemPool::instance()->get("bong.url.base"), '/')."/rc/css/$cssName";
	}
	public static function js($jsName){
		return rtrim(MemPool::instance()->get("bong.url.base"), '/')."/rc/js/$jsName";
	}
	public static function image($imageName){
		return rtrim(MemPool::instance()->get("bong.url.base"), '/')."/rc/img/$imageName";
	}
	public static function link($link=''){
		return rtrim(MemPool::instance()->get("bong.url.base"), '/').$link;
	}
	public static function self($params=''){
		return MemPool::instance()->get("bong.url.root").MemPool::instance()->get('bong.url.path').$params;
	}
}
class SysResource{
	public static function css($cssName){
		return rtrim(MemPool::instance()->get("bong.url.base"), '/')."/sys/rc/css/$cssName";
	}
	public static function js($jsName){
		return rtrim(Resource::base(), '/')."/sys/rc/js/$jsName";
	}
	public static function image($imageName){
		return rtrim(MemPool::instance()->get("bong.url.base"), '/')."/sys/rc/img/$imageName";
	}
}
