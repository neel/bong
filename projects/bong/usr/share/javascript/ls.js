$(document).ready(function(){
	$('.bong-admin-project-name-editable .bong-admin-project-name-editable-cntrl').hide();
	$('.bong-admin-project-name-editable a').click(function(event){
		$(this).hide();
		$(this).next().show();
		$(this).next().focus();
	});
	$('.bong-admin-project-name-editable .bong-admin-project-name-editable-cntrl').blur(function(event){
		$(this).prev().show();
		$(this).hide();
	});
	$('.bong-admin-project-name-editable .bong-admin-project-name-editable-cntrl').keyup(function(event){
		var btnText = $($(this).parent().parent().find('.bong-admin-project-cntrl-new')[0]).html();
		if(btnText.indexOf('*') == -1){
			$($(this).parent().parent().find('.bong-admin-project-cntrl-new')[0]).html(btnText+'*');
		}
		
		if(event.keyCode == 0x0D){
			$(this).blur();
		}else if(!String.fromCharCode(event.keyCode).match(/\w/)){
			this.value = this.value.substr(0, this.value.length-1)+'_';
			$(this).prev().html(this.value);
			$($(this).parent().next().children('.bong-admin-project-dir')[0]).html(this.value);
		}else{
			$(this).prev().html(this.value);
			$($(this).parent().next().children('.bong-admin-project-dir')[0]).html(this.value);
		}
	});
	$('.bong-admin-common-close').click(function(){
		bong.dialog({
			title: 'Confirm Delete Project',
			content: 'Are You Sure ? that You want to Delete the Project ?',
			buttons: [{
				label: 'Delete',
				isDefault: true,
				action: function(){
					bong.dialog({
						title: 'Delete project',
						content: 'Functionality Not Developed Yet',
						buttons: [{
							label: 'Okay',
							isDefault: true,
							action: function(){
								bong.activeDialog().hide();
								bong.activeDialog().rollback();
							}
						}]
					});
				}
			},{
				label: 'Cancel',
				action: function(){
					bong.activeDialog().hide();
				}
			}]
		});
	})
});
