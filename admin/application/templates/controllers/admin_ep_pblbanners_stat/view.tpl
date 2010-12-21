<h1>Подробная статистика по рекламному объявлению</h1>

<p><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></p>

&nbsp;

{* <link type="text/css" rel="stylesheet" href="http://ws.expopromoter.com/bns/v2pbl/pbl_banners_style.css" /> *}
<div class="banner-wrapper">
  <a target="_blank" href="{$entry.banner.url}">
    <span class="banner-txt-image"><img border="0" alt="{$entry.banner.file_alt}" src="http://62.149.12.130/bn/pbldata/{$entry.banner.file_name}?{1|mt_rand:10000}"/></span>
    <span class="banner-txt-header">{$entry.banner.file_alt}</span>
    <span class="banner-txt-content">{$entry.banner.text_content}</span>
  </a>
</div>


<h3 align="center">По модулям</h3>

<table class="list" align="center" width="50%">
 <tr><th>Название</th><th width="70">Показов</th></tr>
 {foreach from=$entry.modules item="el"}
  <tr class="{cycle values="odd,even"}">
   <td>{if empty($el.module_name)}Остальные модули{else}{$el.module_name}{/if}</td>
   <td align="center">{$el.shows}</td>
  </tr>
 {/foreach}
</table>

<br /><br />

<table width="100%">
<tr>
  <td width="30%" align="left" valign="top" style="padding-right:20px">
    <h3>Показы и клики по дням</h3>

    <table class="list" width="100%">
    <tr>
      <th style="text-align:left">Дата</th>
      <th width="60" style="text-align:center">Показы</th>
      <th width="60" style="text-align:center">Клики</th>
    </tr>
    {foreach from=$entry.general item="el"}
    <tr class="{cycle values="odd,even"}">
      <td style="text-align:left">{$el.date_show}</td>
      <td style="text-align:center">{$el.shows}</td>
      <td style="text-align:center">{$el.clicks}</td>
    </tr>
    {/foreach}
    </table>
  </td>

  <td width="70%" align="left" valign="top">
    <h3>Клики</h3>

    <table class="list" width="100%">
    <tr>
      <th style="text-align:left">Дата</th>
      <th style="text-align:left">Издатель</th>
      <th style="text-align:center">Цена</th>
      <th style="text-align:center">IP</th>
      <th style="text-align:center">Страна</th>
    </tr>
    {foreach from=$entry.clicks item="el"}
    <tr class="{cycle values="odd,even"}">
      <td style="text-align:left">{$el.date_click}</td>
      <td style="text-align:left">{$el.site_name}</td>
      <td style="text-align:center">{$el.price}</td>
      <td style="text-align:center">{$el.ip}</td>
      <td style="text-align:center">{$el.country_code}</td>
    </tr>
    {/foreach}
    </table>
  </td>

  <td width="20%" align="center" valign="top">
    <h3>Издатели</h3>
    
    <table class="list">
    <tr>
      <th>Издатель</th><th width="70">Показов</th>
    </tr>
    {foreach from=$entry.sites item="el"}
    <tr class="{cycle values="odd,even"}">
      <td>{$el.site_name}</td>
      <td align="center">{$el.shows}</td>
    </tr>
    {/foreach}
    </table>
  </td>
</tr>
</table>

<p><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></p>
