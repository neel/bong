Hallo <b id="name"><?php echo $xdo->name ?></b> You are <?php echo $xdo->age ?> Years Old
<?php $controller->spirit('demo')->main(); ?>
<br />
<?php /*$controller->spirit('demo')->age();*/ ?>
<input type="text" onkeyup="test()" id="nameT" value="<?php echo $xdo->name ?>" />
<script type="text/javascript">
function test(){
	bong.href('/bong/default.prop/main/'+escape(document.getElementById('nameT').value)+'/-name').invoke();
}
</script>
<script type="text/javascript">
bong.href('/bong/default.prop/-name').refresh([document.getElementById('name'), document.getElementById('nameT')]);
</script>
