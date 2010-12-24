<?php if($data->params): ?>
<?php echo $data->paramsSource ?>
<?php else: ?>
bong.dialog({
	title: 'Layout Created Successfully',
	content: '',
	buttons: [{
		label: 'Okay',
		isDefault: true,
		action: function(){
			bong.activeDialog().hide();
		}
	}]
});
<?php endif; ?>
