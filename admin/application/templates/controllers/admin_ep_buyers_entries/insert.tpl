{if $last_action_result == 1}
  {#msgRecordAdded#}
<script type="text/javascript">
setTimeout("redir()", 1000);
function redir() {ldelim}
	document.location.href="{getUrl add="1" del="id,type" action="list"}";
{rdelim}
</script>
{else}
  {#msgRecordAddError#}: {$last_action_result}
{/if}

<p>
 <a href="{getUrl add="1" del="id,type" action="list"}">{#linkBackToList#}</a>
</p>