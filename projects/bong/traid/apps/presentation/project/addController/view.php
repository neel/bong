$('<li class="bong-admin-sidebar-components-item"><a href="<?php echo Resource::link('/project/controller/'.$data->name) ?>"><?php echo $data->name ?>Controller</a><a class="bong-admin-sidebar-components-item-cross"></a></li>').insertBefore("#sidebar_controller > .bong-admin-sidebar-components-item-spcl");
bong.dialog({
	title: 'Creating <?php echo $data->name ?>Controller',
	content: '<?php echo $data->controller->className() ?> created in `<?php echo $data->controller->filePath() ?>`',
	buttons: [{
		label: 'Okay',
		isDefault: true,
		action: function(){
			bong.activeDialog().hide();
		}
	}]
});
