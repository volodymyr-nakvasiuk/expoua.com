{include file="common/contentVisualEdit.tpl" textarea="description"}

<SCRIPT type="text/javascript">
objBrandsEventsList = Shelby_Backend.ListHelper.cloneObject('objBrandsEventsList');

objBrandsEventsList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название бренда'), new Array('date_from', 'С даты'));
objBrandsEventsList.returnFieldId = 'events_id';
objBrandsEventsList.feedUrl = '{getUrl controller="admin_ep_brandsevents" action="list" feed="json"}';
objBrandsEventsList.writeForm();
</SCRIPT>

<h4>Редактирование участника</h4>

Ссылка для редактирования: <a href="http://admin.expopromoter.com{getUrl controller="sab_participant_self" action="edit" id=$entry.id key=$entry.unique_id}" target="_blank">http://admin.expopromoter.com{getUrl controller="sab_participant_self" action="edit" id=$entry.id key=$entry.unique_id}</a>

<br/><br/>

<FORM method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data">

<TABLE border="1" style="border-collapse:collapse" width="100%">
 <tr>
  <TD>Заголовок:</TD>
  <TD><INPUT type="text" size="30" name="name" value="{$entry.name}"></TD>
 </tr>
 <TR>
  <TD width="70">Выставка: </TD>
  <TD>Id: <INPUT type="text" name="events_id" id="events_id" value="{$entry.events_id}" size="5"> <INPUT type="button" onclick="objBrandsEventsList.showPopUp();" value="Выбрать"> <span id="events_id_name">{$entry.brand_name} (с {$entry.event_date_from} по {$entry.event_date_to}) </span></TD>
 </TR>
 <tr>
  <TD>Логотип:</TD>
  <TD>
   <INPUT type="file" name="logo">
   {if $entry.logo==1} <a href="/data/images/event_participants/logo/{$entry.id}.jpg" target="_blank">Просмотреть</a>{/if}
  </TD>
 </tr>
 <TR>
  <TD>Категория:</TD>
  <TD>
   <SELECT name="brands_categories_id">
   {foreach from=$list_categories.data item="el"}
    <OPTION value="{$el.id}"{if $el.id==$entry.brands_categories_id} selected{/if}>{$el.name}</OPTION>
   {/foreach}
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Город:</TD>
  <TD>
   <SELECT name="cities_id">
   {foreach from=$list_cities.data item="el"}
    <OPTION value="{$el.id}"{if $el.id==$entry.cities_id} selected{/if}>{$el.name}</OPTION>
   {/foreach}
   </SELECT>
  </TD>
 </TR>
 <tr>
  <TD>Адрес:</TD>
  <TD><INPUT type="text" size="50" name="address" value="{$entry.address}"></TD>
 </tr>
 <tr>
  <TD>Индекс:</TD>
  <TD><INPUT type="text" size="30" name="postcode" value="{$entry.postcode}"></TD>
 </tr>
 <tr>
  <TD>Телефон:</TD>
  <TD><INPUT type="text" size="30" name="phone" value="{$entry.phone}"></TD>
 </tr>
 <tr>
  <TD>Факс:</TD>
  <TD><INPUT type="text" size="30" name="fax" value="{$entry.fax}"></TD>
 </tr>
 <tr>
  <TD>Email:</TD>
  <TD><INPUT type="text" size="30" name="email" value="{$entry.email}"></TD>
 </tr>
 <tr>
  <TD>Email запросов:</TD>
  <TD><INPUT type="text" size="30" name="email2" value="{$entry.email2}"></TD>
 </tr>
 <tr>
  <TD>Адрес сайта:</TD>
  <TD><INPUT type="text" size="50" name="web_address" value="{$entry.web_address}"></TD>
 </tr>

 <TR>
  <TD colspan="2">
   Описание:<br />
   <TEXTAREA style="width:95%; height:450px;" name="description">{$entry.description|escape:"html"}</TEXTAREA>
  </TD>
 </TR>
 <TR><TD colspan="2" align="center"><INPUT type="submit" value="Изменить"></TD></TR>
</TABLE>

<P><a href="{getUrl add="1" action="list"}">Вернуться к списку</a></P>

</FORM>