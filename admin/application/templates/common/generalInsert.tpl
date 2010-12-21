{if !$action}{assign var="action" value="list"}{/if}
{if $last_action_result == 1}
  {#msgRecordAdded#}
  {include file="common/helperRedirect.tpl" id=$id action=$action}
{else}
  {#msgRecordAddError#}: {$last_action_result}
{/if}

<p>
 <a href="{getUrl add="1" del="id" action="list"}">{#linkBackToList#}</a>
</p>