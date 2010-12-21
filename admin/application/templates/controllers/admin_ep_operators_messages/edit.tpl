<h4>Ответ на вопрос организатора / оператора</h4>

<p><a href="{getUrl add="1" action="list" del="parent,id"}">Вернуться к списку</a></p>

<p><strong>Текст вопроса:</strong></p>

<p style="margin:0 0 20px 0; padding:10px; background:#DEDEDE;">{$entry.message}</p>

<p><strong>Наш ответ:</strong></p>

<form method="post" action="{getUrl add="1" action="update"}">
<textarea name="answer" style="width:100%; height:100px;">{$entry.answer}</textarea><br/>
<input type="submit" value=" Отправить "/>
</form>

<p>Последние сообщения переписки:</p>

{foreach from=$list.data item="el"}
  <div class="msg_question">
    <div class="msg_tagline">{$el.date_posted}</div>

    <div class="msg_header">Вопрос:</div>

    <p>{$el.message|nl2br}</p>
  </div>
  {if $el.answer}
    <div class="msg_answer">
      <div class="msg_header">Ответ:</div>
      <p>{$el.answer|nl2br}</p>
    </div>
  {/if}
{foreachelse}
  <p style="text-align:center;">Сообщения отсутвуют</p>
{/foreach}

<p><a href="{getUrl add="1" action="list" del="parent,id"}">Вернуться к списку</a></p>
