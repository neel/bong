bong.dialog({
	title: 'Creating <?php echo $data->method->name() ?>()',
	content: '<?php echo $data->controllerName ?>Controller::<?php echo $data->method->name() ?>() created in `<?php echo $data->controller->filePath() ?>`',
	buttons: [{
		label: 'Okay',
		isDefault: true,
		action: function(){
			bong.activeDialog().hide();
		}
	}]
});
var handle = {};
var templateStr = '<div class="bong-admin-component-method">\
	<a class="bong-admin-component-method-cross"></a>\
	<div class="bong-admin-component-method-layout-area">\
		<a class="bong-admin-component-method-layout-show bong-admin-component-method-layout-<?php echo !$data->method->hasLayout() ? 'no' : '' ?>exists" href="#"></a>\
		<a class="bong-admin-component-method-param-show bong-admin-component-method-param-<?php echo !$data->method->hasParams() ? 'no' : '' ?>exists" href="#"></a>\
		<button bong:handle="layoutEdit" class="bong-admin-component-method-layout-edit bong-dialog-btn bong-admin-component-method-layout-exists <?php echo (!$data->method->hasLayout()) ? 'bong-admin-component-method-hide' : '' ?>"></button>\
		<button bong:handle="layoutCreate" class="bong-admin-component-method-layout-edit bong-dialog-btn bong-admin-component-method-layout-noexists <?php echo ($data->method->hasLayout()) ? 'bong-admin-component-method-hide' : '' ?>"></button>\
		<button bong:handle="paramsEdit" class="bong-admin-component-method-param-edit bong-dialog-btn bong-admin-component-method-param-exists <?php echo (!$data->method->hasParams()) ? 'bong-admin-component-method-hide' : '' ?>"></button>\
		<button bong:handle="paramsCreate" class="bong-admin-component-method-param-edit bong-dialog-btn bong-admin-component-method-param-noexists <?php echo ($data->method->hasParams()) ? 'bong-admin-component-method-hide' : '' ?>"></button>\
	</div>\
	<a class="bong-admin-component-method-name" href="#" bong:handle="name"><?php echo $data->method->name() ?></a>\
	<div class="bong-admin-component-method-views">\
		<div class="bong-admin-component-method-noview"></div>\
		<button class="bong-dialog-btn bong-dialog-btn-default bong-admin-component-method-view-new" bong:handle="addNewView">Add New</button>\
	</div>\
</div>';
var dom = bong.domify(templateStr, handle);
var methods = $('.bong-admin-component-method');
$(methods[methods.length-1]).after(dom);
console.log(dom);
bong.addEvent(handle.name, 'click', function(){
	bong.href('<?php echo Resource::link() ?>/source/controllerMethod/<?php echo $data->method->name() ?>').eval();	
})
bong.addEvent(handle.layoutEdit, 'click', function(){
	bong.href('<?php echo Resource::link() ?>/source/controllerMethodLayout/<?php echo $data->method->name() ?>').eval();
});
bong.addEvent(handle.layoutCreate, 'click', function(){
	var self = this;
	bong.href('<?php echo Resource::link() ?>/project/createControllerMethodLayout/<?php echo $data->method->name() ?>').async(function(data){
		console.log(data);
		bong.dialog({
			title: data.title,
			content: data.msg,
			buttons: [{
				label: 'Okay',
				isDefault: true,
				action: function(){
					bong.activeDialog().hide();
				}
			}]
		});
		if(data.success){
			$(self).addClass('bong-admin-component-method-hide');
			$(self).prev().removeClass('bong-admin-component-method-hide');
			$(self).prev().prev().prev().removeClass('bong-admin-component-method-layout-noexists');
			$(self).prev().prev().prev().addClass('bong-admin-component-method-layout-exists');
		}
	});
})
bong.addEvent(handle.paramsEdit, 'click', function(){
	bong.href('<?php echo Resource::link() ?>/source/controllerMethodParams/<?php echo $data->method->name() ?>').eval();
})
bong.addEvent(handle.paramsCreate, 'click', function(){
	var self = this;
	bong.href('<?php echo Resource::link() ?>/project/createControllerMethodParams/<?php echo $data->method->name() ?>').async(function(data){
		console.log(data);
		bong.dialog({
			title: data.title,
			content: data.msg,
			buttons: [{
				label: 'Okay',
				isDefault: true,
				action: function(){
					bong.activeDialog().hide();
				}
			}]
		});
		if(data.success){
			$(self).addClass('bong-admin-component-method-hide');
			$(self).prev().removeClass('bong-admin-component-method-hide');
			$(self).prev().prev().prev().prev().removeClass('bong-admin-component-method-param-noexists');
			$(self).prev().prev().prev().prev().addClass('bong-admin-component-method-param-exists');
		}
	});	
})
bong.addEvent(handle.addNewView, 'click', function(){
	var self = this;
	bong.dialog({
		title: 'New View for Controller Method <?php echo $data->methodName ?>',
		width: 400,
		content: '<center>View Name: <input type="hidden" bong:handle="methodName" value="<?php echo $data->methodName ?>"><input type="text" bong:handle="viewName" value="" /></center>',
		buttons: [{
			label: 'Create',
			isDefault: true,
			action: function(){
				var methodName = this.methodName.value;
				var viewName = this.viewName.value;
				bong.href('<?php echo Resource::link() ?>/project/addControllerView/'+this.methodName.value+'/'+this.viewName.value).async(function(data){
					bong.dialog({
						title: data.title,
						content: data.msg,
						buttons: [{
							label: 'Okay',
							isDefault: true,
							action: function(){
								bong.activeDialog().hide();
							}
						}]
					})
					if(data.success){
						var noView = $(self).parent().children('.bong-admin-component-method-noview');
						if(noView)
							noView.hide();
						var handle = {};
						var dom = bong.domify('<div class="bong-admin-component-method-view">'+viewName+'<a class="bong-admin-sidebar-components-view-cross" bong:handle="cross"></a></div>', handle)
						$(self).parent()[0].appendChild(dom);
						bong.addEvent(dom, 'click', function(){
							bong.href('<?php echo Resource::link() ?>/source/view/'+methodName+'/'+viewName).eval();
						});
					}
				});
				bong.activeDialog().hide();
			}
		},{
			label: 'Cancel',
			isDefault: false,
			action: function(){
				bong.activeDialog().hide();
			}
		}]
	});
})
