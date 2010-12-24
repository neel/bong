<?php if($data->layout): ?>
<?php echo $data->layoutSource ?>
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
