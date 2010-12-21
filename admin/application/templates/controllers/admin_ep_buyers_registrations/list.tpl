<h4>Регистрации</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center" width="250">Название выставки</TH>
 <TH align="center">Причина отказа</TH>
 <TH align="center" width="100">Статус</TH>
 <TH align="center" colspan="3">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD>{$el.brand_name}</TD>
  <TD>{$el.cancellation_reason}</TD>
  <TD align="center">
   {if $el.cancellation_approve_status === "-1"}
    заявка отклонена
   {elseif $el.cancellation_approve_status === "1"}
    удалено
   {elseif $el.cancellation_approve_status === "0"}
    заявка на удаление
   {/if}
  </TD>
{if $el.cancellation_approve_status === "0"}
  <TD align="center">
   <FORM method="post" action="{getUrl add="1" action="update" id=$el.id_registration}" style="padding:0px; margin:0px;">
    <INPUT type="button" value="подтвердить отмену" onclick="this.form.submit();">
    <INPUT type="hidden" name="approve_status" value="1">
   </FORM>
  </TD>
  <TD align="center">
   <FORM method="post" action="{getUrl add="1" action="update" id=$el.id_registration}" style="padding:0px; margin:0px;">
    <INPUT type="button" value="отказать в отмене" onclick="this.form.submit();">
    <INPUT type="hidden" name="approve_status" value="-1">
   </FORM>
  </TD>
{else}
  <TD></TD>
  <TD></TD>
{/if}
  <TD width="20" align="center"><a href="{getUrl add=1 action="view" id=$el.id_registration}"><img alt="просмотр рег. данных" src="{$document_root}images/admin/icons/page_text.gif" border="0" width="16"></a></TD>
 </TR>
{/foreach}
</TABLE>