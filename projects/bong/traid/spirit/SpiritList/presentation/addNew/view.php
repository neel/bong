		<li class="bong-admin-sidebar-components-item bong-admin-sidebar-components-item-spcl">
			<a class="bong-admin-sidebar-component-more"></a>
			<div class="bong-admin-sidebar-component-add">
				Add New
				<script id='spiritDiv' language="text/plain">
				<?php $controller->spirit('SpiritList')->addNewForm() ?>
				</script>
				<script type="text/bongscript" event="click" id="62">
					bong.dialog({
						title: 'New Spirit',
						content: document.getElementById('spiritDiv').innerHTML,
						buttons: [{
							label: 'Create',
							isDefault: true,
							action: function(){
								bong.href('<?php echo Resource::link() ?>/project/addSpirit/').post(this.spirit).eval();
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
			</div>
		</li>
