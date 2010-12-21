{if $last_action_result==1}
 Запись успешно обновлена
 
{elseif $last_action_result==0}
  Запись не обновлена
{else}
 При обновлении возникла ошибка, код: {$last_action_result}
{/if}

