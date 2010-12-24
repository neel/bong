<?php
abstract class ProjectDirException extends ProjectException{
	public function __construct($hierarchy, $errCode) {
		parent::__construct($hierarchy, $errCode);
	}
}
?>