<H4>Список брендов без будущих событий</H4>

<FORM method="post">
<CENTER> Отобразить до
 <SELECT name="year">
  <OPTION>(Не выбрано)</OPTION>
  <OPTION value="2010">2010</OPTION>
  <OPTION value="2009">2009</OPTION>
  <OPTION value="2008">2008</OPTION>
  <OPTION value="2007">2007</OPTION>
  <OPTION value="2006">2006</OPTION>
 </SELECT> года
 <INPUT type="submit" value="Ok">
</CENTER>
</FORM>

<TABLE class="list" width="100%">
 <TR>
   <TH align="center" width="10">N</TH>
   <TH align="center" width="20">Id</TH>
   <TH align="center">Название</TH>
   {*<TH>Организатор</TH>*}
   {include file="common/Lists/headerElementGeneral.tpl" align="center" name="organizer_name" descr="Организатор"}
   {include file="common/Lists/headerElementAutocomplete.tpl" name="countries_id" controller="admin_ep_locations_countries" descr="Страна"}
 </TR>
 {assign var="npp" value=0}
 {foreach from=$list item="el"}
  <TR class="{cycle values="odd,even"}">
   <TD align="center">{assign var="npp" value="`$npp+1`"}{$npp}</TD>
   <TD>{$el.id}</TD>
   <TD>{$el.name}</TD>
   <TD>{$el.organizer_name}</TD>
   <TD>{$el.country_name}</TD>
  </TR>
 {/foreach}
</TABLE>
