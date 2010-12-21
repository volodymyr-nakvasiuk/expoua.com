{if !$action}{assign var="action" value="list"}{/if}
{if $last_action_result == 1}
  {#msgRequestRecorded#}
<script type="text/javascript">
setTimeout("redir()", 1000);
function redir() {ldelim}
    document.location.href="{getUrl add="1" del="reason,registrationId" action='list'}";
{rdelim}
</script>  
{else}
  {#msgRequestFailed#} {$last_action_result}
{/if}

<p>
 <a href="{getUrl add="1" del="reason,registrationId" action='list'}">{#backToList#}</a>
</p>