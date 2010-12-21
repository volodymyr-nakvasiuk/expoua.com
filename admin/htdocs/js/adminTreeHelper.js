Shelby_Backend.TreeHelper = new Object();

//Флаг, указывающий на конец загрузки
Shelby_Backend.TreeHelper.loaded = 0;

//Уровень рекурсии функции построения дерева
Shelby_Backend.TreeHelper.branchLevel = 1;

//Url фида
Shelby_Backend.TreeHelper.feedUrl = null;

//Название клонированного объекта.
Shelby_Backend.TreeHelper.clonedObjName = null;

//Id поля, в которое нужно записать полученное значение
Shelby_Backend.TreeHelper.returnFieldId = null;

//Многомерный массив, содержащий названия столбцов
Shelby_Backend.TreeHelper.columns = new Array();

//Объект, содержащий текущие json-данные
Shelby_Backend.TreeHelper.jsonDataObj = null;

//Объект, содержащий текущие json-данные, приведенные к плоскому виду
Shelby_Backend.TreeHelper.jsonDataObjFlat = new Array();

//Должен быть вызван всякий раз при создании объекта
Shelby_Backend.TreeHelper.cloneObject = function(name) {
	this.clonedObjName = name;

	return Shelby_Backend.clone(this);
}

Shelby_Backend.TreeHelper.getAndWriteTree = function() {
	var url;

	$("#popUpLoadingAnimation_" + this.clonedObjName).css("display", "inline");

	//Сохраняем ссылку на объект для дальнейшего использования
	Shelby_Backend._objListRefer = this;

	url = this.feedUrl + 'feed/json/';

	$.getJSON(url, function(json) {
			var html_data = '';

			Shelby_Backend._objListRefer.jsonDataObj = json.tree;

			html_data += '<div style="overflow:auto; height:350px; width:100%;">' +
				'<div style="cursor:pointer;" onClick="' + Shelby_Backend._objListRefer.clonedObjName + '.selectEntry(null);"><b>Корень</b></div>';
			html_data += Shelby_Backend._objListRefer.createBranch(json.tree);
			html_data += '</div>';

			$('#popup_data_' + Shelby_Backend._objListRefer.clonedObjName).html(html_data);
			$("#popUpLoadingAnimation_" + Shelby_Backend._objListRefer.clonedObjName).css("display", "none");
		});
}

Shelby_Backend.TreeHelper.createBranch = function(data) {
	var html_data = '', i = 0, ii = 0;

	$.each(data, function(i) {
		html_data += '<div style="cursor:pointer; padding-left:' + Shelby_Backend._objListRefer.branchLevel*10 + 'px;" onClick="' + Shelby_Backend._objListRefer.clonedObjName + '.selectEntry(' + i + ');">';

		for (ii=0; ii<Shelby_Backend._objListRefer.columns.length; ii++) {
			if (Shelby_Backend._objListRefer.columns[ii] == 'active') {
				html_data += '<input type="checkbox" disabled ' + (data[i][Shelby_Backend._objListRefer.columns[ii]]==1 ? "checked":"") + '>';
			} else {
				html_data += ' ' + data[i][Shelby_Backend._objListRefer.columns[ii]];
			}

			Shelby_Backend._objListRefer.jsonDataObjFlat[i] = data[i];
		}
		html_data += '</div>';

		if (data[i].children) {
			Shelby_Backend._objListRefer.branchLevel++;
			html_data += Shelby_Backend._objListRefer.createBranch(data[i].children);
			Shelby_Backend._objListRefer.branchLevel--;
		}

	});

	return html_data;
}

Shelby_Backend.TreeHelper.selectEntry = function(i) {
	var ii=i, trail = '';

	$('#popup_window_' + this.clonedObjName).jqmHide();
	if (i==null) {
		$('#' + this.returnFieldId).attr("value", 'Корень');
		$('#' + this.returnFieldId + "_name").html('');
	} else {

		while (this.jsonDataObjFlat[ii]) {
			//alert(this.jsonDataObjFlat[ii].parent_id);
			trail = ' -&gt; ' + this.jsonDataObjFlat[ii].name + trail;
			ii = this.jsonDataObjFlat[ii].parent_id;
		}

		trail = 'Корень' + trail;

		$('#' + this.returnFieldId).attr("value", this.jsonDataObjFlat[i].id);
		$('#' + this.returnFieldId + "_name").html(trail);
	}

	this.callbackUser(this.jsonDataObjFlat[i]);
}

Shelby_Backend.TreeHelper.writeForm = function() {
	document.write('<DIV id="popup_window_' + this.clonedObjName + '" class="jqmWindow">' +
	'<div style="float:right;"><IMG src="' + Shelby_Backend.docRoot + 'images/admin/loadingAnimation.gif" style="display:none;" id="popUpLoadingAnimation_' + this.clonedObjName + '"> &nbsp; &nbsp; <a href="#" class="jqmClose"><b>X</b></a></div>' +
	'<DIV style="clear:both; border-bottom:1px solid #000000; padding-bottom:3px;"></DIV>' +
	'<DIV id="popup_data_' + this.clonedObjName + '"></DIV></DIV>');
}

Shelby_Backend.TreeHelper.showPopUp = function() {
	$('#popup_window_' + this.clonedObjName).jqm().jqmShow();
	if (this.loaded==0) {
		this.getAndWriteTree();
		this.loaded = 1;
	}
}

//Функция обратного пользовательского вызова.
Shelby_Backend.TreeHelper.callbackUser = function(entry) {
	//alert(entry.name);
}