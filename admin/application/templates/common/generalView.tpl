<h4>{#hdrViewRecord#}</h4>

{if is_array($entry)}
 {foreach from=$entry item="el" key="el_key"}
   {$el_key}: {$el}<br />
 {/foreach}
{else}
 {#msgRecordDoesntExist#}
{/if}

 <p><a href="{getUrl add="1" action="list"}">{#linkBackToList#}</a>
