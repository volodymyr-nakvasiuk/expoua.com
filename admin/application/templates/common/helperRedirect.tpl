{if !$action}{assign var="action" value="list"}{/if}
<script type="text/javascript">
setTimeout("redir()", 1000);
function redir() {ldelim}
	document.location.href="{getUrl add="1" del="direction" id=$id action=$action}";
{rdelim}
</script>