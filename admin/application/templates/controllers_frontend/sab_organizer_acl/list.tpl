{if empty($list.data)}
{#notUserAdded#}
{else}
<TABLE border="0" width="100%" class="list" align="center">
<TR>
 <TH align="center" width="10%">{#onOff#}</TH>
 <TH align="center" width="10%">{#adminuser#}</TH>
 <TH align="left">{#login#}</TH>
 <TH align="left">{#FIO#}</TH>
 <TH align="center" colspan="2">{#actions#}</TH>
</TR>
{foreach from=$list.data item="el"}
<TR class="{cycle values="odd,even"}">
 <TD align="center">
  <FORM method="post" action="{getUrl add="1" action="update" id=$el.id}" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" {if $el.active==1} checked{/if} onclick="this.form.submit();">
  <INPUT type="hidden" name="active" value="{if $el.active==1}0{else}1{/if}">
  </FORM>
 </TD>
 <TD align="center"><INPUT type="checkbox" disabled="disabled" {if $el.super==1}checked="checked"{/if} /></TD>
 <TD>{$el.login}</TD>
 <TD>{$el.name_fio}&nbsp;</TD>
 {include file="common/Actions/general.tpl"}
</TR>
{/foreach}
</TABLE>
{/if}

