<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>
objAdvertisersList = Shelby_Backend.ListHelper.cloneObject('objAdvertisersList');

objAdvertisersList.columns = new Array(new Array('id', 'Id'), new Array('name', 'Название'));
objAdvertisersList.returnFieldId = 'advertisers_id';
objAdvertisersList.feedUrl = '{getUrl controller="admin_ep_banners_advertisers" action="list" feed="json"}';
objAdvertisersList.writeForm();
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="update"}">

<h4>Редактируем рекламную кампанию</h4>

<TABLE border="0" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Рекламодатель:</TD>
  <TD><INPUT type="text" size="5" name="advertisers_id" id="advertisers_id" value="{$entry.advertisers_id}"/> <INPUT type="button" onclick="objAdvertisersList.showPopUp();" value="Выбрать"/> <SPAN id="advertisers_id_name">{$entry.advertiser_name}</SPAN></TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="80" name="name" value="{$entry.name|escape:"html"}"></TD>
 </TR>
 <TR>
  <TD colspan="2">Описание:<BR />
   <TEXTAREA name="description" style="width:95%; height:80px;">{$entry.description|escape:"html"}</TEXTAREA>
  </TD>
 </TR>

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Изменить"/></TD></TR>
</TABLE>

</FORM>

<P><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></P>