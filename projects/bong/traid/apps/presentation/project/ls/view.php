<div class="bong-admin-box" style="height: 250px;width: 700px;">
<?php
foreach(Fstab::instance()->projectNames() as $projectName){
	$controller->spirit('ProjectBox')->show($projectName);
}
$controller->spirit('ProjectBox')->create();
?>
	<div class="bong-admin-box-header">Showing All Projects listed in <a href="#">Fstab</a></div>
</div>
