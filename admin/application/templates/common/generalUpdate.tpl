{if !$action}{assign var="action" value="list"}{/if}
{if $last_action_result==1}
  {#msgRecordUpdated#}
  {include file="common/helperRedirect.tpl" id=$id action=$action}
{elseif $last_action_result==0}
  {#msgRecordNotUpdated#}
{else}
  {#msgRecordUpdateError#}: {$last_action_result}
{/if}

<p>
  <a href="{getUrl add="1" del="id" action=$action}">{#linkBackToList#}</a>
</p>