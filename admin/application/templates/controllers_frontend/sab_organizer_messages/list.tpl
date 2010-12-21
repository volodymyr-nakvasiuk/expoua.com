<form method="post" action="{getUrl add="1" action="insert"}">
  <p>{#msgAskQuestion#}</p>

  <textarea name="message" style="width:100%; height:100px;"></textarea>

  <p><input type="submit" value=" {#sendAction#} "/></p>
</form>

{foreach from=$list.data item="el"}
  <div class="msg_question">
    <div class="msg_tagline">{$el.date_posted}</div>

    <div class="msg_header">{#captionQuestion#}:</div>

    <p>{$el.message|escape|nl2br}</p>
  </div>
  {if $el.answer}
    <div class="msg_answer">
      <div class="msg_header">{#captionAnswer#}:</div>
      <p>{$el.answer|escape|nl2br}</p>
    </div>
  {/if}

{foreachelse}
  <p style="text-align:center;">{#msgNoMessages#}</p>
{/foreach}
