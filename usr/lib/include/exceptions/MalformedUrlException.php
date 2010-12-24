<?php
final class MalformedUrlException extends BongException {
	public function __construct($url) {
		parent::__construct("bong.syetem.MalformedUrl", 420);
		$this->registerParam(new BongExceptionParam("url", "Requested Url", true));
		$this->setParam("url", $url);
	}
	protected function templatize() {
		return "Requested Url `".$this->url."` is Malformed";
	}
}

?>