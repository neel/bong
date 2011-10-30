<?php http::contentType('text/html') ?>
<html>
	<head>
		<title></title>
		<link type="text/css" href=""/> 
		<style type="text/css">
			body{
				margin-top: 30px;
				text-align:center;	
				background-color: #627AAD;
			}
			#graph{
				background-color: #D9E0EA;
				margin: auto;
				-webkit-border-radius: 12px;
				-moz-border-radius: 12px;
				border-radius: 12px;
			}
		</style>
		<script language="javascript" type="text/javascript" src="<?php echo SysResource::js('bong.bootstrap') ?>"></script>
		<script language="javascript" type="text/javascript" src="<?php echo SysResource::js('graph/raphael_min') ?>"></script>
		<script language="javascript" type="text/javascript" src="<?php echo SysResource::js('graph/dracula_graffle') ?>"></script>
		<script language="javascript" type="text/javascript" src="<?php echo SysResource::js('graph/dracula_graph') ?>"></script>
		<script language="javascript" type="text/javascript">
			var gContainer = {
				size: {
					heigth: null,
					width: null
				}	
			}
			var drawGraph = function() {
				var g = new Graph();
				<?php foreach($fsm as $state): ?>
				g.addNode('q<?php echo $state->id() ?>', {tooltip: '<?php echo ($state->method() == \Rom\Request::GET ? 'GET' : 'POST') .' '.$state->url() ?>'});
				<?php endforeach; ?>
				
				<?php foreach($fsm as $state): ?>
					<?php if($state->outDegree() > 0): ?>
						<?php $inNode = $state->id() ?>
						<?php foreach($state->children() as $child): ?>
							<?php $outNode = $child->id(); ?>
							g.addEdge('q<?php echo $inNode ?>', 'q<?php echo $outNode ?>', {directed: true});
						<?php endforeach ?>					
					<?php endif; ?>
				<?php endforeach; ?>
				
				
				var layouter = new Graph.Layout.Spring(g);
				layouter.layout();
				var renderer = new Graph.Renderer.Raphael('graph', g, gContainer.size.width, gContainer.size.height);
				renderer.draw();
			};
			
			bong.onready(function(){
				var resizeContainer = function(){
					var graph = bong.byId('graph');
					gContainer.size.height = bong.viewport.height()-70;
					gContainer.size.width = bong.viewport.width()-70;
					graph.style.height = gContainer.size.height;
					graph.style.width = gContainer.size.width;
				}
				bong.addEvent(window, 'resize', resizeContainer);
				resizeContainer();
				drawGraph();
			});
		</script>
	</head>
	<body> 
		<div id="graph"></div>
	</body>
</html>
