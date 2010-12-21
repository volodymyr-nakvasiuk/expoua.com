objCompaniesList = Shelby_Backend.ListHelper.cloneObject('objCompaniesList');
objCompaniesList.columns = new Array(new Array('id', 'Id'), new Array('name', 'Название'), new Array('advertiser_name', 'Рекламодатель'));
objCompaniesList.returnFieldId = 'companies_id';
objCompaniesList.feedUrl = '{getUrl controller="admin_ep_banners_companies" action="list" feed="json"}';
objCompaniesList.writeForm();

{literal}
$(document).ready(function(){
	$('#date_from').datepicker();
	$('#date_to').datepicker();
});

objCompaniesList.callbackUser = function() {
	bannersObj.getBannersList();
}

var bannersObj = new Object();

bannersObj.getBannersList = function() {
	var places_id = $("#places_id").val();
	var companies_id = $("#companies_id").val();
	var url = '{/literal}{getUrl controller="admin_ep_banners_banners" action="list" results="1000" feed="json"}{literal}';

	if (places_id>0 && companies_id>0) {
		url += 'search/companies_id=' + companies_id + ';places_id=' + places_id + '/';
		$("#banners_holder select").empty();
		$.getJSON(url, function(json) {
			var banners_list = '<option value="-1">(Не выбран)</option>';
			$.each(json.list.data, function(i) {
				banners_list += '<option value="' + json.list.data[i].id + '">' + json.list.data[i].name + '</option>';
			});
			$("#banners_holder select").append(banners_list);
			$("#banners_holder select").removeAttr('disabled');
			bannersObj.bannersListComplete();
		});
	} else {
		$("#banners_holder select").empty();
		$("#banners_holder select").attr('disabled', 'disabled');
	}

	if (places_id>0) {
		this.getPublishersList(places_id);
	}
}

bannersObj.getPublishersList = function(places_id) {
	var url = '{/literal}{getUrl controller="admin_ep_banners_publishers" action="list" results="1000" feed="json"}{literal}search/places_id=' + places_id + '/';
	$("#publishers_id").empty();
	$.getJSON(url, function(json) {
		$.each(json.list.data, function(i) {
			$("#publishers_id").append('<option value="' + json.list.data[i].id + '">' + json.list.data[i].name + '</option>');
		});
		bannersObj.publishersListComplete();
	});
}

bannersObj.bannersListComplete = function() {}
bannersObj.publishersListComplete = function() {}

bannersObj.toggleDummy = function(checked) {
	if (checked) {
		$("#priority").attr("disabled","disabled");
	} else {
		$("#priority").removeAttr('disabled');
	}
}

bannersObj.validateForm = function() {
	var err='';
	if ($("#companies_id").val() == '') {
		err += "- Не выбрана кампания\n";
	}
	if ($("#places_id").val()<1) {
		err += "- Не выбрано баннероместо\n";
	}
	if ($("#banners_holder SELECT[value!=-1]").length == 0) {
		err += "- Не выбран баннер\n";
	}
	if ($("#modules_id option").length == $("#modules_id option:selected").length) {
		err += "- Выделены все модули\n";
	}
	if ($("#categories_id option").length == $("#categories_id option:selected").length) {
		err += "- Выделены все категории\n";
	}

	if (err != '') {
		err = "При заполнении формы возникли ошибки:\n\n" + err;
		alert(err);
		return false;
	}
	return true;
}
{/literal}
