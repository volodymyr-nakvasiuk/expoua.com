<H4>Список выставочных центров с одинаковыми названиями</H4>

<TABLE class="list" width="100%">
 <TR><TH align="center" width="10">N</TH><TH align="center">Название</TH><TH align="center">Количество</TH><TH align="center" width="80">Действие</TH></TR>
 {assign var="npp" value=0}
 {foreach from=$list item="el"}
  <TR class="{cycle values="odd,even"}">
   <TD align="center">{assign var="npp" value="`$npp+1`"}{$npp}</TD>
   <TD>{$el.name}</TD>
   <TD align="center">{$el.cnt}</TD>
   <TD align="center"><a href="{getUrl controller="admin_ep_expocenters" action="list" search="name=`$el.name`"}"><img alt="Показать список" src="{$document_root}images/admin/icons/page_text.gif" border="0" width="16"></a></TD>
  </TR>
 {/foreach}
</TABLE>