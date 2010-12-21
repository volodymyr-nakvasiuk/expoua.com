<a href="{getUrl add="1" del="parent,page"}" class="func_button"><img alt="Корень" src="{$document_root}images/admin/icons/page_left.gif" border="0" width="16"> Корень</a>
{foreach from=$trail item="el"}
 -> <a href="{getUrl add="1" del="page" parent=$el.id}">{$el.name}</a>
{/foreach}