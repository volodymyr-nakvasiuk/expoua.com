{if $last_action_result==1}
<h4 align="center">Запрос успешно отправлен по указанному адресу</h4>
<SCRIPT type="text/javascript">
	setTimeout("redir()", 5000);
	function redir() {ldelim}
		document.location.href="{getUrl add=1 action="view"}";
	{rdelim}
</SCRIPT>
{else}
<h4 align="center">Вы указали неверный электронный адрес</h4>
{/if}

<p>
 <a href="{getUrl add=1 action="view"}">Вернуться к просмотру запроса</a>
</p>
