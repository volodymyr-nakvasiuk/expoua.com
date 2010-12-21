<h4>Подробная статистика по плану показа</h4>

<DIV align="right"><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></DIV>

<h5 align="center">По модулям</h5>
<TABLE class="list" align="center" width="50%">
 <tr><th>Название</th><th width="70">Показов</th></tr>
 {foreach from=$entry.modules item="el"}
  <tr class="{cycle values="odd,even"}">
   <td>{if empty($el.module_name)}Остальные модули{else}{$el.module_name}{/if}</td>
   <td align="center">{$el.shows}</td>
  </tr>
 {/foreach}
</TABLE>

<br /><br />

<TABLE width="100%">
 <tr>

  <TD width="20%" align="center" valign="top">
  <H5>Показы по дням</H5>
<TABLE class="list" width="170">
 <tr><th>Дата</th><th width="70">Показов</th></tr>
 {foreach from=$entry.general item="el"}
  <tr class="{cycle values="odd,even"}">
   <td>{$el.date_show}</td>
   <td align="center">{$el.shows}</td>
  </tr>
 {/foreach}
</TABLE>
  </TD>

  <td align="center" valign="top">
  <H5>Клики</H5>
<TABLE class="list" width="90%">
 <tr><th>Дата</th><th>Издатель</th><th>Язык</th><th>IP</th></tr>
 {foreach from=$entry.clicks item="el"}
  <tr class="{cycle values="odd,even"}">
   <td>{$el.date_click}</td>
   <td>{$el.publisher_name}</td>
   <td align="center">{$el.lang_name}</td>
   <td align="center">{$el.ip}</td>
  </TR>
 {/foreach}
</TABLE>
  </td>

  <td width="20%" align="center" valign="top">
  <H5>Издатели</H5>
<TABLE class="list">
 <tr><th>Издатель</th><th width="70">Показов</th></tr>
 {foreach from=$entry.publishers item="el"}
  <tr class="{cycle values="odd,even"}">
   <td>{$el.publisher_name}</td>
   <td align="center">{$el.shows}</td>
  </TR>
 {/foreach}
</TABLE>
  </td>

 </tr>
</TABLE>