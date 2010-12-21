<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT type="text/javascript">
objSitesList = Shelby_Backend.ListHelper.cloneObject('objSitesList');
objSitesList.columns = new Array(new Array('id', 'Id'), new Array('name', 'Название'));
objSitesList.returnFieldId = 'sites_id';
objSitesList.feedUrl = '{getUrl controller="admin_ep_sites" action="list" feed="json"}';
objSitesList.writeForm();
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="insert"}">

<h4>Добавляем новый сайт - рекламную прощадку</h4>

<TABLE border="0" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="80" name="name"></TD>
 </TR>
 <TR>
  <TD>URL:</TD>
  <TD><INPUT type="text" size="40" name="url"></TD>
 </TR>
 <TR>
  <TD>Зарегистрированный сайт:</TD>
  <TD><INPUT type="text" size="5" name="sites_id" id="sites_id" /> <INPUT type="button" onclick="objSitesList.showPopUp();" value="Выбрать"/> <SPAN id="sites_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD colspan="2">Описание:<BR />
   <TEXTAREA name="description" style="width:95%; height:80px;"></TEXTAREA>
  </TD>
 </TR>

 <TR style="border-bottom:1px solid #CCCCCC;">
  <TD>Доступные площадки:</TD>
  <TD>
   {foreach from=$list_places item="el"}
    <LABEL><INPUT type="checkbox" name="places_id[{$el.id}]" value="{$el.id}"/> {$el.name}</LABEL><br />
   {/foreach}
  </TD>
 </TR>

 <TR>
  <TD>Доступные языки:</TD>
  <TD>
   {foreach from=$list_languages item="el"}
    <LABEL><INPUT type="checkbox" name="languages_id[{$el.id}]" value="{$el.id}"/> {$el.name}</LABEL><br />
   {/foreach}
  </TD>
 </TR>
 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Добавить"></TD></TR>
</TABLE>

</FORM>

<P><a href="{getUrl add="1" action="list"}">Вернуться к списку</a></P>