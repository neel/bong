<?php
class DirectoryNotFoundException extends DirectoryException{
	public function __construct($file){
		parent::__construct($file, "DirNotFound", 4049, "is not Found");
	}
}
?>