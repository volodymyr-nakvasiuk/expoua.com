Shelby_Backend.Autocomplete = new Object();

//Autocomplete
//Номер выбранного в данный момент элемента списка
Shelby_Backend.Autocomplete.SelectedTag = 0;

//Количество элементов в результате поиска
Shelby_Backend.Autocomplete.ResultsNum = 0;

//Строка предыдущего поиска
Shelby_Backend.Autocomplete.PrevSearch = '';

//Объект результата
Shelby_Backend.Autocomplete.jsonDataObj = null;

//Массив, устанавливающий связь между выбранным элементом выпадающего списка и элементом в побъекте данных
Shelby_Backend.Autocomplete.SelTagToJsonDOId = new Array();

//Url фида
Shelby_Backend.Autocomplete.feedUrl = null;

//Базовый Url поиска
Shelby_Backend.Autocomplete.baseSearchUrl = null;

//Имя колонки, по которой производится поиск
Shelby_Backend.Autocomplete.columnName = null;

//Название клонированного объекта
Shelby_Backend.Autocomplete.clonedObjName = null;

//Постоянный фильтр выборки
Shelby_Backend.Autocomplete.persistentFilter = null;

//Должен быть вызван всякий раз при создании объекта
Shelby_Backend.Autocomplete.cloneObject = function(name) {
	this.clonedObjName = name;

	return Shelby_Backend.clone(this);
}

//Рисуем форму подсказок
Shelby_Backend.Autocomplete.writeForm = function() {
	document.write('<DIV style="clear:both; display:none;" id="' + this.clonedObjName + '_form_id">' +
   '<INPUT type="text" style="width:90%;" onKeyUp="' + this.clonedObjName + '.getData(this.value, event);" id="' + this.clonedObjName + '_input_id"><br />' +
   'Добавить: <input type="checkbox" id="' + this.clonedObjName + '_addFilterFlag_id">' +
	'<div id="' + this.clonedObjName + '_popup_id" style="position:absolute; border: 1px solid #6f5d15; width:250px; background-color:white; visibility:hidden; float:left;"></div></DIV>');
}

Shelby_Backend.Autocomplete.toggleForm = function() {
	name = "#" + this.clonedObjName + '_form_id';

	if ($(name).css("display")=="none") {
		$(name).css("display", "inline");
	} else {
		$(name).css("display", "none");
	}
}

Shelby_Backend.Autocomplete.getData = function(search_str, event_obj) {

	switch (event_obj.keyCode) {
		case 38:
			this.SelectedTag--;
			if (this.SelectedTag<1) {
				this.SelectedTag = this.ResultsNum-1;
			}
			this.selectLine(this.SelectedTag);
			break;
		case 40:
			this.SelectedTag++;
			if (this.SelectedTag>=this.ResultsNum) {
				this.SelectedTag = 1;
			}
			this.selectLine(this.SelectedTag);
			break;
		case 13:
			if ((this.SelectedTag>0) && (this.SelectedTag<=this.ResultsNum)) {
				this.pickElement(this.SelectedTag);
			}
			break;
		default:

			if (search_str==this.PrevSearch) {
				return;
			}

			this.PrevSearch = search_str;

			//Сохраняем ссылку на объект для дальнейшего использования
			Shelby_Backend._objListRefer = this;

			//Минимум 3 символа
			if (search_str.length>=3) {
				$.getJSON(this.feedUrl + 'search/name~' + encodeURIComponent(search_str) + (this.persistentFilter == null ? "":";" + this.persistentFilter) + "/", function(json) {

					Shelby_Backend._objListRefer.jsonDataObj = json.list.data;

					if (json.list.rows>0) {
						var ii=0, name = '';
						cont = '<table width="100%" cellspacing="0" cellpadding="1">';

						$.each(json.list.data, function(i) {
							ii++;
							idtr = Shelby_Backend._objListRefer.clonedObjName + "_popup_tr_id_" + ii;

							name = json.list.data[i].name;
							if (json.list.data[i].city_name) {
								name += ", " + json.list.data[i].city_name;
							}

							cont += '<tr class="popupAutocomplete" id="' + idtr + '" onMouseOver="' + Shelby_Backend._objListRefer.clonedObjName + '.selectLine('+ ii +');" onClick="' + Shelby_Backend._objListRefer.clonedObjName + '.pickElement('+ ii +');"><td>' + name + "</td></tr>";

							Shelby_Backend._objListRefer.SelTagToJsonDOId[ii] = json.list.data[i].id;
						});

						cont += '</table>';
						$('#' + Shelby_Backend._objListRefer.clonedObjName + '_popup_id').html(cont);

						Shelby_Backend._objListRefer.showPopUp();
					} else {
						Shelby_Backend._objListRefer.hidePopUp();
					}

					Shelby_Backend._objListRefer.ResultsNum = ii+1;

				});
			} else {
				this.hidePopUp();
			}
	}
}

Shelby_Backend.Autocomplete.hidePopUp = function() {
	$('#' + this.clonedObjName + '_popup_id').css('visibility', "hidden");
	this.ResultsNum = 0;
	this.SelectedTag = 0;
}

Shelby_Backend.Autocomplete.showPopUp = function() {
	$('#' + this.clonedObjName + '_popup_id').css('visibility', "visible");
}

Shelby_Backend.Autocomplete.pickElement = function(i) {
	var jsonElId = this.SelTagToJsonDOId[i];
	var url = this.baseSearchUrl + "search/";
	var addSearchFilter = false;

	$('#' + this.clonedObjName + '_input_id').attr("value", this.jsonDataObj[jsonElId].name);

	if ($('#' + this.clonedObjName + '_addFilterFlag_id').attr('checked') == true) {
		//url += Shelby_Backend.currentSearchFilter + ";";
		addSearchFilter = true;
	}

	url += Shelby_Backend.createSearchParam(this.columnName, this.jsonDataObj[jsonElId].id, "=", addSearchFilter);

	this.hidePopUp();

	document.location.href = url + "/";
}

Shelby_Backend.Autocomplete.selectLine = function(i) {
	$("tr.popupAutocomplete").css("background", '#FFFFFF');
	$("tr.popupAutocomplete td").css("color", '#474747');

	$("#" + this.clonedObjName + "_popup_tr_id_" + i).css("background", '#6F5D15');
	$("#" + this.clonedObjName + "_popup_tr_id_" + i + " td").css("color", '#FFFFFF');
	this.SelectedTag = i;
}
