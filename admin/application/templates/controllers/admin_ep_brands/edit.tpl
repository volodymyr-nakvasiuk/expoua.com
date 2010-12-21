<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>

objOrganizersList = Shelby_Backend.ListHelper.cloneObject('objOrganizersList');

objOrganizersList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objOrganizersList.returnFieldId = 'organizers_id';
objOrganizersList.feedUrl = '{getUrl controller="admin_ep_organizers" action="list" feed="json"}';
objOrganizersList.writeForm();

</SCRIPT>

<FORM method="post" action="{if isset($formActionUrl)}{$formActionUrl}{else}{getUrl add="1" action="update"}{/if}">

<h4>{if isset($formTitle)}{$formTitle}{else}Редактируем бренд{/if}</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Организатор:</TD>
  <TD><INPUT type="text" size="5" name="organizers_id" id="organizers_id" value="{$entry.organizers_id}"> <INPUT type="button" onclick="objOrganizersList.showPopUp();" value="Выбрать"> <SPAN id="organizers_id_name">{$entry.organizer_name}</SPAN></TD>
 </TR>
 <TR>
  <TD>Основная категория:</TD>
  <TD>
   <SELECT name="brands_categories_id">
    {foreach from=$list_categories item="cat"}
     <OPTION value="{$cat.id}"{if $cat.id==$entry.brands_categories_id} selected{/if}>{$cat.name}</OPTION>
    {/foreach}
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Дополнительные категории:</TD>
  <TD>
  <DIV style="overflow:auto; height:350px;">
  {strip}
   {foreach from=$list_categories item="cat"}
    <INPUT type="checkbox" name="cat[{$cat.id}]" id="cat{$cat.id}" value="{$cat.id}"{if isset($entry.categories[$cat.id])} checked{/if} /> <b>{$cat.name}</b><BR />
    {foreach from=$cat.subcats item="subcat"}
     &nbsp; &nbsp; <INPUT type="checkbox" name="subcat[{$subcat.id}]" value="{$subcat.id}"{if isset($entry.sub_categories[$subcat.id])} checked{/if} onclick="$('#cat{$cat.id}').attr('checked', 'checked');" /> {$subcat.name}<BR />
    {/foreach}
   {/foreach}
  {/strip}
  </DIV>
  </TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="80" name="name" value="{$entry.name}"></TD>
 </TR>
 <TR>
  <TD colspan="2">Расширенное название:<BR />
   <TEXTAREA name="name_extended" id="name_extended" style="width:95%; height:50px;">{$entry.name_extended|escape:"html"}</TEXTAREA>
  </TD>
 </TR>

 <TR>
  <TD>Мертвый:</TD>
  <TD>
   <INPUT type="checkbox"{if $entry.dead==1} checked="checked"{/if} onclick="Shelby_Backend.objects_multi_checkbox('dead_id', this.checked);" />
   <INPUT type="hidden" name="dead" id="dead_id" value="{$entry.dead}" />
  </TD>
 </TR>
 <TR>
  <TD>Email для запросов:</TD>
  <TD><INPUT type="text" size="20" name="email_requests" value="{$entry.email_requests}"></TD>
 </TR>

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="{if isset($formSubmitName)}{$formSubmitName}{else}Обновить{/if}"></TD></TR>
</TABLE>

</FORM>