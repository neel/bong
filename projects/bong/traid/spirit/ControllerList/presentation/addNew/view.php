		<li class="bong-admin-sidebar-components-item bong-admin-sidebar-components-item-spcl">
			<a class="bong-admin-sidebar-component-more"></a>
			<div class="bong-admin-sidebar-component-add">
				Add New
				<script type="text/bongscript" event="click">
					bong.dialog({
						title: 'New Controller',
						content: 'Controller Name: <input type="text" bong:handle="controllerName" value="" />',
						buttons: [{
							label: 'Create',
							isDefault: true,
							action: function(){
								bong.href('/bong/~bong/project/addController/'+this.controllerName.value).eval();
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
