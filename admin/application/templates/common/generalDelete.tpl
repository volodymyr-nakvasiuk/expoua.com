{if $last_action_result == 1}
 {#msgRecordDeleted#}
 {include file="common/helperRedirect.tpl"}
{else}
 {#msgRecordDeleteError#}: {$last_action_result}
{/if}

<p>
 <a href="{getUrl add="1" del="id" action="list"}">{#linkBackToList#}</a>
</p>