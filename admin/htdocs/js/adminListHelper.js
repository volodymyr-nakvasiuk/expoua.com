Shelby_Backend.ListHelper = new Object();

//Флаг, указывающий на конец загрузки
Shelby_Backend.ListHelper.loaded = 0;

//Url фида
Shelby_Backend.ListHelper.feedUrl = null;

//Название клонированного объекта.
Shelby_Backend.ListHelper.clonedObjName = null;

//Постоянный фильтр выборки
Shelby_Backend.ListHelper.persistentFilter = null;

//Id поля, в которое нужно записать полученное значение
Shelby_Backend.ListHelper.returnFieldId = null;

//Многомерный массив, содержащий названия столбцов
Shelby_Backend.ListHelper.columns = new Array();

//Объект, содержащий текущие json-данные
Shelby_Backend.ListHelper.jsonDataObj = null;


Shelby_Backend.ListHelper.msgNoValue   = '(No value)';
Shelby_Backend.ListHelper.msgListEmpty = '(List empty)';



//Должен быть вызван всякий раз при создании объекта
Shelby_Backend.ListHelper.cloneObject = function(name) {
  this.clonedObjName = name;

  return Shelby_Backend.clone(this);
}

//Функция обратного пользовательского вызова.
Shelby_Backend.ListHelper.callbackUser = function(entry) {
  //alert(entry.name);
}


Shelby_Backend.ListHelper.renderName = function(entry) {
  return  entry.name;
}


Shelby_Backend.ListHelper.inArray = function(s, a) {
  for (i = 0; i < a.length; i++) {
    if(a[i] == s) {
      return true;
    }
  }
  return false;
}


Shelby_Backend.ListHelper.getAndWriteList = function(page, sortby, search) {
  var url, url_search = '';

  $("#popUpLoadingAnimation_" + this.clonedObjName).css("display", "inline");

  url =this.feedUrl + "page/"+page+"/";

  if (sortby) {
    url += "sort/"+sortby+"/";
  }

  if (search && search.length>0) {
    url_search = "name~" + encodeURIComponent(search);
  }

  if (this.persistentFilter != null) {
    url_search += (url_search=="" ? "":";") + this.persistentFilter;
  }

  if (url_search != "") {
    url += "search/" + url_search + "/";
  }

  // Задержка выполнения пока предыдущая выборка не завершится
  //for (var xxx = 0; (xxx < 9999) || !Shelby_Backend._objListRefer; xxx++) ;

  //Сохраняем ссылку на объект для дальнейшего использования
  var t = this;

  $.getJSON(url, function(json) {
      var html_data = '';
      var pagesTotal;

      t.jsonDataObj = json.list.data;

      html_data += '<div style="overflow:auto; height:350px; width:100%;">';
      html_data += '<table border="1" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;"><tr>';

      for (i=0; i < t.columns.length; i++) {
        html_data += '<th align="center">' + t.columns[i][1] + '</th>';
      }

      html_data += '</tr>';

      pagesTotal = json.list.pages;

      $.each(json.list.data, function(i) {

        html_data += '<tr style="cursor:pointer" onClick="' + t.clonedObjName + '.selectEntry(' + i + ');">';

        for (ii=0; ii < t.columns.length; ii++) {
          if (t.columns[ii][0] == 'active') {
            html_data += '<td align="center"><input type="checkbox" disabled ' + (json.list.data[i][t.columns[ii][0]]==1 ? "checked":"") + '></td>';
          } else {
            html_data += '<td>' + json.list.data[i][t.columns[ii][0]] + '</td>';
          }
        }

        html_data += '</tr>';
      });

      html_data += '</table></div><div align="center">' + t.createPaging(page, pagesTotal, sortby, search) + '</div>';

      $('#popup_data_' + t.clonedObjName).html(html_data);
      $("#popUpLoadingAnimation_" + t.clonedObjName).css("display", "none");
    });
}



Shelby_Backend.ListHelper.getAndWriteMultiselectList = function(sortby) {
  var url = '';

  $("#popUpLoadingAnimation_" + this.clonedObjName).css("display", "inline");

  url = this.feedUrl + "results/999/";

  if (sortby) {
    url += "sort/"+sortby+"/";
  }

  // Задержка выполнения пока предыдущая выборка не завершится
  // for (var xxx = 0; (xxx < 9999) || !Shelby_Backend._objListRefer; xxx++) ;

  //Сохраняем ссылку на объект для дальнейшего использования
  var t = this;

  $.getJSON(url, function(json) {
      var html_data = '';

      var choices = $('#' + t.returnFieldId).val().split(',');

      t.jsonDataObj = json.list.data;

      html_data += '<div style="overflow:auto; height:350px; width:100%;">';
      html_data += '<table border="1" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;"><tr><td>Select</td>';

      for (i=0; i < t.columns.length; i++) {
        html_data += '<th align="center">' + t.columns[i][1] + '</th>';
      }

      html_data += '</tr>';

      $.each(json.list.data, function(i) {

        html_data += '<tr>';

        html_data += '<td align="center"><input type="checkbox"'+(t.inArray(json.list.data[i]['id'], choices) ? " checked":"")+' value="'+i+'" name="multiselect_checkbox" /></td>';

        for (ii=0; ii < t.columns.length; ii++) {
          if (t.columns[ii][0] == 'active') {
            html_data += '<td><input type="checkbox" disabled ' + (json.list.data[i][t.columns[ii][0]]==1 ? "checked":"") + '></td>';
          } else {
            html_data += '<td>' + json.list.data[i][t.columns[ii][0]] + '</td>';
          }
        }

        html_data += '</tr>';
      });

      html_data += '</table></div><div align="center"><button type="button" onclick="'+t.clonedObjName+'.selectEntries()">Select</button></div>';

      $('#popup_data_' + t.clonedObjName).html(html_data);
      $("#popUpLoadingAnimation_" + t.clonedObjName).css("display", "none");
    });
}



Shelby_Backend.ListHelper.getValue = function(id) {
  //Сохраняем ссылку на объект для дальнейшего использования
  var t = this;

  if (id) {
    var url, url_search = '';

    url =t.valueUrl + "id/"+id+"/";

    if (t.persistentFilter != null) {
      url_search += (url_search=="" ? "":";") + t.persistentFilter;
    }

    if (url_search != "") {
      url += "search/" + url_search + "/";
    }

    // alert(url);

    $.getJSON(url, function(json) {
      var objId = json.entry.id;
      if (!objId) json.entry.name = t.msgNoValue;
      var objName = t.renderName(json.entry);
      $('#' + t.returnFieldId + "_name").html(objName);
    });
  } else {
    var em = { 'name' : t.msgNoValue };
    var objName = t.renderName(em);
    $('#' + t.returnFieldId + "_name").html(objName);
  }
}



Shelby_Backend.ListHelper.getValues = function(ids) {
  var url;

  //Сохраняем ссылку на объект для дальнейшего использования
  var t = this;

  if (ids) {
    // var retNames = new Array();
    var arr = $.trim(ids).split(',');

    for (var i = 0; i < arr.length; i++) {
      if (arr[i]) {
        url = t.valueUrl + "id/" + arr[i] + "/";

        $.getJSON(url, function(json) {
          var objId = json.entry.id;

          if (objId) {
            var objName = t.renderName(json.entry);
            // retNames.push(objName);
            var h = $('#' + t.returnFieldId + "_name").html();
            $('#' + t.returnFieldId + "_name").html(h + (h.length ? "\n" : '') + objName);
          }
        });
      }
    }
  } else {
    var em = { 'name' : t.msgListEmpty };
    var objName = t.renderName(em);
    $('#' + t.returnFieldId + "_name").html(objName);
  }
}


Shelby_Backend.ListHelper.selectEntry = function(i) {
  $('#popup_window_' + this.clonedObjName).jqmHide();

  $('#' + this.returnFieldId).attr("value", this.jsonDataObj[i].id);
  $('#' + this.returnFieldId + "_name").html(this.renderName(this.jsonDataObj[i]));

  this.callbackUser(this.jsonDataObj[i]);
}



Shelby_Backend.ListHelper.selectEntries = function() {
  var t = this;

  var ch    = new Array();
  var ids   = new Array();
  var names = new Array();

  $('#popup_window_' + t.clonedObjName).jqmHide();

  $("#popup_data_" + t.clonedObjName + " input[name=multiselect_checkbox]:checked").each(function (n) {
    ch.push($(this).val());
  });

  for (var i = 0; i < ch.length; i++) {
    var d = t.jsonDataObj[ch[i]];
    ids.push(d.id); names.push(t.renderName(d));
  }

  $('#' + t.returnFieldId).val(ids.join(','));
  $('#' + t.returnFieldId + "_name").html(names.join("\n"));

  // t.callbackUser(t.jsonDataObj[i]);
}




Shelby_Backend.ListHelper.createPaging = function(page, pagesTotal, sort, search) {
  var i;
  if (!sort) {
    sort = '';
  }
  if (!search) {
    search = '';
  }

  var data = 'Page: <select onChange="' + this.clonedObjName + '.getAndWriteList(this.value, \'' + sort + '\', \''+ search + '\');">';
  pagesTotal = parseInt(pagesTotal);
  page = parseInt(page);

  for (i=1; i<=pagesTotal; i++) {
    data += '<option value="' + i + '"';
    if (i==page) {
      data += " selected";
    }
    data += ">" + i + "</option>";
  }
  data += "</select>";

  return data;
}


Shelby_Backend.ListHelper.writeForm = function() {
  document.write('<DIV id="popup_window_' + this.clonedObjName + '" class="jqmWindow"><DIV style="float:left;">' +
  '<FORM method="post" onsubmit="' + this.clonedObjName + '.getAndWriteList(1, null, $(\'#popup_search_text_' + this.clonedObjName + '\').attr(\'value\')); return false;">Search by name: <INPUT type="text" size="15" id="popup_search_text_' + this.clonedObjName + '"> <INPUT type="submit" value="&gt;"></FORM></DIV>' +
  '<div style="float:right;"><IMG src="/images/admin/loadingAnimation.gif" style="display:none;" id="popUpLoadingAnimation_' + this.clonedObjName + '"> &nbsp; &nbsp; <a href="#" class="jqmClose"><b>X</b></a></div>' +
  '<DIV style="clear:both; border-bottom:1px solid #000000; padding-bottom:3px;"></DIV>' +
  '<DIV id="popup_data_' + this.clonedObjName + '"></DIV></DIV>');
}

Shelby_Backend.ListHelper.writeMultipleForm = function() {
  document.write('<DIV id="popup_window_' + this.clonedObjName + '" class="jqmWindow"><DIV style="float:left;">' +
  '</DIV>' +
  '<div style="float:right;"><IMG src="/images/admin/loadingAnimation.gif" style="display:none;" id="popUpLoadingAnimation_' + this.clonedObjName + '"> &nbsp; &nbsp; <a href="#" class="jqmClose"><b>X</b></a></div>' +
  '<DIV style="clear:both; border-bottom:1px solid #000000; padding-bottom:3px;"></DIV>' +
  '<DIV id="popup_data_' + this.clonedObjName + '"></DIV></DIV>');
}

Shelby_Backend.ListHelper.showPopUp = function() {
  $('#popup_window_' + this.clonedObjName).jqm().jqmShow();
  if (this.loaded==0) {
    this.getAndWriteList(1);
    this.loaded = 1;
  }
}

Shelby_Backend.ListHelper.showPopUpMultiple = function() {
  $('#popup_window_' + this.clonedObjName).jqm().jqmShow();
  if (this.loaded==0) {
    this.getAndWriteMultiselectList(1);
    this.loaded = 1;
  }
}

