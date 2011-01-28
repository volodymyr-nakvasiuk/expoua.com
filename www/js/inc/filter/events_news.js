js.module("inc.filter.events");
$(function(){
	$("#sub_filter_flipper").click(function(){
		var $sub = $("#filter_sub");
		if ($sub.attr('vis')!='0'){
			$sub.attr('vis', '0');
			$sub.slideUp();
			$(this).parent().children().each(function(){
				if ($(this).html()=='↑')$(this).html('↓');
			});
		}
		else {
			$sub.attr('vis', '1');
			$sub.slideDown();
			$(this).parent().children().each(function(){
				if ($(this).html()=='↓')$(this).html('↑');
			});
		}
	});

	$("#filter_category").change(function(){
		$("#filter_subcategory").attr('disabled','disabled').children(":not(:first)").remove();
		if ($(this).val()!=''){
			$.ajax({
				data: {'parent[0]':$(this).val()},
				url: '/'+lang_id+'/ajax/get/subcategories/',
				type: 'POST',
				beforeSend: function($form, options) {
					//$('#loading').show();
					return true;
				},
				complete: function(data){
					//$('#loading').hide();
					if (data.status != 200) return false;
					var response = data.responseText;
					if (response.isJSON()) response = eval('('+response+')');
					else return false;
					if (response.success && response.data){
						var $subcategory = $("#filter_subcategory");
						for(var i in response.data){
							$subcategory.append('<option value="'+i+'">'+response.data[i]+'</option>');
						}
						var initValue = $subcategory.attr('init');
						if (initValue){
							$subcategory.removeAttr('init').val(initValue).change();
						}
						$subcategory.removeAttr('disabled');
					}
					else return false;
				}
			});
		}
	});

	$("#filter_region").change(function(){
		$("#filter_country").attr('disabled','disabled').children(":not(:first)").remove();
		$("#filter_country").change();
		if ($(this).val()!=''){
			$.ajax({
				data: {'parent[0]':$(this).val()},
				url: '/'+lang_id+'/ajax/get/countries/',
				type: 'POST',
				beforeSend: function($form, options) {
					//$('#loading').show();
					return true;
				},
				complete: function(data){
					//$('#loading').hide();
					if (data.status != 200) return false;
					var response = data.responseText;
					if (response.isJSON()) response = eval('('+response+')');
					else return false;
					if (response.success && response.data){
						var $subcategory = $("#filter_country");
						for(var i in response.data){
							$subcategory.append('<option value="'+i+'">'+response.data[i]+'</option>');
						}
						var initValue = $subcategory.attr('init');
						if (initValue){
							$subcategory.removeAttr('init').val(initValue).change();
						}
						$subcategory.removeAttr('disabled');
					}
					else return false;
				}
			});
		}
	});

	$("#filter_country").change(function(){
		$("#filter_city").attr('disabled','disabled').children(":not(:first)").remove();
		if ($(this).val()!=''){
			$.ajax({
				data: {'parent[0]':$("#filter_region").val(),'parent[1]':$(this).val()},
				url: '/'+lang_id+'/ajax/get/cities/',
				type: 'POST',
				beforeSend: function($form, options) {
					//$('#loading').show();
					return true;
				},
				complete: function(data){
					//$('#loading').hide();
					if (data.status != 200) return false;
					var response = data.responseText;
					if (response.isJSON()) response = eval('('+response+')');
					else return false;
					if (response.success && response.data){
						var $subcategory = $("#filter_city");
						for(var i in response.data){
							$subcategory.append('<option value="'+i+'">'+response.data[i]+'</option>');
						}
						var initValue = $subcategory.attr('init');
						if (initValue){
							$subcategory.removeAttr('init').val(initValue).change();
						}
						$subcategory.removeAttr('disabled');
					}
					else return false;
				}
			});
		}
	});
	
	$("#filter_button").click(function(){
		var $filter =$("#top .filter");
		var input;
		var redirectUrl = '/'+lang_id+'/event/news/';
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
	$("#filter_search").keypress(function(e){
		var code = (e.keyCode ? e.keyCode : e.which);
		if(code == 13) {
			$("#filter_button").click();
		}
	}).blur();
});
$(window).load(function(){
	var $filter =$("#top .filter");
	for (var i in phpParams['filter']){
		$filter.find('[name='+i+']').attr('init',phpParams['filter'][i]).val(phpParams['filter'][i]).change();
	}
	$filter.find('[emptyval]').each(function(){
		if ($(this).val()=='') $(this).val($(this).attr('emptyval'));
	});
});