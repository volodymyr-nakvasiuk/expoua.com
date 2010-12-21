{if $last_action_result==1}
 {#msgRecordUpdated#}
 
{elseif $last_action_result==0}
  {#msgRecordNotUpdated#}
{else}
 {#msgRecordUpdateError#}: {$last_action_result}
{/if}

