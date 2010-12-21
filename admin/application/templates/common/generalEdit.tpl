{if is_array($entry)}
 <FORM method="POST" action="{getUrl add="1" action="update"}">
 {foreach from=$entry item="el" key="el_key"}
  {if $el_key!="id" && $el_key!="parent_id"}
   <div style="width:200px;">{$el_key}:</div> <INPUT type="text" size="60" name="{$el_key}" value="{$el}"><br />
  {/if}
 {/foreach}
 <INPUT type="submit" value="{#updateAction#}">
 </FORM>
{else}
 Запись не существует
 <p><a href="{getUrl add="1" action="list"}">{#linkBackToList#}</a>
{/if}