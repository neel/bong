$(document).ready(function(){
	$('.bong-admin-sidebar-components').mouseenter(function(){
		var label = $($(this).find('.bong-admin-sidebar-components-label'));
		$(this).css('padding-top', 25);
		label.css('opacity', 1.0);
	});
	$('.bong-admin-sidebar-components').mouseleave(function(){
		var label = $($(this).find('.bong-admin-sidebar-components-label'));
		label.css('opacity', 0.08);
		$(this).css('padding-top', 3);
	});
	resizeCentralDiv = function(){
		var panels = {};
		panels.document = $('body').innerWidth();
		panels.sidebar = $('.bong-admin-sidebar').outerWidth();
		panels.right = $('.bong-admin-central-right').outerWidth();
		console.log(panels);
		$('.bong-admin-central-main').css('width', (panels.document-(panels.sidebar+panels.right))-180);
	}
	window.onresize = resizeCentralDiv;
	resizeCentralDiv();
	window.setTimeout(resizeCentralDiv, 2000);
});
