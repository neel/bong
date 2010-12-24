<?php
abstract class FileSystemException extends BongException{
	private $errMsg;
	private $_fileException;
	
	public function __construct($file, $exceptionExtension, $errCode, $errMsg, $fileException=true){
		$this->errMsg = $errMsg;
		$this->_fileException = $fileException;
		parent::__construct("bong.util.".($this->_fileException ? 'file' : 'dir').".".$exceptionExtension, $errCode);
		$this->registerParam(new BongExceptionParam("filePath", "File Path", true));
		$this->setParam("filePath", $file);
	}
	protected function templatize(){
		return "\nUncaught Exception <<".$this->hierarchy().">> Thrown\n".($this->_fileException ? 'File' : 'Directory')." `".$this->filePath."` $this->errMsg";
	}
}
?>