<h4>Список страниц</h4>

{include file="common/Trails/General.tpl" trail=$trail}

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>

{else}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<p><a href="{getUrl add="1" action="tree"}" class="func_button"><img alt="Выше" src="{$document_root}images/admin/icons/page_tree.gif" border="0" width="16"> В виде дерева</a></p>

<TABLE border="0" class="list" width="100%">

 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="id" descr="Id"}
 {include file="common/Lists/headerElementCheckbox.tpl" width="30" name="active" descr="A"}
 {include file="common/Lists/headerElementGeneral.tpl" name="name" descr="Название"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="templates_id" controller="admin_cms_templates_pages" descr="Шаблон"}
 <TH align="center" colspan="2">Действия</TH>

{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
 <TD align="center">{$el.id}</TD>
 <TD align="center">
  <FORM method="post" action="{getUrl add="1" action="update" id=$el.id}" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" {if $el.active==1} checked{/if} onclick="this.form.submit();">
  <INPUT type="hidden" name="active" value="{if $el.active==1}0{else}1{/if}">
  </FORM>
 </TD>
 <TD><a href="{getUrl add="1" parent=$el.id}">{$el.name}</a></TD>
 <TD align="center">{if empty($el.template_name)}(не установлен){else}{$el.template_name}{/if}</TD>
 {include file="common/Actions/general.tpl" isFirst=$smarty.foreach.fe.first isLast=$smarty.foreach.fe.last}
 </TR>
{/foreach}
</TABLE>

{/if}

<p>
<a href="{getUrl add="1" action="add"}" class="func_button"><img alt="Выше" src="{$document_root}images/admin/icons/page_new.gif" border="0" width="16"> Добавляем новую запись</a>
</p>