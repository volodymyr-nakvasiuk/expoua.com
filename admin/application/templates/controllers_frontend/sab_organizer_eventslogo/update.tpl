{if $last_action_result==1}
 Запись успешно обновлена
<SCRIPT type="text/javascript">
setTimeout("redir()", 1000);
function redir() {ldelim}
	document.location.href="{getUrl add="1" action="edit"}";
{rdelim}
</SCRIPT>
{elseif $last_action_result==0}
  Запись не обновлена
{else}
 При обновлении возникла ошибка, код: {$last_action_result}
{/if}

<p>
 <a href="{getUrl add="1" action="edit"}">Вернуться к записи</a>
</p>