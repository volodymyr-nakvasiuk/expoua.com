<h4>База категорий товаров и услуг компаний</h4>

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center" width="30">N</TH>
 <TH align="center" width="30">A</TH>
 <TH align="center">Название</TH>
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
  <INPUT type="hidden" name="common[active]" value="{if $el.active==1}0{else}1{/if}">
  <INPUT type="hidden" name="en[dummy]" value="1">
  </FORM>
 </TD>
  <TD>{$el.name}</TD>

<TD width="20" align="center"><a href="{getUrl add="1" action="edit" id=$el.id}"><img title="Изменить" src="{$document_root}images/admin/list-edit.gif" border="0" width="23" height="21"></a></TD>
<TD width="20" align="center"><a href="{getUrl add="1" action="delete" id=$el.id}" onclick="return Shelby_Backend.confirmDelete();"><img alt="Удалить" src="{$document_root}images/admin/icons/delete.gif" border="0" width="16"></a></TD>

 </TR>
{/foreach}
</TABLE>

<p><a href="{getUrl add='1' action='add' del='page'}">Добавить новую запись</a></p>