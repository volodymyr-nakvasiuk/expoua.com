Shelby_Backend.FormValidator = new Object();

//Заголовок сообщения об ошибке
Shelby_Backend.FormValidator.headerMessage = '';

//Название клонированного объекта.
Shelby_Backend.FormValidator.clonedObjName = null;

//Массив полей, требующих валидации
Shelby_Backend.FormValidator.fields = new Array();

//Количество параметров валидации
Shelby_Backend.FormValidator.fieldsNum = 0;

//Должен быть вызван всякий раз при создании объекта в случае если на одной странице используется несколько валидаторов форм
Shelby_Backend.FormValidator.cloneObject = function(name) {
	this.clonedObjName = name;

	return Shelby_Backend.clone(this);
}

//Функция добавления нового поля для валидации. Принимает параметры:
//id: идентификатор поля
//type: тип поля (num, text, email, tinyMCE)
//length: минимальная длина данных в поле
//message: сообщение об ошибке
Shelby_Backend.FormValidator.addField = function(id, type, length, message) {
	this.fields[this.fieldsNum] = new Array(id, type, length, message);

	this.fieldsNum++;
}

//Функция пользовательских фалидаторов. Автоматически вызывается в this.validate, должна возвращать true или сообщение об ошибке в зависимости от результата
Shelby_Backend.FormValidator.userValidation = function() {
	return true;
}

//Функция валидации формы, вызывается из формы, возвращает true или false
Shelby_Backend.FormValidator.validate = function() {
	var i;
	var error_msg = '';
	var field;

	for (i=0; i<this.fieldsNum; i++) {
		field = this.fields[i];
		switch (field[1]) {
			case "text":
				if ($("#" + field[0]).val().length < field[2]) {
					error_msg += field[3];
				}
				break;
			case "checkbox":
				if (!$("#" + field[0]).attr('checked')) {
					error_msg += field[3];
				}
				break;
			case "num":
				if (Number($("#" + field[0]).val()) == NaN) {
					error_msg += field[3];
				} else if (Number(field[2]) != NaN && Number($("#" + field[0]).val()) < field[2]) {
					//Вторым параметром можно задать нижнюю границу значения поля
					error_msg += field[3];
				}
				break;
			case "email":
				var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
				if (filter.test($("#" + field[0]).val()) == false) {
					error_msg += field[3];
				}
				break;
			case "tinyMCE":
				var content = tinyMCE.getContent(field[0]);
				if (content.length < field[2]) {
					error_msg += field[3];
				}
				break;
		}
	}

	var userValRes = this.userValidation();

	if (userValRes != true) {
		error_msg += userValRes;
	}

	if (error_msg!='') {
		alert(this.headerMessage + error_msg);
		return false;
	}

	return true;
}