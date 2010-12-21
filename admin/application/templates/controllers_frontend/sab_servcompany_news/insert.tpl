{if $last_action_result==1}
 Запись успешно добавлена
 
{elseif $last_action_result==0}
  Запись не добавлена
{else}
 При обновлении возникла ошибка, код: {$last_action_result}
{/if}
