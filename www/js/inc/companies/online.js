js.module("inc.companies.online");
js.include("jquery.tooltip_online");
$(function (){
	$(".tooltip_box").tooltip({
		track: false,
		delay: 500,
		showURL: false,
		//fixPNG: true,
		extraClass: "online_tooltip",
		bodyHandler: function() {
			return $(this).parent().find('.tooltip_content').html();
		},
		keepShowed:true,
		closeButton: ".tooltip_close",
		top: 5,
		left: 5
	});
});