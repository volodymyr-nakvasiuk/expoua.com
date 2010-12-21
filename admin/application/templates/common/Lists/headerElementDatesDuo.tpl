<TH {if !empty($width)}width="{$width}"{/if} align="{if !empty($align)}{$align}{else}center{/if}">
 <a href="{getUrl add="1" sort=$HMixed->getSortOrder($name, $list.sort_by)}">{$descr}</a>
 <IMG src="{$document_root}images/admin/icons/icon_search.gif" style="float:right; cursor:pointer;" onclick="Shelby_Backend.toggle_search('{$name}');" />
 <DIV style="clear:both; display:none; position:absolute; width:100px; background:white; border: 1px solid #6f5d15;" align="center" id="list_header_search_div_{$name}">
 <DIV style="border-bottom:1px solid #DDDDDD; width:100%; cursor:pointer;" onClick="$('#list_header_search_div_{$name}').css('display', 'none');">X</DIV>
  <FORM method="post" onsubmit="Shelby_Backend.table_header_search_dates_duo('{getUrl add="1" del="search,page"}'); return false;" style="clear:both; padding:0px; float:right;">
   с <INPUT type="text" style="width:80px;" id="list_header_search_date_from"><BR />
   по <INPUT type="text" style="width:80px;" id="list_header_search_date_to">
   <INPUT type="submit" value="&gt;">
  </FORM>
 </DIV>
</TH>

<SCRIPT type="text/javascript">
$(document).ready(function() {ldelim}
	$('#list_header_search_date_from').datepicker();
	$('#list_header_search_date_to').datepicker();
{rdelim});
</SCRIPT>