<?php
class FileNotWritableException extends FileException{
	public function __construct($file){
		parent::__construct($file, "FileNotWritable", 405, "is not Writable");
	}
}
?>