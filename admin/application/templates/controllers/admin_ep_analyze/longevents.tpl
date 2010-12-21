<H4>События с возможно неверной датой проведения</H4>

<TABLE class="list" width="100%">
 <TR><TH align="center">N</TH><TH align="center" width="10">Id</TH><TH align="center" width="10">A</TH><TH align="center">Дата с</TH><TH>Дата по</TH><TH>Дней</TH><TH width="30">Действия</TH></TR>
 {assign var="npp" value=0}
 {foreach from=$list item="el"}
  <TR class="{cycle values="odd,even"}">
   <TD align="center">{assign var="npp" value="`$npp+1`"}{$npp}</TD>
   <TD align="center">{$el.id}</TD>
   <TD align="center">
   <FORM method="post" action="{getUrl add="1" controller="admin_ep_events" action="update" id=$el.id}" style="padding:0px; margin:0px;" target="_blank">
   <INPUT type="checkbox" {if $el.active==1} checked{/if} onclick="this.form.submit();">
   <INPUT type="hidden" name="active" value="{if $el.active==1}0{else}1{/if}">
   </FORM>
   </TD>
   <TD align="center">{$el.date_from}</TD>
   <TD align="center">{$el.date_to}</TD>
   <TD align="center">{$el.days}</TD>
   <TD align="center"><a href="{getUrl add="1" controller="admin_ep_events" action="edit" id=$el.id}" target="_blank"><img title="Изменить событие" src="/images/admin/icons/edit_event.gif" border="0" width="16"></a></TD>
  </TR>
 {/foreach}
</TABLE>