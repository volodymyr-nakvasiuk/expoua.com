<h4>{if empty($entry_event)}Галереи событий{else}Галерея события {$entry_event.brand_name} {$entry_event.date_from}{/if}</h4>

<STYLE type="text/css">{literal}
 div.list_thumbs div {
	width:120px;
	float:left;
	text-align:center;
	margin-bottom:10px;
}
{/literal}</STYLE>
<link rel="stylesheet" href="http://www.expopromoter.com/css/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="http://www.expopromoter.com/js/thickbox.js"></script>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

{if !empty($entry_event)}
<FORM method="post" enctype="multipart/form-data" action="{getUrl add=1 action="insert"}">
Загрузить изображение: <INPUT type="file" name="image"/>
<INPUT type="submit" value="Загрузить"/>
<INPUT type="hidden" name="dummy" value="0"/>
</FORM>
{/if}

{*
<div class="list_thumbs">
{foreach from=$list.data item="el"}
 <div>
  <a href="/data/images/events/{$el.events_id}/gallery/{$el.id}.jpg" class="thickbox" rel="gallery" target="_blank"><img src="/data/images/events/{$el.events_id}/gallery/{$el.id}_tb.jpg" class="tb"/></a><br/>
  <a href="{getUrl add="1" parent=$el.events_id}">{$el.date_from} {$el.brand_name}</a><br/>
  <a href="{getUrl add="1" action="delete" id=$el.id}" onclick="return Shelby_Backend.confirmDelete();"><img title="Удалить событие" src="/images/admin/icons/delete.gif" border="0" width="16"></a>
 </div>
{/foreach}
</div>
*}

<table cellspacing="4" width="95%">
<tr valign="bottom">
{foreach name="ilist" from=$list.data item="el"}
  <td width="20%" align="center" style="background:#efefef; padding:5px;">
    <a href="/data/images/events/{$el.events_id}/gallery/{$el.id}.jpg" class="thickbox" rel="gallery" target="_blank" title="{$el.date_from} {$el.brand_name}">
      <img src="/data/images/events/{$el.events_id}/gallery/{$el.id}_tb.jpg" class="tb"/></a><br/>
    <a href="{getUrl add="1" parent=$el.events_id}">{$el.date_from} {$el.brand_name}</a><br/>
    <a href="{getUrl add="1" action="delete" id=$el.id}" onclick="return Shelby_Backend.confirmDelete();">
      <img title="Удалить событие" src="/images/admin/icons/delete.gif" border="0" width="16"></a>
  </td>
  {if $smarty.foreach.ilist.iteration % 5 == 0 && !$smarty.foreach.ilist.last}</tr><tr valign="bottom">{/if}
{/foreach}
</tr>
</table>
