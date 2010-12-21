<p></p>
<p></p>

<center>
{if $last_action_result == 1}
  {#msgRecordAdded#}
{else}
  {#msgRecordAddError#}: {$last_action_result}
{/if}
</center>
