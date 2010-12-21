{if $entry_event}<h2>{#captionTicketsFor#} {$event.brand_name} ({$event.date_from} &ndash; {$event.date_to})</h2>{/if}

{if empty($list.data)}

  <p>{#msgListEmpty#}</p>

{else}

  <p style="text-align:right">
    {include file="common/tip.tpl" topic="organizer_csv_export"} 
    <a href="{getUrl add=1 page=1 results=999999 feed=csv}">CSV Export</a>
  </p>

  <table border="0" width="100%" class="list">
  <tr>
   <th style="width:160px">{#ticket_date#}</th>
   <th>{#FIO#}</th>
   <th>{#company#}</th>
   <th>{#position#}</th>
   {*<th style="width:200px">{#site#}</th>*}
  </tr>
  
  {foreach from=$list.data item="el" name="fe"}
  <tr valign="top" class="{cycle values="odd,even"}">
    <td align="left">{$el.time_created}</td>
    
    <td align="left">
      <div><a href="{getUrl add=1 del='page' action='view' user=$el.user_id}"><strong>{$el.fname} {$el.lname}</strong></a></div>
    </td>
    
    <td>{$el.companyName}</td>
    <td>{$el.positionName}</td>
    
    {*<td align="left"><a href="{$el.site_url}" target="_blank">{$el.site_name}</a></td>*}
  </tr>
  {/foreach}
  </table>

{/if}


<p><a href="{getUrl controller=$user_params.controller action='index'}">{#linkBackToList#}</a></p>