<h2>{$event[$selected_language].brand_name} ({$event.date_from|date_format:'%d/%m'} - {$event.date_to|date_format:'%d/%m/%Y'})</h2>

<p align="right">{#msgStatisticsSince#}</p>


{if empty($list.data)}
<p>{#records_notpresent#}</p>
{/if}

<p style="text-align:right">
  {include file="common/tip.tpl" topic="organizer_csv_export"} 
  <a href="{getUrl add=1 page=1 results=999999 feed=csv}">CSV Export</a>
</p>

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

{if $mode == 'Hits'}

  <table border="0" width="100%" class="list">
  <tr>
    <th>{#captionTime#}</th>
    <th>{#captionSiteName#}</th>
    <th>{#captionLanguage#}</th>
    <th>{#captionIpAddress#}</th>
    <th>{#country#}</th>
  </tr>
  
  {foreach from=$list.data item="el" name="fe"}
  <tr class="{cycle values="odd,even"}">
    <td>{$el.hit_time}</td>
    <td>{$el.site_name}</td>
    <td>{$el.lang}&nbsp;</td>
    <td>{$el.ip_addr}</td>
    <td>{$el.country_name|default:"&mdash;"}</td>
  </tr>
  {/foreach}
  </table>

{elseif $mode == 'Redirects'}

  <table border="0" width="100%" class="list">
  <tr>
    <th>{#captionTime#}</th>
    <th>{#captionReferer#}</th>
    <th>{#captionLanguage#}</th>
    <th>{#captionIpAddress#}</th>
    <th>{#country#}</th>
  </tr>
  
  {foreach from=$list.data item="el" name="fe"}
  <tr class="{cycle values="odd,even"}">
    <td>{$el.redirect_time}</td>
    <td>{$el.referer}</td>
    <td>{$el.lang}&nbsp;</td>
    <td>{$el.ip_addr}</td>
    <td>{$el.country_name|default:"&mdash;"}</td>
  </tr>
  {/foreach}
  </table>

{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<p>
  <a href="{getUrl add="1" del="id,mode,page" action='list'}">{#linkBackToList#}</a>
</p>