{if $last_action_result==1}
 Запись успешно обновлена
<SCRIPT type="text/javascript">
	setTimeout("redir()", 1000);
	function redir() {ldelim}
		document.location.href="{getUrl controller="sab_organizer_brandsevents" del="id" action="list"}";
	{rdelim}
</SCRIPT>
{elseif $last_action_result==0}
  Запись не обновлена
{else}
 При обновлении возникла ошибка, код: {$last_action_result}
{/if}

<p>
 <a href="{getUrl controller="sab_organizer_brandsevents" del="id" action="list"}">Вернуться к списку</a>
</p>
