<?php
class FileNotFoundException extends FileException{
	public function __construct($file){
		parent::__construct($file, "FileNotFound", 404, "is not Found");
	}
}
?>
