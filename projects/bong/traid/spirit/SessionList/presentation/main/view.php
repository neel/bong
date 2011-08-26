<h6 class="bong-admin-component-headline bong-admin-component-session-headline">User Sessions</h6>
<?php
$sessionDir = \Path::instance()->currentProject('run');
$_it = new DirectoryIterator("glob://$sessionDir/*.usr");
foreach($_it as $_file): ?>
<a class="bong-admin-component-xdo bong-admin-component-session" href="#"><?php echo $_file->getBasename('.usr'); ?>
	<script type="text/bongscript" event="click">
		bong.href("<?php echo Resource::link() ?>/+SessionList.res/desc/<?php echo $_file->getBasename('.usr'); ?>").eval()
	</script>
</a>
<?php endforeach; ?>
<a class="bong-admin-component-xdo-more"></a>
