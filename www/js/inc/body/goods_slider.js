js.module("inc.body.goods_slider");
js.include("jquery.ui.tabs");
$(function() {
	$(".body_products_inner_content .scrollable").scrollable({
		next:'.body_products_next',
		prev:'.body_products_prev',
		circular:true,
		onSeek: function(event, pageIndex){
			$('.body_products_title em:first').html(pageIndex+1);
		}
	});
});
