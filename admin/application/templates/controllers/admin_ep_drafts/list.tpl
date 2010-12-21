<H4>Созданные черновики</H4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{else}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="id" descr="Id"}
 {include file="common/Lists/headerElementDatesDuo.tpl" width="80" name="date_from" descr="Даты"}
 {include file="common/Lists/headerElementGeneral.tpl" width="80" align="center" name="type" descr="Тип"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="brand_name" descr="Бренд"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="organizers_id" controller="admin_ep_organizers" descr="Организатор"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="date_add" descr="Дата добавления"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="login" descr="Оператор"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="operator_type" descr="Тип"}
 <TH align="center" colspan="2">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{if $el.operator_type=='organizer'}{if $el.premium==1}marked{else}marked_blue{/if}{else}{cycle values="odd,even"}{/if}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD align="center">{$el.date_from}<BR />{$el.date_to}</TD>
  <TD align="center">{if $el.type=="edit"}Изменение{else}Добавление{/if}</TD>
  <TD>{if !empty($el.brand_name)}{$el.brand_name}{else}{$el.brand_name_new}{/if}</TD>
  <TD><a href="{getUrl add="1" search="organizers_id=`$el.organizers_id`"}">{$el.organizer_name}</a></TD>
  <TD align="center">{$el.date_add}</TD>
  <TD align="center"><a href="{if $el.operator_type=='organizer'}{getUrl controller="admin_acl_organizers" action="edit" id=$el.users_operators_id}{else}{getUrl controller="admin_acl_operators" action="edit" id=$el.users_operators_id}{/if}">{$el.login}</a></TD>

  <TD align="center"><a href="{getUrl add=1 search="operator_type:`$el.operator_type`"}">{$el.operator_type}</a></TD>

  <TD align="center" width="20"><a href="{getUrl add="1" action="edit" id=$el.id}"><img title="Просмотреть" src="/images/admin/icons/edit_brand.gif" border="0" width="16"></a></TD>

 <TD align="center" width="20"><a href="{getUrl add="1" action="delete" id=$el.id}" onclick="return Shelby_Backend.confirmDelete();"><img title="Удалить" src="/images/admin/icons/page_delete.gif" border="0" width="16"></a></TD>

 </TR>
{/foreach}
</TABLE>
<p><b>Всего записей: </b> {$list.rows}</p>

{/if}
