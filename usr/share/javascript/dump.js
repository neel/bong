$(document).ready(function(){
	$(".dumpWindowMax").click(function(event){
		var d = $(this).parent().parent().parent().css('display');
		if(d == 'inline-block'){
			$(this).parent().parent().parent().css('display', 'block');
		}else{
			$(this).parent().parent().parent().css('display', 'inline-block');
		}
	});
	$(".dumpWindowFloat").click(function(event){
		var w = $(this).parent().parent().parent();
		if(w.attr('floatable') == 't'){
			w.attr('floatable', 'f');
			w.css('position', 'static');
		}else{
			w.attr('floatable', 't');
			w.css('position', 'absolute');
		}
	});
	$(".dumpWindowTitle").mousedown(function(e){
		var o = $(this).parent()[0];
		if($(o).attr('floatable') == 't'){
			gap = {
				w: e.clientX-o.offsetLeft,
				h: e.clientY-o.offsetTop
			};
			o.isDraggable = true;
			o.style.opacity = 0.5;
			var bak = document.onmousemove;
			var baku = document.onmouseup;
			document.onmouseup = function(ev){
				o.isDraggable = false;
				o.style.opacity = 1.0;
				document.onmousemove = bak;
				document.onmouseup = baku;
			}
			document.onmousemove = function(ev){
				if(o.isDraggable){
					o.style.left = ev.clientX-gap.w;
					o.style.top = ev.clientY-gap.h;
				}else{
					console.log(o);
				}
			}
		}
	});
	$(".node").click(function(event){
		if(!$(this).attr('shown') || $(this).attr('shown') == 'f'){
			$(this).attr('shown', 't');
			$(this).children(".node").css('display', 'block');
			$(this).children(".node").each(function(){
				if($(this).children(".line").children(".elem")[0])
					$(this).children(".line").children(".elem")[0].innerHTML = "Elements ("+$(this).children(".node").length+")";
				if($(this).children(".node").length == 0){
					$(this).children(".fold").addClass("disabled");
				}
			});
		}else{
			$(this).attr('shown', 'f');
			$(this).children(".node").css('display', 'none');					
		}
		event.stopPropagation();
	})
});
