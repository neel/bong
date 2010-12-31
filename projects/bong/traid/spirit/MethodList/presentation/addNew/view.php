<button class="bong-dialog-btn">Add
	<script type="text/bongscript" event="click">
	bong.dialog({
		title: 'New <?php echo (get_class($data->controller) == 'Structs\Admin\SpiritController' ? 'Spirit' : 'Controller') ?> Method',
		content: 'Method Name: <input type="text" bong:handle="methodName" value="" />',
		buttons: [{
			label: 'Create',
			isDefault: true,
			action: function(){
				bong.href('<?php echo Resource::link() ?>/project/<?php echo (get_class($data->controller) == 'Structs\Admin\SpiritController' ? 'addSpiritMethod' : 'addControllerMethod') ?>/'+this.methodName.value).eval();
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
	</script>
</button>
