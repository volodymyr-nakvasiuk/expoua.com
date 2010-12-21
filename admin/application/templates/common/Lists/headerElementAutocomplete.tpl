{if !isset($action)}{assign var="action" value="list"}{/if}
<SCRIPT type="text/javascript" language="Javascript">
objAC_{$name} = Shelby_Backend.Autocomplete.cloneObject('objAC_{$name}');
objAC_{$name}.feedUrl = '{getUrl controller=$controller action=$action feed="json"}';
objAC_{$name}.baseSearchUrl = '{getUrl add="1" del="search,page"}';
objAC_{$name}.columnName = '{$name}';
{if isset($persistentFilter)}objAC_{$name}.persistentFilter = '{$persistentFilter}';{/if}
</SCRIPT>

<TH {if !empty($width)}width="{$width}"{/if} align="{if !empty($align)}{$align}{else}center{/if}"><NOBR>
 <a href="{getUrl add="1" sort=$HMixed->getSortOrder($name, $list.sort_by)}">{$descr}</a>
 <IMG src="{$document_root}images/admin/icons/icon_search.gif" style="float:right; cursor:pointer;" onclick="objAC_{$name}.toggleForm();" /></NOBR>

 <SCRIPT type="text/javascript">objAC_{$name}.writeForm();</SCRIPT>
</TH>