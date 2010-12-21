<h4>Редактируем модуль</h4>

<FORM action="{getUrl add="1" action="update"}" method="POST">

<TABLE border="1" style="border-collapse:collapse;">

<TR><TD valign="top">

 Установлен: <INPUT type="checkbox" name="installed" value="1"{if $entry.installed==1} checked{/if}><br />
 Админ: <INPUT type="checkbox" name="super" value="1"{if $entry.super==1} checked{/if}><br />
 Код: <INPUT type="text" size="20" name="code" value="{$entry.code}"><br />
 Описание: <BR /><TEXTAREA name="description" cols="50" rows="2">{$entry.description}</TEXTAREA><br />
 <INPUT type="submit" value="Изменить">

</TD><TD style="padding-left:10px;">

{foreach from=$list_actions item="el"}
 <INPUT type="checkbox" name="actions[{$el.id}]" value="1"{if not empty($entry.actions[$el.id])} checked{/if}> <LABEL>{$el.code}</LABEL><BR />
 {$el.description}
 <div style="border-top:1px solid #CCCCCC;"></DIV>
{/foreach}

</TD></TR>
</TABLE>

</FORM>