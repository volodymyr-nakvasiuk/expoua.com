<h4>{if empty($title)}Файлы для загрузки{else}{$title}{/if}</h4>

{include file="common/Trails/Files.tpl" trail=$trail}<BR /><BR />

{if empty($list.data)}
<p>Записи отсутсвуют</p>

{else}

<TABLE class="list" width="100%">

 <TH width="30" align="center">T</TH>
 <TH align="center">Имя файла</TH>
 <TH width="130" align="center">Размер</TH>
 <TH width="120" align="center">Дата изменения</TH>
 <TH align="center" colspan="2">Действия</TH>

{if !empty($user_params.parent)}
 {assign var="parent" value="`$user_params.parent`:"}
{else}
 {assign var="parent" value=""}
{/if}

{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center"><IMG src="{$document_root}images/admin/icons/{if $el.type=='dir'}icon_folder.gif{else}icon_file.gif{/if}" /></TD>
  <TD>
   {if $el.type=='dir'}<a href="{getUrl add="1" parent="`$parent``$el.id`"}">{$el.name}</a>
   {else}<a href="{$files_base_path}{$parent|replace:":":"/"}{$el.name}" target="_blank">{$el.name}</a>{/if}
  </TD>
  <TD>{$el.size|number_format} байт</TD>
  <TD align="center">{$el.date|date_format:"%D %H:%M:%S"}</TD>
  {include file="common/Files/Actions.tpl" isFirst=$smarty.foreach.fe.first isLast=$smarty.foreach.fe.last}
 </TR>
{/foreach}

</TABLE>

<p></p>
{/if}

<TABLE class="list" width="100%">
<TR>
 <TH align="center">Создаем новый каталог</TH>
 <TH align="center">Загружаем файл</TH>
</TR>
<TR>
 <TD>
  <FORM method="post" action="{getUrl add="1" action="insert"}">
   <INPUT type="hidden" name="type" value="dir">
   Название директории: <INPUT type="text" name="name" size="20"><br />
   <p><CENTER><INPUT type="submit" value="Создать"></CENTER></p>
  </FORM>
 </TD><TD>
  <FORM method="post" action="{getUrl add="1" action="insert"}" enctype="multipart/form-data">
   <INPUT type="hidden" name="type" value="file">
   Файл: <INPUT type="file" name="upload" size="20"><br />
   <p><CENTER><INPUT type="submit" value="Загрузить"></CENTER></p>
  </FORM>
 </TD>
</TR>
</TABLE>