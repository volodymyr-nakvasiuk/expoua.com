<FORM method="post" action="{getUrl add="1" action="update"}">

<h4>Редактируем баннероместо</h4>

<TABLE border="0" width="100%">
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="80" name="name" value="{$entry.name}"></TD>
 </TR>
 <TR>
  <TD>Код:</TD>
  <TD><INPUT type="text" size="16" name="code" value="{$entry.code}"></TD>
 </TR>
</TABLE>

<TABLE border="0" width="100%">
 <TR>
  <TD>Поддерживаемые типы баннеров:</TD>
 </TR>
 <TR>
  <TD>
  {foreach from=$list_types item="el"}
   <LABEL><INPUT type="checkbox" name="types_id[{$el.id}]" value="{$el.id}"{if isset($entry.selected_types[$el.id])} checked="checked"{/if} /> {$el.name}</LABEL><br/>
  {/foreach}
  </TD>
 </TR>
 <TR><TD align="center"><INPUT type="submit" value="Изменить"></TD></TR>
</TABLE>

</FORM>

<P><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></P>