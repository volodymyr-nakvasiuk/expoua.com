<h4>Прикрепленные файлы к выставкам</h4>

{if empty($list.data)}
<p>Записи отсутсвуют</p>
<p><a href="{getUrl controller="admin_ep_eventsfiles" action="list"}">Показать все файлы</a></p>
{else}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="id" descr="Id"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="events_id" descr="Id События"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="name" descr="Название файла"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="size" descr="Размер"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="brand_name" descr="Бренд"}
 <TH align="center" colspan="3">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD align="center">{$el.events_id}</TD>
  <TD>{$el.name}</TD>
  <TD align="center">{$el.size}</TD>
  <TD align="center">{$el.brand_name}</TD>
 {include file="common/Actions/general.tpl" el=$el}
 </TR>
{/foreach}
</TABLE>

{/if}

{if isset($user_params.event_id)}
<p>
 <FORM method="post" action="{getUrl add="1" action="insert"}" enctype="multipart/form-data">
  <INPUT type="hidden" name="events_id" value="{$user_params.event_id}" />
  Название: <INPUT type="text" name="name" size="30"><BR />
  Файл: <INPUT type="file" name="upload_file"><BR />
  <INPUT type="submit" value="Загрузить">
 </FORM>
</p>

<P><a href="{getUrl controller="admin_ep_events" action="edit" id=$user_params.event_id}">Вернуться к редактированию выставки</a></P>
{/if}