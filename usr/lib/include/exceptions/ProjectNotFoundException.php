<?php
class ProjectNotFoundException extends ProjectException{
	public function __construct($projectName) {
		parent::__construct("bong.system.project.ProjectNotFound", 4040);
		$this->registerParam(new BongExceptionParam("project", "Project Name", true));
		$this->setParam("project", $projectName);
	}
	protected function templatize(){
		return "\nUncaught Exception <<".$this->hierarchy().">> Thrown\nProject `".$this->param("project")."` not Found in Fstab";
	}
}
?>