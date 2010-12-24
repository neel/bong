<?php
class InvalidExceptionException extends BongException{
	function __construct($exception){
		parent::__construct("bong.syetem.InvalidException", 500);
		$this->registerParam(new BongExceptionParam("exception", "Exception Thrown", true));
		$this->setParam("exception", $exception);
	}
	protected function templatize(){
		return "\nException <<".$this->hierarchy().">> Thrown\n"."Failed to Throw Exception `".$this->param("exception")->hierarchy."` as one or more Mandatory Parameter(s) were not Supplied";
	}
}
?>
