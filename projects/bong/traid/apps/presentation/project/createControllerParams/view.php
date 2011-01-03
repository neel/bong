<?php if(!$data->success): ?>
bong.dialog({
	title: 'Failed to Create Layout',
	content: 'Controllerlayout creation Failed',
	buttons: [{
		label: 'Okay',
		isDefault: true,
		action: function(){
			bong.activeDialog().hide();
		}
	}]
});
<?php else: ?>
bong.dialog({
	title: 'Successfully Created Layout',
	content: 'Controllerlayout Created',
	buttons: [{
		label: 'Okay',
		isDefault: true,
		action: function(){
			bong.activeDialog().hide();
		}
	}]
});
$(this).addClass('bong-admin-property-hide');
$(this).prev().addClass('bong-admin-property-hide');
$(this).prev().prev().removeClass('bong-admin-property-hide');
$(this).prev().prev().prev().removeClass('bong-admin-property-hide');
<?php endif; ?>
