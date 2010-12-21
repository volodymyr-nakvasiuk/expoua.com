{foreach from=$tree item="el" name="treestruct"}
 <div style="padding-left:{$depth*10}px;">{if $depth>0}<img src="/images/admin/tree_node.gif">{/if}<a href="{getUrl add="1" parent=$el.id action="list"}">{$el.name}</a></div>
 {if not empty($el.children)}
  {include file="common/Tries/generalTextElement.tpl" tree=$el.children depth=$depth+1}
 {/if}
{/foreach}