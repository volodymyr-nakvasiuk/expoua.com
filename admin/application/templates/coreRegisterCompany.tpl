<HTML>
 <HEAD>
  <TITLE>Exhibition Global Marketing System</TITLE>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8">
  <LINK rel="stylesheet" href="{$document_root}css/admin/admin_style.css" type="text/css">
  <SCRIPT type="text/javascript" src="{$document_root}js/adminGeneral.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/adminAutocomplete.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/jquery.js"></SCRIPT>

{literal}
<STYLE type="text/css">
HTML, BODY {
	height: 98%;
}
</STYLE>
{/literal}
 </HEAD>
 <BODY id="cms-wrapper">

{literal}
<SCRIPT type="text/javascript">

var allowSubmit = false;

 function checkUser(login) {
 	var url = '{/literal}{getUrl controller="sab_jsonfeeds" action="checkcompanyuser"}{literal}id/' + login + "/";

 	$.getJSON(url, function(json) {
 		if (json.entry == 1) {
 			$("#check_result").html('&#215;');
 			$("#check_result").css("background-color", '#FF0000');
 			allowSubmit = false;
 			$("#submit_button").attr("disabled", "disabled");
 		} else {
 			$("#check_result").html('&#8730;');
 			$("#check_result").css("background-color", '#00FF00');
 			allowSubmit = true;
 			$("#submit_button").removeAttr("disabled");
 		}
 	});
 }

 function checkFrom() {
 	if (allowSubmit == false) {
 		alert('Пользователь с таким логином уже существует');
 		return false;
 	}

 	return true;
 }
{/literal}

{*
objAC_company = Shelby_Backend.Autocomplete.cloneObject('objAC_company');
objAC_company.feedUrl = '{getUrl controller="sab_jsonfeeds" action="companies"}';
objAC_company.baseSearchUrl = '{getUrl add="1" del="search,page"}';

objAC_company.pickElement = function(i) {ldelim}
	var jsonElId = this.SelTagToJsonDOId[i];

	$('#companies_id').attr("value", this.jsonDataObj[jsonElId].id);
	$('#objAC_company_input_id').attr("value", this.jsonDataObj[jsonElId].name);

	this.hidePopUp();
{rdelim}
*}
</SCRIPT>

<TABLE width="100%" align="center" cellspacing="0" border="0" style="position:static;">
 <TR>
  <TD align="right" valign="top"><img src="{$document_root}images/admin/logo.gif"></TD>
 </TR>
</TABLE>
<TABLE height="100%" width="100%" border="0" cellpadding="8">
<TR><TD>

{if isset($last_action_result) && $last_action_result==1}
<CENTER>
 <p>Регистрация прошла успешно!</p>
 
<p>Спасибо за регистрацию в системе. На Ваш e-mail адрес было отправлено сообщение с Вашим логином и паролем.
Чтобы начать работу в системе, перейдите, пожалуйста, на <a href="http://admin.expopromoter.com/company/">страницу входа</a> и войдите в систему используя Ваш логин и пароль.</p>

<p>Вы сможете добавлять и редактировать в базе информацию о Вашей компании
(внимание! Ваша компания появится на ExpoUA.com, ExpoTOP.ru, ExpoTOP.com, только после того как  Вы <a href="{getUrl controller="sab_company_self" action="edit}">отредактируете</a> информацию о ней), 
<a href="{getUrl controller="sab_company_services" action="list"}">товарах/услугах</a>,
<a href="{getUrl controller="sab_company_employers" action="list"}">сотрудниках</a>,
<a href="{getUrl controller="sab_company_news" action="list"}">публиковать новости Вашей компании</a>.</p>
В адрес Ваших сотрудников Вы сможете получать запросы/заказы от посетителей наших веб-порталов. 

<p><a href="{getUrl controller="sab_company_eventspart" action="edit"}">Функция анонсирования участия Вашей компании в выставках</a>
поможет привлечь на Ваш стенд посетителей, так как Ваша компания будет размещена на карточке соответствующей выставки в блоке
"Компании-участники приглашают". (для информации, за 5 дней до начала выставки и во время ее проведения, информационная
карточка данной выставки на наших веб-порталах просматривается до 1000 раз в сутки).</p>
 
<p><a href="{getUrl controller="sab_company_auth"}">Перейти на страницу входа в систему управления</a></p>
</CENTER>
{elseif isset($last_action_result) && $last_action_result==0}
<CENTER><a href="{getUrl controller="sab_company_register"}">При добавлении компании возникли ошибки, пожалуйста, попробуйте снова.</a></CENTER>
{else}

 <P>&nbsp;</P><P><h4 align="center">Регистрация новой компании</h4></P>

 <FORM method="post" action="{getUrl add="1" action="proceed"}" onsubmit="return checkFrom();" onkeypress="return event.keyCode!=13">

 {* <INPUT type="hidden" name="companies_id" id="companies_id" value="0" /> *}

<TABLE align="center" cellspacing="0" border="0" width="400">
 <TR>
  <TD>Ваш язык:</TD>
  <TD>
   <SELECT name="local_languages_id">
   {foreach from=$list_languages item="el"}<OPTION value="{$el.id}">{$el.name}</OPTION>{/foreach}
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Логин: </TD><TD><INPUT type="text" name="login" style="width:200px" onchange="checkUser(this.value);"> <SPAN id="check_result"></SPAN></TD>
 </TR>
 <TR>
  <TD>Пароль: </TD><TD><INPUT type="text" name="passwd" style="width:200px"></TD>
 </TR>
 <TR>
  <TD>Название компании: </TD><TD>
   <INPUT type="text" name="name" style="width:200px" /><BR />
   {* <INPUT type="text" name="name" style="width:200px" onKeyUp="objAC_company.getData(this.value, event);" id="objAC_company_input_id"><BR />
   <div id="objAC_company_popup_id" style="position:absolute; border: 1px solid #6f5d15; width:250px; background-color:white; visibility:hidden; float:left;"></div> *}
  </TD>
 </TR>
 <TR>
  <TD>Контактное лицо: </TD><TD><INPUT type="text" name="contact_person" style="width:200px"></TD>
 </TR>
 <TR>
  <TD>Электронный адрес: </TD><TD><INPUT type="text" name="email" style="width:200px"></TD>
 </TR>
 <TR>
  <TD>Вэб-сайт: </TD><TD><INPUT type="text" name="url" style="width:200px"></TD>
 </TR>
 <TR>
  <TD>Телефон: </TD><TD><INPUT type="text" name="phone" style="width:200px"></TD>
 </TR>
 <TR>
  <TD colspan="2" align="center">
   <INPUT type="reset" value="Очистить" /> &nbsp; &nbsp;
   <INPUT type="submit" id="submit_button" value="Зарегистрироваться" disabled="disabled" />
  </TD>
 </TR>
</TABLE>

</FORM>
{/if}
</TD></TR>
<TR><TD height="100%" valign="bottom" align="right">
 <!-- <a href="http://framework.zend.com/" target="zfwindow"><img src="http://framework.zend.com/images/PoweredBy_ZF_4LightBG.png" border="0"></a> -->
</TD></TR>
</TABLE>

<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-978984-1";
urchinTracker();
</script>

</BODY>
</HTML>