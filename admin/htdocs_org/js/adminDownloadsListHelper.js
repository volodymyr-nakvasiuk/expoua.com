Shelby_Backend.DownloadsListHelper = new Object();

//Путь от корня веб-сервера, куда была установлена система
Shelby_Backend.DownloadsListHelper.docRoot = null;

//Url фида списка изображений
Shelby_Backend.DownloadsListHelper.feedImagesUrl = null;

//Url для загрузки изображений
Shelby_Backend.DownloadsListHelper.imagesUploadUrl = null;

//Базовый путь к картинкам
Shelby_Backend.DownloadsListHelper.baseImagesUrl = null;

//Имя объекта, в котором находятся функциия работы со списком и доступного из нового окна
Shelby_Backend.DownloadsListHelper.clonedObjName = 'obj_shelby';

//Ссылка на объект, в который помещаются все данные
Shelby_Backend.DownloadsListHelper.dataObj = null;

//Текущий путь
Shelby_Backend.DownloadsListHelper.imagesListParent = '';

//Список колонок
Shelby_Backend.DownloadsListHelper.columns = new Array(new Array('type', 'Тип'), new Array('name', 'Имя файла'), new Array('size', 'Размер'));

//Вызывается визуальным редактором для открытия нового окна браузера
Shelby_Backend.DownloadsListHelper.browseWindowCall = function(field_name, url, type, win) {
    tinyMCE.openWindow({
        file : this.docRoot + "js/adminDownloadsListHelper.html",
        title : "File Browser",
        width : 360,
        height : 600,
        close_previous : "no"
    }, {
        obj_instance : this,
        images_base_path : this.baseImagesUrl,
        input : field_name,
        window_obj : win,
        editor_id : tinyMCE.selectedInstance.editorId
    });
    return false;
}

Shelby_Backend.DownloadsListHelper.createTable = function(parent) {

	var url = this.feedImagesUrl;

	if (parent) {
		url += "parent/" + parent + "/";
	}

	//Сохраняем ссылку на объект для дальнейшего использования
	Shelby_Backend._objListRefer = this;

	$.getJSON(url, function(json) {
			var html_data = '';
			var parent = '';

			html_data += Shelby_Backend._objListRefer.createTrail(json.trail);

			html_data += '<div style="overflow:auto; height:450px; width:100%;">';
			html_data += '<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>';

			for (i=0; i<Shelby_Backend._objListRefer.columns.length; i++) {
				html_data += '<td align="center"><b>' + Shelby_Backend._objListRefer.columns[i][1] + '</b></td>';
			}

			html_data += '</tr>';

			$.each(json.trail, function(i) {
				parent += json.trail[i] + ":";
			});

			$.each(json.list.data, function(i) {

				html_data += '<tr style="cursor:pointer; background-color:#' + (i%2==0 ? "DDDDDD":"EEEEEE") + ';" onClick="';
				if (json.list.data[i]['type'] == 'dir') {
					html_data += Shelby_Backend._objListRefer.clonedObjName + '.createTable(\'' + parent + json.list.data[i]['name'] + '\');">';
				} else {
					html_data += 'selectEntry(\'' + json.list.data[i]['name'] + '\', \'' + parent + '\');">';
				}

				for (ii=0; ii<Shelby_Backend._objListRefer.columns.length; ii++) {
					if (Shelby_Backend._objListRefer.columns[ii][0] == 'type') {
						html_data += '<td align="center"><img src="../images/admin/icons/';
						if (json.list.data[i]['type'] == 'dir') {
							html_data += 'icon_folder.gif"></td>';
						} else {
							html_data += 'icon_file.gif"></td>';
						}
					} else {
						html_data += '<td>' + json.list.data[i][Shelby_Backend._objListRefer.columns[ii][0]] + '</td>';
					}
				}

				html_data += '</tr>';
			});

			html_data += '</table></div>';

			html_data += Shelby_Backend._objListRefer.createUploadForm();

			Shelby_Backend._objListRefer.dataObj.innerHTML = html_data;
		});
}

Shelby_Backend.DownloadsListHelper.createTrail = function(trailArray) {
	var html_data = '';
	var parent = '';

	html_data = '<div style="padding-bottom:5px;"><a onClick="' + this.clonedObjName + '.createTable();" href="#">Корень</a>';

	$.each(trailArray, function(i) {

		if (i>0) {
			parent += ":";
		}
		parent += trailArray[i];

		html_data += ' -&gt; <a onClick="' + Shelby_Backend._objListRefer.clonedObjName + '.createTable(\'' + parent + '\');" href="#">' + trailArray[i] + '</a>';
	});

	html_data += '</div>';

	this.imagesListParent = parent;

	return html_data;
}

Shelby_Backend.DownloadsListHelper.createUploadForm = function() {
	var html_data = '';

	return html_data;
}