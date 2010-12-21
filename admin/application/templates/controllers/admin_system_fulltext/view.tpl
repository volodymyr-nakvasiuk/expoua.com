<h4>Детальная информация о состоянии индекса</h4>

<h5 align="center">Общая информация</h5>

<table align="center" class="list">
 <tr class="odd">
  <td>Документов: </td>
  <td align="center">{$entry.documents}</td>
 </tr>
 <tr class="even">
  <td>Слов всего: </td>
  <td align="center">{$entry.words}</td>
 </tr>
 <tr class="odd">
  <td>Уникальных слов: </td>
  <td align="center">{$entry.words_distinct}</td>
 </tr>
</table>

<h5 align="center">Top наиболее часто встречающихся базовых словоформ</h5>

<table align="center" class="list">
 <tr><th>Базовая словоформа</th><th>Количество</th></tr>
 {foreach from=$entry.words_top item="el"}
  <tr class="{cycle values="odd,even"}">
   <td>{$el.name}</td>
   <td align="center">{$el.cnt}</td>
  </tr>
 {/foreach}
</table>

<p><a href="{getUrl controller='admin_system_fulltext'}">Вернуться</a></p>