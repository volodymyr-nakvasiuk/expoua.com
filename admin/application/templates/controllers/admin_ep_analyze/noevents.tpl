<H4>Список брендов без событий</H4>

<TABLE class="list" width="100%">
 <TR><TH align="center" width="10">N</TH><TH align="center" width="20">Id</TH><TH align="center">Название</TH><TH align="center">Организатор</TH><TH align="center" width="80">Действие</TH></TR>
 {assign var="npp" value=0}
 {foreach from=$list item="el"}
  <TR class="{cycle values="odd,even"}">
   <TD align="center">{assign var="npp" value="`$npp+1`"}{$npp}</TD>
   <TD>{$el.id}</TD>
   <TD>{$el.name}</TD>
   <TD>{$el.organizer_name}</TD>
   <TD align="center"><a href="{getUrl controller="admin_ep_brands" action="delete" id=$el.id}?redirURL={getUrl add="1"}" onclick="return Shelby_Backend.confirmDelete();"><img alt="Удалить" src="{$document_root}images/admin/icons/delete.gif" border="0" width="16"></a></TD>
  </TR>
 {/foreach}
</TABLE>