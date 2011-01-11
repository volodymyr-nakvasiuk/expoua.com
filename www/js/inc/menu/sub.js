js.module("inc.menu.sub");
js.include("jquery.fg.menu");
$(function() {
	$("#dropdown_menu_btn").menu({
		content: $('#dropdown_menu_content').html(),
		showSpeed: 400,
		width:420
	});
});
