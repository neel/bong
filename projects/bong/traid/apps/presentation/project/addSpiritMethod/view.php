bong.dialog({
	title: 'Creating <?php echo $data->method->name() ?>()',
	content: '<?php echo $data->controllerName ?>Abstractor::<?php echo $data->method->name() ?>() created in `<?php echo $data->controller->filePath() ?>`',
	buttons: [{
		label: 'Okay',
		isDefault: true,
		action: function(){
			bong.activeDialog().hide();
		}
	}]
});
