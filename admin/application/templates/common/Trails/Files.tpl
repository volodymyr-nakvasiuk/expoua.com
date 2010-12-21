<a href="{getUrl add="1" del="parent,page"}" class="func_button"><img alt="Начало" src="{$document_root}images/admin/icons/page_left.gif" border="0" width="16"> Начало</a>
{foreach from=$trail item="el"}
 {if isset($path)}
  {assign var="path" value="`$path`:`$el`"}
 {else}
  {assign var="path" value=$el}
 {/if}
 -> <a href="{getUrl add="1" del="page" parent=$path}">{$el}</a>
{/foreach}