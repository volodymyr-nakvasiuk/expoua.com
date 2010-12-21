{if $last_action_result==1}
 Запись успешно добавлена
<SCRIPT type="text/javascript">
	setTimeout("redir()", 1000);
	function redir() {ldelim}
		document.location.href="{getUrl add="1" del="id" action="list"}";
	{rdelim}
</SCRIPT>
{elseif $last_action_result==0}
  Запись не добавлена
{else}
 При добавлении возникла ошибка, код: {$last_action_result}
{/if}

<p>
 <a href="{getUrl add="1" del="id" action="list"}">Вернуться к списку</a>
</p>
