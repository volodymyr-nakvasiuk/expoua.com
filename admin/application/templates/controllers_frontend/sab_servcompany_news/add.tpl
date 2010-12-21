{*debug*}
{strip}{assign var="ve_textareas" value=""}
{foreach from=$list_languages item="lang" name="lang_fe"}
{assign var="ve_textareas" value="`$ve_textareas`content_`$lang.code`"}
{if !$smarty.foreach.lang_fe.last}{assign var="ve_textareas" value="`$ve_textareas`,"}{/if}
{/foreach}{/strip}
{include file="common/orgContentVisualEdit.tpl" textarea=$ve_textareas}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>

objBrandsList = Shelby_Backend.ListHelper.cloneObject('objBrandsList');

objBrandsList.columns = new Array(new Array('name', 'Название'));
objBrandsList.returnFieldId = 'brands_id';
objBrandsList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="brands" results="999" sort="name:ASC"}';
objBrandsList.persistentFilter = 'organizers_id={$session_user_orgid}';
objBrandsList.writeForm();

{literal}

objBrandsList.callbackUser = function(entry) {
	$("#brands_categories_id").val(entry.brands_categories_id);
	$("#brand_name").val(entry.name);
}

function preview() {
	var insertAction = '{/literal}{getUrl add="1" action="insert"}{literal}';
	var previewAction = '{/literal}{getUrl add="1" action="preview"}{literal}';

	$('#mainform').attr('action', previewAction);
	$('#mainform').attr('target', 'news_preview');

	$('#mainform').submit();

	$('#mainform').attr('action', insertAction);
	$('#mainform').attr('target', '_self');
}

$(document).ready(function() {
	$('#date_public').datepicker();
});
{/literal}
</SCRIPT>

<h4>Добавляем в ленту новостей</h4>

<FORM method="post" action="{getUrl add="1" action="insert"}" id="mainform">

<INPUT type="hidden" name="brand_name" id="brand_name" value="" />
<INPUT type="hidden" name="common[brands_categories_id]" id="brands_categories_id" />

<TABLE border="0" width="100%" style="border-collapse:collapse;" cellpadding="5">
 <!--{*<TR>
  <TD class="wb">Выбрать выставку:</TD>
  <TD><INPUT type="hidden" name="common[brands_id]" id="brands_id" value=""> <INPUT type="button" onclick="objBrandsList.showPopUp();" value="Выбрать"> <SPAN id="brands_id_name"></SPAN></TD>
 </TR>*}-->
 <TR>
  <TD class="wb">Страна:</TD>
  <TD>
   <SELECT name="countries_id">
    <OPTION value="">(Не выбрана)</OPTION>
    {foreach from=$list_countries.data item="el"}
     <OPTION value="{$el.id}">{$el.name}</OPTION>
    {/foreach}
   </SELECT>
 </TR>
 {foreach from=$list_languages item="lang"}
 <TR>
  <TD class="wb">Заголовок ({$lang.name}):</TD>
  <TD><INPUT type="text" size="50" name="{$lang.code}[name]" value=""></TD>
 </TR>
 {/foreach}
 <TR>
  <TD class="wb">Дата публикации:</TD>
  <TD><INPUT type="text" size="12" name="common[date_public]" id="date_public" value="{$smarty.now|date_format:"%Y-%m-%d"}"></TD>
 </TR>

 {foreach from=$list_languages item="lang"}
 <TR>
  <TD valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="wb">Преамбула ({$lang.name}): </td>
		</tr>
	</table>
  	<BR /></TD>
 <TD><textarea name="{$lang.code}[preambula]" id="preambula_{$lang.code}" style="width:95%; height:100px;"></textarea></TD>
 </TR>
 {/foreach}
 {foreach from=$list_languages item="lang"}
 <TR>
  <TD valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="wb">Полный текст ({$lang.name}): </td>
		</tr>
	</table>
  	<BR /></TD>
 <TD><textarea name="{$lang.code}[content]" id="content_{$lang.code}" style="width:95%; height:500px;"></textarea></TD>
 </TR>
 {/foreach}
</TABLE>

<TABLE width="60%" align="center">
 <TR>
  <TD align="center"><INPUT type="button" value="Очистить" onclick="document.location.href=document.location.href;" /></TD>
  <TD align="center"><INPUT type="button" value="Предварительный просмотр" onclick="preview();" /></TD>
  <TD align="center"><INPUT type="submit" value="Отправить на проверку" /></TD>
 </TR>
</TABLE>

</FORM>