<?php
namespace Structs\Admin;
class CommonComponent extends Struct{
	protected $_filePath = null;
	
	public function instance($filePath){
		$this->_filePath = $filePath;
	}
	public function filePath(){
		return $this->_filePath;
	}
	public function source(){
		return file_get_contents($this->filePath());
	}
	public function generate($data=""){
		if(is_writable(pathinfo($this->filePath(), PATHINFO_DIRNAME))){
			$ret = file_put_contents($this->filePath(), $data) !== false;
			return $ret;
		}
		return false;
	}
}
?>
