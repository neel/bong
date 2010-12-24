<?php
class ProjectDirNotFoundException extends ProjectDirException{
	public function __construct($projectName) {
		parent::__construct("bong.system.project.ProjectDirNotFound", 4041);
		$this->registerParam(new BongExceptionParam("project", "Project Name", true));
		$this->setParam("project", $projectName);
	}
	protected function templatize(){
		return "\nUncaught Exception <<".$this->hierarchy().">> Thrown\nProject `".$this->param("project")."` Found in Fstab But Project directory for the Project not Found in File System";
	}
}
?>