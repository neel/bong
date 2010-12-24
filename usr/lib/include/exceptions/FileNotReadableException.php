<?php
class FileNotReadableException extends FileException{
	public function __construct($file){
		parent::__construct($file, "FileNotReadable", 403, "is not Readable");
	}
}
?>