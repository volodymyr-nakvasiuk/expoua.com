<h4>Галлереи</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>

{else}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE class="list" width="100%">

 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="id" descr="Id"}
 {include file="common/Lists/headerElementCheckbox.tpl" width="30" name="active" descr="A"}
 {include file="common/Lists/headerElementCheckbox.tpl" width="30" name="thumbnail_create" descr="TB"}
 {include file="common/Lists/headerElementGeneral.tpl" name="name" descr="Заголовок"}
 <TH align="center" width="100"><a href="{getUrl add="1" sort=$HMixed->getSortOrder('thumbnail_height', $list.sort_by)}">Уменьшенные</a></TH>
<TH align="center" colspan="3">Действия</TH>

{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
 <TD align="center">{$el.id}</TD>
 <TD align="center">
  <FORM method="post" action="{getUrl add="1" action="update" id=$el.id}" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" {if $el.active==1} checked{/if} onclick="this.form.submit();">
  <INPUT type="hidden" name="active" value="{if $el.active==1}0{else}1{/if}">
  </FORM>
 </TD>
 <TD align="center">
  <FORM method="post" action="{getUrl add="1" action="update" id=$el.id}" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" {if $el.thumbnail_create==1} checked{/if} onclick="this.form.submit();">
  <INPUT type="hidden" name="thumbnail_create" value="{if $el.thumbnail_create==1}0{else}1{/if}">
  </FORM>
 </TD>
 <TD><a href="{getUrl controller="admin_cms_galleries_elements" action="list" parent=$el.id}">{$el.name}</a></TD>
 <TD align="center">{$el.thumbnail_width}x{$el.thumbnail_height}</TD>
 {include file="common/Actions/general.tpl" isFirst=$smarty.foreach.fe.first isLast=$smarty.foreach.fe.last}
 </TR>
{/foreach}
</TABLE>

{/if}

<p>Добавляем новую запись</p>

<FORM action="{getUrl add="1" action="insert"}" method="POST">
<INPUT type="hidden" name="_shelby_copy_all_langs" value="1" />
 Название: <INPUT type="text" size="50" name="name"><br />
 Создавать уменьшенные копии: <INPUT type="checkbox" name="thumbnail_create" value="1" checked="checked"><br />
 Размер уменьшенных копий: <INPUT type="text" size="3" name="thumbnail_width" value="{$HMixed->getConfigConstValue("GALLERY_DEFAUTL_TB_WIDTH")}">x<INPUT type="text" size="3" name="thumbnail_height" value="{$HMixed->getConfigConstValue("GALLERY_DEFAUTL_TB_HEIGHT")}"><br />
 <INPUT type="submit" value="Добавить">
</FORM>

<SMALL>* TB - создавать уменьшенные копии</SMALL>