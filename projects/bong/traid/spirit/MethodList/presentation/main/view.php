		<h6 class="bong-admin-component-headline bong-admin-component-method-headline">Methods<?php $controller->spirit('MethodList')->addNew() ?></h6>
		<div class="bong-admin-component-method bong-admin-component-method-hide"></div>
		<?php foreach($data->controller->methods() as $method): ?>
		<div class="bong-admin-component-method">
			<a class="bong-admin-component-method-cross"></a>
			<div class="bong-admin-component-method-layout-area">
				<?php if($method->type() == Structs\Admin\Method::ControllerMethod): ?>
				
				<a class="bong-admin-component-method-layout-show bong-admin-component-method-layout-<?php echo !$method->hasLayout() ? 'no' : '' ?>exists" href="#"></a>
				<a class="bong-admin-component-method-param-show bong-admin-component-method-param-<?php echo !$method->hasParams() ? 'no' : '' ?>exists" href="#"></a>
				
				<button class="bong-admin-component-method-layout-edit bong-dialog-btn bong-admin-component-method-layout-exists <?php echo (!$method->hasLayout()) ? 'bong-admin-component-method-hide' : '' ?>">
					<script type="text/bongscript" event="click">
						bong.href('/bong/~bong/source/controllerMethodLayout/<?php echo $method->name() ?>').eval();
					</script>
				</button>
				
				<button class="bong-admin-component-method-layout-edit bong-dialog-btn bong-admin-component-method-layout-noexists <?php echo ($method->hasLayout()) ? 'bong-admin-component-method-hide' : '' ?>">
					<script type="text/bongscript" event="click">
						var self = this;
						bong.href('/bong/~bong/project/createControllerMethodLayout/<?php echo $method->name() ?>').async(function(data){
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
					</script>	
				</button>
								
				<button class="bong-admin-component-method-param-edit bong-dialog-btn bong-admin-component-method-param-exists <?php echo (!$method->hasParams()) ? 'bong-admin-component-method-hide' : '' ?>">
					<script type="text/bongscript" event="click">
						bong.href('/bong/~bong/source/controllerMethodParams/<?php echo $method->name() ?>').eval();
					</script>
				</button>
				
				<button class="bong-admin-component-method-param-edit bong-dialog-btn bong-admin-component-method-param-noexists <?php echo ($method->hasParams()) ? 'bong-admin-component-method-hide' : '' ?>">
					<script type="text/bongscript" event="click">
						var self = this;
						bong.href('/bong/~bong/project/createControllerMethodParams/<?php echo $method->name() ?>').async(function(data){
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
					</script>	
				</button>
				
				<?php endif; ?>
			</div>
			<a class="bong-admin-component-method-name" href="#"><?php echo $method->name() ?>
				<script type="text/bongscript" event="click">
					bong.href('/bong/~bong/source/<?php echo ($method->type() == Structs\Admin\Method::ControllerMethod ? 'controllerMethod' : 'spiritMethod').'/'.$method->name() ?>/<?php echo $method->name() ?>').eval();
				</script>		
			</a>
			<?php if($method->type() == Structs\Admin\Method::ControllerMethod): ?>
			<a href="#" class="bong-admin-component-method-link">
				<script type="text/bongscript" event="click">
					bong.href('/bong/~bong/project/methodLink/<?php echo $method->name() ?>').eval();
				</script>
			</a>
			<?php endif; ?>
			<div class="bong-admin-component-method-views">
				<?php if($method->numViews() > 0): ?>
				<?php foreach($method->views() as $view): ?>
				<div class="bong-admin-component-method-view">
					<?php echo $view->name() ?>
					<script type="text/bongscript" event="click">
					bong.href('/bong/~bong/source/<?php echo ($method->type() == Structs\Admin\Method::ControllerMethod ? 'view' : 'spiritView') ?>/<?php echo $method->name() ?>/<?php echo $view->name() ?>').eval();
					</script>
					<a class="bong-admin-sidebar-components-view-cross"></a>
				</div>
				<?php endforeach; ?>
				<?php else: ?>
				<div class="bong-admin-component-method-noview">
				
				</div>
				<?php endif; ?>
				<?php $controller->spirit('MethodList')->addNewView($method->name()) ?>
			</div>
		</div>		
		<?php endforeach ?>
