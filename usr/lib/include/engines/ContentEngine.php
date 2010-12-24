<?php
abstract class ContentEngine extends AbstractContentEngine implements Runnable{
	abstract public function executeLogic();
}
?>