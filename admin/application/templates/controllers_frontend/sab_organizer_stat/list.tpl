<table width="100%" cellpadding="0" cellspacing="0">
<tr valign="top">
  <td align="left">{#msgStatisticsDisclaimer#}</td>
  <td align="right">{#msgStatisticsSince#}</td>
</tr>
</table>


{if $mode == 'details'}


{if empty($list.data) && empty($list_drafts.data)}
<p>{#records_notpresent#}</p>
{/if}

<p style="text-align:right">
  {include file="common/tip.tpl" topic="organizer_csv_export"} 
  <a href="{getUrl add=1 page=1 results=999999 feed=csv}">CSV Export</a>
</p>

<table border="0" width="100%" class="list">
<tr>
  <th align="center" width="90">{#dates#}</th>
  <th align="center">{#title#}</th>
  <th align="center">{#exhCenter#}</th>
  <th align="center">{#country#} / {#city#}</th>
  
  <th align="center">{#captionTime#}</th>
  <th align="center">{#captionSiteName#}</th>
  <th align="center">{#captionLanguage#}</th>
  <th align="center">{#captionIpAddress#}</th>
  <th align="center">{#country#}</th>
</tr>

{foreach from=$list.data item="el"}
{foreach from=$el.hits item="hit"}

<tr class="{cycle values="odd,even"} {if $el.active != 1}grey_hilight{else}blue_hilight{/if}">
  <td align="center">{$el.date_from}<br />{$el.date_to}</td>
  <td>{$el.name}&nbsp;</td>
  <td>{$el.expocenter_name}&nbsp;</td>
  <td align="center">{$el.country_name} / {$el.city_name}&nbsp;</td>

  <td>{$hit.hit_time}</td>
  <td>{$hit.site_name}</td>
  <td>{$hit.lang}&nbsp;</td>
  <td>{$hit.ip_addr}</td>
  <td>{$hit.country_name|default:"&mdash;"}</td>
</tr>

{/foreach}
{/foreach}

</table>


{else}

{if empty($list.data) && empty($list_drafts.data)}
<p>{#records_notpresent#}</p>
{/if}

<p style="text-align:right"><a href="{getUrl add=1 page=1 results=999999 feed=csv}">CSV Export</a></p>

<table border="0" width="100%" class="list">
<tr>
  <th align="center" width="90">{#dates#}</th>
  <th align="center">{#title#}</th>
  <th align="center">{#exhCenter#}</th>
  <th align="center">{#country#}</th>
  <th align="center">{#city#}</th>
  <th align="center">{#captionViews#}</th>
  <th align="center">{#captionRequests#}</th>
  <th align="center">{#captionSiteRedirs#}</th>
</tr>

{foreach from=$list.data item="el" name="fe"}
<tr class="{cycle values="odd,even"} {if $el.active != 1}grey_hilight{else}blue_hilight{/if}">
  <td align="center">{$el.date_from}<br />{$el.date_to}</td>
  <td>{$el.name}&nbsp;</td>
  <td>{$el.expocenter_name}&nbsp;</td>
  <td align="center">{$el.country_name}&nbsp;</td>
  <td align="center">{$el.city_name}&nbsp;</td>
  <td align="center"><a href="{getUrl controller='sab_organizer_stat' action='view' mode='Hits' id=$el.id}">{$el.view_cnt}</a></td>
  <td align="center"><a href="{getUrl controller='sab_organizer_requests' action='list' search="events_id=`$el.id`"}">{$el.req_cnt}</a></td>
  <td align="center">{if $el.redir_cnt}<a href="{getUrl controller='sab_organizer_stat' action='view' mode='Redirects' id=$el.id}">{$el.redir_cnt}</a>{else}&mdash;{/if}</td>
</tr>
{/foreach}
</table>

{/if}