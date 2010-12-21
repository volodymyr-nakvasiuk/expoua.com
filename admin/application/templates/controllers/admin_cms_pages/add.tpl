{include file="common/contentVisualEdit.tpl" textarea="content" editor_type="advanced"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminTreeHelper.js"></SCRIPT>

<SCRIPT type="text/javascript" language="javascript">
objPagesTree = Shelby_Backend.TreeHelper.cloneObject('objPagesTree');
objPagesCopyTree = Shelby_Backend.TreeHelper.cloneObject('objPagesCopyTree');

objPagesTree.columns = new Array('active', 'name');
objPagesTree.returnFieldId = 'parent_id';
objPagesTree.feedUrl = '{getUrl controller="admin_cms_pages" action="tree"}';
objPagesTree.writeForm();

objPagesCopyTree.columns = new Array('active', 'name');
objPagesCopyTree.feedUrl = '{getUrl controller="admin_cms_pages" action="tree"}';
objPagesCopyTree.writeForm();

{literal}
objPagesCopyTree.callbackUser = function(entry) {
	var url = '{/literal}{getUrl controller="admin_cms_pages" action="view" feed="json"}{literal}' + 'id/' + entry.id + "/";

	$.getJSON(url, function(json) {

		$("#name").val(json.entry.name);

		$("select[@id='templates_id'] option[@value='" + json.entry.templates_id + "']").attr("selected", "selected");
		$("select[@id='objects_id'] option[@value='" + json.entry.objects_id + "']").attr("selected", "selected");

		$("#meta_title").val(json.entry.title);
		$("#meta_keywords").val(json.entry.keywords);
		$("#meta_description").val(json.entry.description);

		$("#content").val(json.entry.content);
		tinyMCE.updateContent('content');
	});
}
{/literal}

</SCRIPT>

<h4>Добавляем страницу</h4>

 <FORM method="POST" action="{getUrl add="1" action="insert"}">

  Копировать страницу: <INPUT type="button" value="Выбрать" onclick="objPagesCopyTree.showPopUp();" /><BR />

  Название: <INPUT type="text" name="name" id="name" size="50"><BR />

  Шаблон:
   <SELECT name="templates_id" id="templates_id">
    <OPTION value="">(не выбрано)</OPTION>
   {foreach from=$list_templates item="el"}
    <OPTION value="{$el.id}"{if $el.id==$HMixed->getConfigConstValue('CMS_DEFAULT_TEMPLATE_ID')} selected{/if}>{$el.name}</OPTION>
   {/foreach}
   </SELECT><BR />

   Категория: <INPUT type="text" size="4" name="parent_id" id="parent_id" value="{$user_params.parent}" /> <INPUT type="button" value="Выбрать" onclick="objPagesTree.showPopUp();" /> <SPAN id="parent_id_name">{include file="common/Trails/NoLinks.tpl" trail=$trail}</SPAN><BR />

  <p>
  {include file="common/generalMetaTags.tpl"}
  </p>

  Текст страницы:<br />
  <TEXTAREA style="width:100%; height:550px;" name="content" id="content"></TEXTAREA>

  <p>Скопировать запись во все языковые версии: <INPUT type="checkbox" name="_shelby_copy_all_langs" value="1" checked="checked"></p>

 <INPUT type="submit" value="Добавить">
 </FORM>

 <p><a href="{getUrl add="1" action="list"}" class="func_button"><img alt="Выше" src="{$document_root}images/admin/icons/page_left.gif" border="0" width="16"> Вернуться к списку</a>