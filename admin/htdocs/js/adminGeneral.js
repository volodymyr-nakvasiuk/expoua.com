var Shelby_Backend = new Object();

//Установленный фильтр поиска
Shelby_Backend.currentSearchFilter = null;

Shelby_Backend.confirmDelete = function(lang) {
  if (lang == 'ru') {
    return confirm('Вы действительно хотите удалить эту запись?');
  } else {
    return confirm('Are you sure you want to delete this item?');
  }
}

Shelby_Backend.confirmArchive = function(lang) {
  if (lang == 'ru') {
  	return confirm('Вы действительно хотите поместить эту запись в архив?');
  } else {
    return confirm('Are you sure you want to put this item into archive?');
  }
}

Shelby_Backend.objects_multi_checkbox = function(id, status) {
	var obj = document.getElementById(id);
	if (status==true) {
		obj.value=1;
	} else {
		obj.value=0;
	}
}

Shelby_Backend.objects_show_hidden = function(name) {
	var obj = document.getElementsByName('objects_hidden');
	var len = obj.length;

	for (i=0; i<len; i++) {
		obj[i].style.visibility = 'visible';
		obj[i].style.display = 'block';
	}
}

Shelby_Backend.toggle_search = function(name) {
	name = "#list_header_search_div_"+name;

	if ($(name).css("display")=="none") {
		$(name).css("display", "inline");
	} else {
		$(name).css("display", "none");
	}
}

Shelby_Backend.table_header_search = function(baseUrl, name, stype) {
  stype = stype ? stype : ":";
	kw = $("#list_header_search_kw_"+name).attr("value");
	
	if (kw=="" || kw==undefined) {
		document.location.href=baseUrl;
	} else {
		document.location.href=baseUrl+"search/" + name + (name=='name' || name=='description' ? "~":stype) + kw + "/";
	}
}

Shelby_Backend.table_header_search_dates_duo = function(baseUrl) {
	var url_from = '', url_to = '', url = '';

	kw_from = $("#list_header_search_date_from").attr("value");
	kw_to = $("#list_header_search_date_to").attr("value");

	if ((kw_from=="" || kw_from==undefined) && (kw_to=="" || kw_to==undefined)) {
		document.location.href=baseUrl;
	} else {
		if (kw_from!="" && kw_from!=undefined) {
			url_from = "date_from" + ">" + kw_from;
		}

		if (kw_to!="" && kw_to!=undefined) {
			url_to = "date_to" + "<" + kw_to;
		}

		if (url_from!='' && url_to!='') {
			url_from += ';';
		}

		url = baseUrl + "search/" + encodeURIComponent(url_from + url_to) + '/';
		document.location.href = url;
	}
}

Shelby_Backend.leftMenuHS = function() {
	if ($("#shelby_left_menu").css("display")=="none") {
		$("#shelby_left_menu_td").width("160px");
		$("#shelby_left_menu").width("160px");
		$("#shelby_left_menu").css("display", "inline");
		this.createCookie("shelby_left_menu", "show");
	} else {
		$("#shelby_left_menu_td").width("10px");
		$("#shelby_left_menu").css("display", "none");
		this.createCookie("shelby_left_menu", "hide");
	}
}

//Функция подготовки поискового параметра
Shelby_Backend.createSearchParam = function(column, value, type, addFilter) {
	var res = column + type + value;

	if (addFilter == true && this.currentSearchFilter != null) {
		var prevFilter = this.currentSearchFilter;

		if (prevFilter.indexOf(column) != -1) {
			var start = prevFilter.indexOf(column);
			var end = prevFilter.indexOf(";", start);

			if (end == -1) {
				end = prevFilter.length;
			}

			//alert(prevFilter.substring(start, end));
			res = prevFilter.replace(prevFilter.substring(start, end), res);
		} else {
			res += ";" + prevFilter;
		}
	}

	return res;
}

//Work with cookies
Shelby_Backend.createCookie = function(name, value, days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	} else {
		var expires = "";
	}
	document.cookie = name+"="+value+expires+"; path=/";
}

Shelby_Backend.readCookie = function(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') {
			c = c.substring(1, c.length);
		}
		if (c.indexOf(nameEQ) == 0) {
			return c.substring(nameEQ.length, c.length);
		}
	}
	return null;
}

Shelby_Backend.eraseCookie = function(name) {
	this.createCookie(name, "" ,-1);
}

//Функция клонирования объекта
Shelby_Backend.clone = function(obj) {
	if(obj == null || typeof(obj) != 'object') {
		return obj;
	}

	var temp = {};
	for(var key in obj) {
		temp[key] = this.clone(obj[key]);
	}

	return temp;
}
