<?php
abstract class ProjectException extends BongException {
	public function __construct($hierarchy, $errCode){
		parent::__construct($hierarchy, $errCode);
	}
}
?>