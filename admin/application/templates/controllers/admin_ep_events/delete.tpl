{if $last_action_result == 1}
 Запись успешно удалена
<SCRIPT type="text/javascript">
	setTimeout("redir()", 1000);
	function redir() {ldelim}
		document.location.href="{getUrl add="1" controller="admin_ep_brandsevents" del="id" action="list"}";
	{rdelim}
</SCRIPT>
{else}
 При удалении возникла ошибка, код: {$last_action_result}
{/if}

<p>
 <a href="{getUrl add="1" controller="admin_ep_brandsevents" del="id" action="list"}">Вернуться к списку</a>
</p>
