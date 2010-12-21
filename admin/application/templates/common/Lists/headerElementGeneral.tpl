<TH {if !empty($width)}width="{$width}"{/if} align="{if !empty($align)}{$align}{else}center{/if}">
 <a href="{getUrl add="1" sort=$HMixed->getSortOrder($name, $list.sort_by)}">{$descr}</a>
 <IMG src="{$document_root}images/admin/icons/icon_search.gif" style="float:right; cursor:pointer;" onclick="Shelby_Backend.toggle_search('{$name}');" />
 <DIV style="clear:both; display:none;" align="center" id="list_header_search_div_{$name}">
  <FORM method="post" onsubmit="Shelby_Backend.table_header_search('{getUrl add="1" del="search,page"}', '{$name}'{if $stype}, '{$stype}'{/if}); return false;">
   <INPUT type="text" style="width:90%;" id="list_header_search_kw_{$name}">
  </FORM>
 </DIV>
</TH>