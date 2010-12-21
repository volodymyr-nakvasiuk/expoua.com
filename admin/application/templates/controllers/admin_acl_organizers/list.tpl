<h4>Зарегистрированные организаторы</h4>

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="active" descr="A"}
 {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="super" descr="S"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="login" descr="Имя пользователя"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="organizers_id" controller="admin_ep_organizers" descr="Организатор"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="countries_id" controller="admin_ep_locations_countries" descr="Страна"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="cities_id" controller="admin_ep_locations_cities" descr="Город"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="create_time" descr="Время создания"}
 <TH align="center" colspan="2">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
   <TD align="center">
  <FORM method="post" action="{getUrl add="1" action="update" id=$el.id}" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" {if $el.active==1} checked{/if} onclick="this.form.submit();">
  <INPUT type="hidden" name="active" value="{if $el.active==1}0{else}1{/if}">
  </FORM>
 </TD>
 <TD align="center">
  {if $el.super==1}
   <INPUT type="checkbox" disabled="disabled" checked="checked" /></TD>
  {else}
   <FORM method="post" action="{getUrl add="1" action="update" id=$el.id}" style="padding:0px; margin:0px;">
   <INPUT type="checkbox" onclick="this.form.submit();">
   <INPUT type="hidden" name="super" value="1">
  </FORM>
  {/if}
  <TD><a href="{getUrl controller='admin_system_logoperators' action='list' search="operator_login:`$el.login`"}">{$el.login}</a></TD>
  <TD>{$el.organizer_name}</TD>
  <TD align="center">{$el.country_name}</TD>
  <TD align="center">{$el.city_name}</TD>
  <TD align="center">{$el.create_time}</TD>
 {include file="common/Actions/general.tpl" el=$el}
 </TR>
{/foreach}
</TABLE>

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>
objOrganizersList = Shelby_Backend.ListHelper.cloneObject('objOrganizersList');

objOrganizersList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objOrganizersList.returnFieldId = 'organizers_id';
objOrganizersList.feedUrl = '{getUrl controller="admin_ep_organizers" action="list" feed="json"}';
objOrganizersList.writeForm();
</SCRIPT>

<p>
<form method="post" action="{getUrl add='1' action='insert'}">
<INPUT type="hidden" name="super" value="1" />
<TABLE>
<TR><TD>Имя пользователя:</TD><TD><INPUT type="text" name="login" /></TD></TR>
<TR><TD>Пароль:</TD><TD><INPUT type="text" name="passwd" /></TD></TR>
<TR><TD>Организатор:</TD><TD><INPUT type="text" name="organizers_id" id="organizers_id" size="5" /> <INPUT type="button" onclick="objOrganizersList.showPopUp();" value="Выбрать"></TD></TR>
<TR><TD>Язык:</TD><TD>
<select name="user_languages_id">
 {foreach from=$list_languages item="el"}
  <option value="{$el.id}">{$el.name}</option>
 {/foreach}
</select>
</TD></TR>
</TABLE>
<INPUT type="submit" value="Добавить" />
</FORM>
</p>