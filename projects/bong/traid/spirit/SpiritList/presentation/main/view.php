	<ul class="bong-admin-sidebar-components bong-admin-sidebar-components-spirit" id="sidebar_spirit">
		<li class="bong-admin-sidebar-components-label">Spirits</li>
		<?php foreach($data->spirits as $spirit): ?>	
			<li class="bong-admin-sidebar-components-item">
				<a href="<?php echo Resource::link('/project/sspirit/'.$spirit->name()); ?>"><?php echo $spirit->name() ?>Abstractor</a>
				<a class="bong-admin-sidebar-components-item-cross"></a>
			</li>
		<?php endforeach; ?>
		<?php $controller->spirit('SpiritList')->addNew() ?>
	</ul>
