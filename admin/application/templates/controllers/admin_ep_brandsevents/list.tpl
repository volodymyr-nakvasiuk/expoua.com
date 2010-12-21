{strip}
<h4>Бренд+Событие</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="id" descr="Id"}
 {include file="common/Lists/headerElementCheckbox.tpl" width="30" name="active" descr="A"}
 {include file="common/Lists/headerElementCheckbox.tpl" width="30" name="premium" descr="P"}
 {include file="common/Lists/headerElementDatesDuo.tpl" width="100" name="date_from" descr="Даты"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="organizers_id" controller="admin_ep_organizers" descr="Организатор"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="brands_id" controller="admin_ep_brands" descr="Бренд"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="expocenters_id" controller="admin_ep_expocenters" descr="Выставочный центр"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="countries_id" controller="admin_ep_locations_countries" descr="Страна"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="cities_id" controller="admin_ep_locations_cities" descr="Город"}
 <TH align="center" colspan="5">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{if $el.brand_dead==1}marked_blue{else}{cycle values="odd,even"}{/if}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD align="center">
  <FORM method="post" action="{getUrl add="1" controller="admin_ep_events" action="update" id=$el.id}" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" {if $el.active==1} checked{/if} onclick="this.form.submit();">
  <INPUT type="hidden" name="active" value="{if $el.active==1}0{else}1{/if}">
  </FORM>
 </TD>
 <TD align="center">
  <FORM method="post" action="{getUrl add="1" controller="admin_ep_events" action="update" id=$el.id}" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" {if $el.premium==1} checked{/if} onclick="this.form.submit();">
  <INPUT type="hidden" name="show_list_logo" value="{if $el.premium==1}0{else}1{/if}">
  </FORM>
 </TD>
  <TD align="center"><nobr>{$el.date_from}</nobr><br />{$el.date_to}</TD>
  <TD align="center"><a href="{getUrl add="1" search="organizers_id=`$el.organizers_id`"}">{$el.organizer_name}</a></TD>
  <TD align="center">{$el.name}</TD>
  <TD align="center">{$el.expocenter_name}</TD>
  <TD align="center">{$el.country_name}</TD>
  <TD align="center"><a href="{getUrl add="1" search="cities_id=`$el.cities_id`"}">{$el.city_name}</a></TD>

  <TD><a href="{getUrl controller="admin_ep_galleries_events" action="list" parent=$el.id}"><img title="Галерея" src="/images/admin/icons/page_component.gif" border="0" width="16"></a></TD>

  <TD><a href="{getUrl add="1" controller="admin_ep_brands" action="edit" id=$el.brands_id}"><img title="Изменить бренд" src="/images/admin/icons/edit_brand.gif" border="0" width="16"></a></TD>

  <TD><a href="{getUrl add="1" controller="admin_ep_events" action="edit" id=$el.id}"><img title="Изменить событие" src="/images/admin/icons/edit_event.gif" border="0" width="16"></a></TD>

  <TD><a href="{getUrl add="1" controller="admin_ep_events" action="copy" id=$el.id}"><img title="Копировать событие" src="/images/admin/icons/copy.gif" border="0" width="15"></a></TD>

 <TD><a href="{getUrl add="1" controller="admin_ep_events" action="delete" id=$el.id}" onclick="return Shelby_Backend.confirmDelete();"><img title="Удалить событие" src="/images/admin/icons/delete.gif" border="0" width="16"></a></TD>

 </TR>
{/foreach}
</TABLE>
<b>Всего записей: </b> {$list.rows}
<p><a href="{getUrl controller="admin_ep_events" action="add"}">Добавить новое событие</a></p>

<p><a href="{getUrl controller="admin_ep_brands" action="add"}">Добавить новый бренд</a></p>

<p><a href="{getUrl controller="admin_ep_brandsevents" action="add"}">Добавить новое бренд+событие</a></p>
{/strip}