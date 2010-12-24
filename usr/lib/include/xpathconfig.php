<?php
interface XPathConfig{
	/*private function directiveToXPath($path, &$projectDir="");*/
	public function evaluate($path);
}
?>