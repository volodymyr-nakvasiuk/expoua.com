{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>{#msgNoRecords#}</p>
{/if}

<h4>{#hdrNewsList#}</h4>

<input type="button" value="{#actionAddNews#}" onClick="document.location.href='{getUrl add='1' action='add' del='page'}';"><br><br>

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center" width="40">{#captionNo#}</TH>
 <TH align="center" width="90">{#captionImage#}</TH>
 <TH align="center">{#captionName#}</TH>
 <TH align="center">{#captionDate#}</TH>
 <TH align="center" colspan="2">{#captionActions#}</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}

{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{if $el.logo==1}<img src="/data/images/companies/{$el.companies_id}/news/logo/{$el.id}.jpg">{else}
  <img src="/images/admin/nofoto_small.gif">{/if}</TD>
  <TD>{$el.name}</TD>
  <td align="center">{$el.date_public}&nbsp;</td>

<TD width="20" align="center"><a href="{getUrl add="1" action="edit" id=$el.id}"><img title="{#editAction#}" src="{$document_root}images/admin/list-edit.gif" border="0" width="23" height="21"></a></TD>
<TD width="20" align="center"><a href="{getUrl add="1" action="delete" id=$el.id}" onclick="return Shelby_Backend.confirmDelete();"><img alt="{#deleteAction#}" src="{$document_root}images/admin/icons/delete.gif" border="0" width="16"></a></TD>

  </TR>
{/foreach}
</TABLE>