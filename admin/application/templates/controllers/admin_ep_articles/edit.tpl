{include file="common/contentVisualEdit.tpl" textarea="content" imagesDefaultParent="articles:`$entry.id`"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/jqExtensions/ui.datepicker.js"></SCRIPT>

<SCRIPT type="text/javascript" language="javascript">
{literal}
$(document).ready(function() {

	$('#date_public').datepicker();

});
{/literal}
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="update"}">

<h4>Редактируем статью</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="50" name="name" value="{$entry.name}"></TD>
 </TR>
 <TR>
  <TD>Дата публикации:</TD>
  <TD><INPUT type="text" size="12" name="date_public" id="date_public" value="{$entry.date_public}"></TD>
 </TR>
 <TR>
  <TD colspan="2">Преамбула:<BR />
   <TEXTAREA name="preambula" id="preambula" style="width:95%; height:100px;">{$entry.preambula|escape:"html"}</TEXTAREA>
  </TD>
 </TR>
 <TR>
  <TD colspan="2">Полный текст:<BR />
   <TEXTAREA name="content" id="content" style="width:95%; height:500px;">{$entry.content|escape:"html"}</TEXTAREA>
  </TD>
 </TR>
 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Обновить"></TD></TR>
</TABLE>

</FORM>