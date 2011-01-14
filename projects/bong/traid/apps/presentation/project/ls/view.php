<div class="bong-admin-box" style="height: 250px;width: 700px;">
<?php foreach(Fstab::instance()->projectNames() as $projectName): ?>
	<bong:spirit name="ProjectBox" call="show">
		<bong:param value="<?php echo $projectName ?>" />
	</bong:spirit>
<?php endforeach; ?>
	<bong:spirit name="ProjectBox" call="create" />
	<div class="bong-admin-box-header">Showing All Projects listed in <a href="#">Fstab</a></div>
<?php /*Dump::r($data->form)*/ ?>
<?php /*print_r($data->fsm['f0a61c7e8ce7aee55a5e88f733b5f65a'])*/ ?>
<?php /*Dump::r($data->report->errors())*/ ?>
</div>
