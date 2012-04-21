<?php
class InvalidExceptionException extends BongException{
	function __construct($exception, $missing_param = null){
		parent::__construct("bong.syetem.InvalidException", 500);
		$this->registerParam(new BongExceptionParam("exception", "Exception Thrown", true));
		$this->registerParam(new BongExceptionParam("missing", "Missing Params", false));
		$this->setParam("exception", $exception);
		$this->setParam("missing", $missing_param);
	}
	protected function templatize(){
		return "\nException <<".$this->exception->hierarchy().">> Thrown\n"."Failed to Throw Exception <".$this->param("exception")->hierarchy()."> as one or more Mandatory Parameter(s) `".$this->param('missing')."` were not Supplied";
	}
}
?>
