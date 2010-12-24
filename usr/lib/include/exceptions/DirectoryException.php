<?php
abstract class DirectoryException extends FileSystemException {
	public function __construct($file, $exceptionExtension, $errCode, $errMsg){
		parent::__construct($file, $exceptionExtension, $errCode, $errMsg, false);
	}
}
?>