<?php
class ProjectDirNotReadableException extends ProjectDirException{
	public function __construct($projectName) {
		parent::__construct("bong.system.project.ProjectDirNotReadable", 4030);
		$this->registerParam(new BongExceptionParam("project", "Project Name", true));
		$this->setParam("project", $projectName);
	}
	protected function templatize(){
		return "\nUncaught Exception <<".$this->hierarchy().">> Thrown\nProject `".$this->param("project")."` Found in Fstab But Project directory in File System for the Project is not readable";
	}
}
?>
