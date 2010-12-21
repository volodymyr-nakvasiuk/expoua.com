<SCRIPT type="text/javascript" src="{$document_root}js/adminAutocomplete.js"></SCRIPT>
<SCRIPT type="text/javascript">

{foreach from=$list_user_languages item="lang"}
objAC_categories_{$lang.code} = Shelby_Backend.Autocomplete.cloneObject('objAC_categories_{$lang.code}');
objAC_categories_{$lang.code}.feedUrl = '{getUrl language=$lang.code controller="sab_jsonfeeds" action="compservcats"}';

objAC_categories_{$lang.code}.pickElement = function(i) {ldelim}
	var jsonElId = this.SelTagToJsonDOId[i];

	$('#objAC_categories_input_{$lang.code}').attr("value", this.jsonDataObj[jsonElId].name);

	this.hidePopUp();
{rdelim}
{/foreach}
{literal}
function saveAsTemplate() {
	$('#active').val('0');
	$('#thisform').submit();
}
{/literal}
</SCRIPT>

<h4>Добавляем новую категорию товаров или услуг</h4>

<table width="140" border="0" cellspacing="3" cellpadding="3">
  <tr class="yellow_hilight">
    <td><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></td>
  </tr>
  <tr class="yellow_hilight">
    <td>&nbsp;</td>
  </tr>
</table>

<FORM method="post" action="{getUrl add="1" action="insert"}" id="thisform" onkeypress="return event.keyCode!=13;">

<TABLE border="0" width="100%" style="border-collapse:collapse;">
 {foreach from=$list_user_languages item="lang"}
 <TR>
  <TD class="wb">Название ({$lang.name}):</TD>
  <TD>
   <INPUT type="text" size="70" name="{$lang.code}[name]" id="objAC_categories_input_{$lang.code}" onKeyUp="objAC_categories_{$lang.code}.getData(this.value, event);">
   <div id="objAC_categories_{$lang.code}_popup_id" style="position:absolute; border: 1px solid #6f5d15; width:250px; background-color:white; visibility:hidden; float:left;"></div>
  </TD>
 </TR>
 {/foreach}

 <TR><TD align="center" colspan="2"><BR />
  <INPUT type="submit" value="Добавить" style="margin-right:100px;">
  <INPUT type="button" value="Сохранить как черновик" onclick="$('#active').val('0'); $('#thisform').submit();">
 </TD></TR>

</TABLE>

<INPUT type="hidden" name="common[active]" id="active" value="1">

</FORM>
