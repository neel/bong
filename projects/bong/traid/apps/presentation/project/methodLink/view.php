<?php if(count($data->arguments) == 0): ?>
	window.open('<?php echo Resource::link("/".$data->method->controller()->name()."/".$data->method->name()) ?>/', '_blank');
<?php else: ?>
bong.dialog({
	title: 'Visit',
	content: '<form method="post" action="#" bong:handle="method">\
					<div class="bong-dialog-form">\
						<div class="bong-dialog-form-field">\
							<label>Method</label> <input bong:handle="methodName" type="text" name="name" value="<?php echo $data->methodName ?>" disabled />\
						</div>\
					</div>\
					<fieldset class="bong-admin-properties-fieldset">\
						<legend>Arguments</legend>\
						<div class="bong-dialog-form">\
							<?php foreach($data->arguments as $argument): ?>\
							<div class="bong-dialog-form-field">\
								<label><?php echo $argument->name() ?>:</label> <input type="text" value="<?php echo ($argument->isDefault() ? $argument->defaultValue() : '') ?>" />\
							</div>\
							<?php endforeach; ?>\
						</div>\
					</fieldset>\
				</form>',
	buttons: [{
		label: 'Go',
		isDefault: true,
		action: function(){
			var dom = this.method;
			var methodName = this.methodName.value;
			var base = '<?php echo Resource::link("/".$data->method->controller()->name()."/".$data->method->name()) ?>/';
			var args = [];
			$('.bong-dialog-form-field').each(function(i, elem){
				if(i > 0){
					var labelText = $(elem).children('label').html();
					var valueText = $(elem).children('input')[0].value;
					args.push(valueText);
				}
			});
			window.open(base+args.join('/'), '_blank');
			args = [];
		}
	},{
		label: 'Cancel',
		action: function(){
			bong.activeDialog().hide();
		}
	}]
});
<?php endif; ?>
