<h4>Изображения галлереи</h4>

<a href="{getUrl controller="admin_cms_galleries" action="list"}" class="func_button"><img src="{$document_root}images/admin/icons/page_component.gif" border="0" width="16"> Список галлерей</a>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>

{else}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE width="100%" class="list">

 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="id" descr="Id"}
 {include file="common/Lists/headerElementCheckbox.tpl" width="30" name="active" descr="A"}
 {include file="common/Lists/headerElementGeneral.tpl" name="name" descr="Заголовок"}
  <TH align="center" width="100"><a href="{getUrl add="1" sort=$HMixed->getSortOrder('image_width', $list.sort_by)}">Размер</a></TH>
 <TH align="center">Пердпросмотр</TH>
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
 <TD>{$el.name}</TD>
 <TD align="center">{$el.image_width}x{$el.image_height}</TD>
 <TD align="center">
  <a href="{$files_base_path}{$el.parent_id}/{$el.filename}" target="_blank"><img src="{$files_base_path}{$el.parent_id}/tb/{$el.id}.jpg" border="0"></a>
 </TD>
 {include file="common/Actions/general.tpl"}
 </TR>
{/foreach}
</TABLE>

{/if}

<p>Добавляем новую запись</p>

<FORM action="{getUrl add="1" action="insert"}" method="POST" enctype="multipart/form-data">
<INPUT type="hidden" name="_shelby_copy_all_langs" value="1" />
 Название: <INPUT type="text" size="20" name="name"><br />
 Альтернативный текст: <INPUT type="text" size="20" name="alt"><br />
 Изображение: <INPUT type="file" size="20" name="upload"><br />
 <INPUT type="submit" value="Добавить">
</FORM>