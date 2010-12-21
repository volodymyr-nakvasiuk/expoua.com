{include file="common/contentVisualEdit.tpl" textarea="content" editor_type="advanced"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminTreeHelper.js"></SCRIPT>

<SCRIPT type="text/javascript" language="javascript">
objPagesTree = Shelby_Backend.TreeHelper.cloneObject('objPagesTree');

objPagesTree.columns = new Array('active', 'name');
objPagesTree.returnFieldId = 'parent_id';
objPagesTree.feedUrl = '{getUrl controller="admin_cms_pages" action="tree"}';
objPagesTree.writeForm();
</SCRIPT>

<h4>Редактируем страницу</h4>

{if is_array($entry)}
 <FORM method="POST" action="{getUrl add="1" action="update"}">
  Название: <INPUT type="text" name="name" value="{$entry.name}" size="50" /><BR />

  Шаблон:
   <SELECT name="templates_id">
    <OPTION value="">(не выбрано)</OPTION>
   {foreach from=$list_templates item="el"}
    <OPTION value="{$el.id}"{if $el.id==$entry.templates_id} selected{/if}>{$el.name}</OPTION>
   {/foreach}
   </SELECT><BR />

   Категория: <INPUT type="text" size="4" name="parent_id" id="parent_id" value="{$entry.parent_id}" /> <INPUT type="button" value="Выбрать" onclick="objPagesTree.showPopUp();" /> <SPAN id="parent_id_name">{include file="common/Trails/NoLinks.tpl" trail=$trail}</SPAN><BR />

   {include file="common/generalMetaTags.tpl"}

  Текст страницы:<br />
  <TEXTAREA style="width:100%; height:550px;" name="content">{$entry.content|escape}</TEXTAREA>

 <INPUT type="submit" value="Обновить">
 </FORM>
{else}
 Запись не существует
{/if}

 <p><a href="{getUrl add="1" action="list"}">Вернуться к списку</a>