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
		top: 5,
		left: 5
	});
}

$(function (){
	//if (getcookie('info_admin') != ''){
	//}
	//console.log(getcookie('info_admin'));
	tooltip_init();
});