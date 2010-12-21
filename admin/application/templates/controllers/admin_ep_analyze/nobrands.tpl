<H4>Список организаторов без брендов</H4>

<TABLE class="list" width="100%">
 <TR><TH align="center" width="10">N</TH><TH align="center" width="20">Id</TH><TH align="center">Название</TH>
 <TH>Город</TH><TH>Страна</TH></TR>
 {assign var="npp" value=0}
 {foreach from=$list item="el"}
  <TR class="{cycle values="odd,even"}">
   <TD align="center">{assign var="npp" value="`$npp+1`"}{$npp}</TD>
   <TD>{$el.id}</TD>
   <TD><a href="{getUrl controller="admin_ep_organizers" action="list" search="id=`$el.id`"}">{$el.name}</a></TD>
   <TD>{$el.city_name}</TD>
   <TD>{$el.country_name}</TD>
  </TR>
 {/foreach}
</TABLE>