$('<li class="bong-admin-sidebar-components-item"><?php echo $data->name ?>Abstractor<a class="bong-admin-sidebar-components-item-cross"></a></li>').insertBefore("#sidebar_spirit > .bong-admin-sidebar-components-item-spcl");
bong.dialog({
	title: 'Creating <?php echo $data->name ?>Abstractor',
	content: '<?php echo $data->spirit->className() ?> created in `<?php echo $data->spirit->filePath() ?>`',
	buttons: [{
		label: 'Okay',
		isDefault: true,
		action: function(){
			bong.activeDialog().hide();
		}
	}]
});
