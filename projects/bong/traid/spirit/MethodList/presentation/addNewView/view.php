				<button class="bong-dialog-btn bong-dialog-btn-default bong-admin-component-method-view-new">Add New
					<script type="text/bongscript" event="click">
					var self = this;
					bong.dialog({
						title: 'New View for <?php echo (get_class($data->controller) == 'Structs\Admin\SpiritController' ? 'Spirit' : 'Controller') ?> Method <?php echo $data->methodName ?>',
						width: 400,
						content: '<center>View Name: <input type="hidden" bong:handle="methodName" value="<?php echo $data->methodName ?>"><input type="text" bong:handle="viewName" value="" /></center>',
						buttons: [{
							label: 'Create',
							isDefault: true,
							action: function(){
								var methodName = this.methodName.value;
								var viewName = this.viewName.value;
								bong.href('<?php echo Resource::link() ?>/project/<?php echo (get_class($data->controller) == 'Structs\Admin\SpiritController' ? 'addSpiritMethodView' : 'addControllerView') ?>/'+this.methodName.value+'/'+this.viewName.value).async(function(data){
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
											bong.href('<?php echo Resource::link() ?>/source/<?php echo (get_class($data->controller) == 'Structs\Admin\SpiritController' ? 'spiritView' : 'view') ?>/'+methodName+'/'+viewName).eval();
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
					</script>
				</button>
