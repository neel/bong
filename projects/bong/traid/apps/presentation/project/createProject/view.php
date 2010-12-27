<?php if($data->success): ?>
bong.dialog({
	title: 'Project Created',
	content: 'Project <?php echo $data->projectName ?>',
	buttons: [{
		label: 'Okay',
		isDefault: true,
		action: function(){
			bong.activeDialog().hide();
			location.href = '<?php Resource::self() ?>'
		}
	}]
});
<?php else: ?>
bong.dialog({
	title: 'Failed to create Project',
	content: 'Project <?php echo $data->projectName ?> Couldn\'t be created Probabbly due to permission errors or for directory being not writable',
	buttons: [{
		label: 'Okay',
		isDefault: true,
		action: function(){
			bong.activeDialog().hide();
		}
	}]
});
<?php endif; ?>