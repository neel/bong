<?php
class ProjectBoxAbstractor extends SpiritAbstractor implements StaticBound, MemoryXDO, SelfFeeded, FloatingSpirit{
	public function show($projectName){
		$this->xdo->project = Fstab::instance()->project($projectName);
	}
	public function create(){
		
	}
}
?>
