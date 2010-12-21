{if !empty($list.search)}

 {assign var="tmp" value=""}

 <DIV style="padding-bottom:5px; float:right">
 <b>{#activeFilter#}:</b><BR />
 {foreach from=$list.search item="el" name="fe"}
  {$el.column} {$el.type} {$el.value}<BR />
  {assign var="tmp" value="`$tmp``$el.column``$el.type``$el.value`"}
  {if !$smarty.foreach.fe.last}{assign var="tmp" value="`$tmp`;"}{/if}
 {/foreach}
 <a href="{getUrl add="1" del="search"}">{#dropFilter#}</a>
 </DIV><DIV style="clear:both"></DIV>

 <SCRIPT type="text/javascript">
 Shelby_Backend.currentSearchFilter = '{$tmp}';
 </SCRIPT>

{/if}