<div class="bong-admin-box" style="height: 250px;width: 700px;">
<?php foreach(Fstab::instance()->projectNames() as $projectName): ?>
	<bong:spirit name="ProjectBox" call="show">
		<bong:param value="<?php echo $projectName ?>" />
	</bong:spirit>
<?php endforeach; ?>
	<bong:spirit name="ProjectBox" call="create" />
	<div class="bong-admin-box-header">Showing All Projects listed in <a href="#">Fstab</a></div>
</div>