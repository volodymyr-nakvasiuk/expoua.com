<H4>Созданные черновики</H4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{else}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center" width="30">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="40" align="center" name="id" descr="Id"}
 {include file="common/Lists/headerElementGeneral.tpl" width="70" align="center" name="status" descr="Готово"}
 {include file="common/Lists/headerElementGeneral.tpl" width="80" align="center" name="type" descr="Тип"}
 {include file="common/Lists/headerElementGeneral.tpl" width="70" align="center" name="date_from" descr="Дата с"}
 {include file="common/Lists/headerElementGeneral.tpl" width="70" align="center" name="date_to" descr="Дата по"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="brand_name" descr="Бренд"}
 <TH align="center" colspan="2">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{if $el.status==-1}marked{else}{cycle values="odd,even"}{/if}" onmouseover="$('#cmnt_{$el.id}').css('display', 'inline');" onmouseout="$('#cmnt_{$el.id}').css('display', 'none');">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD align="center">
  <FORM method="post" action="{getUrl add="1" action="update" id=$el.id}" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" {if $el.status==1} checked{/if} onclick="this.form.submit();">
  <INPUT type="hidden" name="common[status]" value="{if $el.status==1}0{else}1{/if}">
  <input type="hidden" name="ru[dummy]" value=""/>
  </FORM>
  {if !empty($el.comments)}<DIV style="display:none; border: 1px solid #000000; position:absolute; background-color:#FFFFFF; top:50px;" id="cmnt_{$el.id}">{$el.comments|nl2br}</DIV>{/if}
  </TD>
  <TD align="center">{if $el.type=="edit"}Изменение{else}Добавление{/if}</TD>
  <TD align="center">{$el.date_from}</TD>
  <TD align="center">{$el.date_to}</TD>
  <TD align="center">{if !empty($el.brand_name)}{$el.brand_name}{else}{$el.brand_name_new}{/if}</TD>

  <TD align="center" width="20"><a href="{getUrl add="1" action="edit" id=$el.id}"><img title="Изменить" src="/images/admin/icons/edit_brand.gif" border="0" width="16"></a></TD>

 <TD align="center" width="20"><a href="{getUrl add="1" action="delete" id=$el.id}" onclick="return Shelby_Backend.confirmDelete();"><img title="Удалить" src="/images/admin/icons/page_delete.gif" border="0" width="16"></a></TD>

 </TR>
{/foreach}
</TABLE>

{/if}
