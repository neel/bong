	<ul class="bong-admin-sidebar-components bong-admin-sidebar-components-controller" id="sidebar_controller">
		<li class="bong-admin-sidebar-components-label">Controllers</li>
		<?php foreach($data->controllers as $con): ?>	
			<li class="bong-admin-sidebar-components-item <?php echo ($xdo->controllerName && $xdo->controllerName == $con->name() ? 'bong-admin-sidebar-components-item-selected' : null) ?>">
				<a href="<?php echo Resource::link('/project/controller/'.$con->name()); ?>"><?php echo $con->name() ?>Controller</a>
				<a class="bong-admin-sidebar-components-item-cross"></a>
			</li>
		<?php endforeach; ?>
		<?php $controller->spirit('ControllerList')->addNew() ?>
	</ul>
