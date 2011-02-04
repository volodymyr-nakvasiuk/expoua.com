js.module("inc.filter.companies");
$(function(){
	$("#filter_category").change(function(){
		var $filter =$("#top .filter");
		var input;
		var redirectUrl = '/'+lang_id+'/companies/online/';
		for(var i in phpParams['filterParams']){
			input = $filter.find('[name='+i+']');
			if (input){
				var value = input.val();
				if (value!='' && value!=input.attr('emptyval')){
					redirectUrl += input.attr('name')+'-'+value+'/';
				}
			}
		}
		window.location.href = redirectUrl;
	});
});
$(window).load(function(){
	var $filter =$("#top .filter");
	for (var i in phpParams['filter']){
		$filter.find('[name='+i+']').attr('init',phpParams['filter'][i]).val(phpParams['filter'][i]);//.change();
	}
	$filter.find('[emptyval]').each(function(){
		if ($(this).val()=='') $(this).val($(this).attr('emptyval'));
	});
});
