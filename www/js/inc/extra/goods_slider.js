js.module("inc.extra.goods_slider");
js.include("jquery.ui.tabs");
$(function() {
	$(".extra_products_inner_content .scrollable").scrollable({
		next:'.extra_products_next',
		prev:'.extra_products_prev',
		circular:true,
		onSeek: function(event, pageIndex){
			$('.extra_products_title em:first').html(pageIndex+1);
		}
	});
});
