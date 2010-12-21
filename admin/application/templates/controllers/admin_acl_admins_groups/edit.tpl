<h4>Редактирование группы доступа</h4>

<FORM action="{getUrl add="1" action="update"}" method="POST">
 Название: <INPUT type="text" name="name" value="{$entry.name}"><br />
 Родительская группа:
  <SELECT name="parent_group_id">
   <OPTION value="">(не установлена)</OPTION>
   {foreach from=$list_groups.data item="el"}
    {if $el.id!=$entry.id}
     <OPTION value="{$el.id}"{if $el.id==$entry.parent_group_id} selected{/if}>{$el.name}</OPTION>
    {/if}
   {/foreach}
  </SELECT><BR />
 Описание: <BR /><TEXTAREA name="description" cols="30" rows="2">{$entry.description}</TEXTAREA><br />

 <p>
 <b>Управление доступом к модулям</b><br />
 <TABLE border="1" style="border-collapse:collapse;">
  <TR>
   <TH align="center">Модули</TH>
   <TH align="center" width="300">Запретить действия</TH>
  </TR>
 {foreach from=$list_resources item="res_el"}
  <TR>
   <TD valign="top">
    <INPUT type="checkbox" value="{$res_el.id}" name="resources_id[{$res_el.id}]"{if $res_el.allowed==true} checked{/if}> {$res_el.code}<BR />
    {$res_el.description}
   </TD>
   <TD valign="top">
    <CENTER style="cursor:pointer;" onclick="document.getElementById('g_actions{$res_el.id}').style.display='block';"><b>Список</b></CENTER>
    <DIV id='g_actions{$res_el.id}' style="display:none;">
    {foreach from=$res_el.actions item="act_el"}
     <INPUT type="checkbox" name="actions_id[{$res_el.id}][{$act_el.id}]" value="{$act_el.id}" {if !is_null($act_el.restricted_action)} checked{/if}>{$act_el.description}<BR />
    {foreachelse}
    Нет действий
    {/foreach}
    </DIV>
   </TD>
 {/foreach}

 </TABLE>
 </p>

  <INPUT type="submit" value="Изменить">

</FORM>