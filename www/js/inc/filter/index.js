js.module("inc.filter.index");
$(function(){
	$("#filter_button").click(function(){
		var input = $('#filter_search');
		var value = input.val();
		if (value!='' && value!=input.attr('emptyval')){
			window.location.href = '/'+lang_id+$('#filter_module').val()+default_search+input.attr('name')+'-'+value+'/';
		}
	});
	$("#filter_search").keypress(function(e){
		var code = (e.keyCode ? e.keyCode : e.which);
		if(code == 13) {
			$("#filter_button").click();
		}
	}).blur();
});