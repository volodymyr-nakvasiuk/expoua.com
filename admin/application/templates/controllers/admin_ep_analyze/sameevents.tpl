<H4>Список одинаковых событий</H4>

<TABLE class="list" width="100%">
 <TR><TH align="center" width="10">N</TH><TH align="center">Даты</TH><TH align="center">Количество</TH><TH align="center" width="80">Действие</TH></TR>
 {assign var="npp" value=0}
 {foreach from=$list item="el"}
  <TR class="{cycle values="odd,even"}">
   <TD align="center">{assign var="npp" value="`$npp+1`"}{$npp}</TD>
   <TD align="center">{$el.date_from}<BR />{$el.date_to}</TD>
   <TD align="center">{$el.cnt}</TD>
   <TD align="center"><a href="{getUrl controller="admin_ep_brandsevents" action="list" search="brands_id=`$el.brands_id`;date_from=`$el.date_from`"}"><img alt="Показать список" src="{$document_root}images/admin/icons/page_text.gif" border="0" width="16"></a></TD>
  </TR>
 {/foreach}
</TABLE>