js.module("inc.companies.card");
js.include("jquery.tooltip");
function tooltip_init(){
	$(".tooltip_box").tooltip({
		track: false,
		delay: 500,
		showURL: false,
		//fixPNG: true,
		extraClass: "online_tooltip",
		bodyHandler: function() {
			return $(this).parent().parent().find('.tooltip_content').html();
		},
		keepShowed:true,
		closeButton: ".tooltip_close",
		top: 5,
		left: 5
	});
}

$(function (){
	tooltip_init();
});