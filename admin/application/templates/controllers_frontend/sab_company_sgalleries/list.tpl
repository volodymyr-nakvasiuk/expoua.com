<H4>Галлерея</H4>

<p><a href="{getUrl add="1" action="add"}">Добавить изображение</a></p>

{if empty($list.data)}
Записи отсутсвуют
{else}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

{foreach from=$list.data item="el"}
<DIV style="width:150px; float:left; border: 1px solid #999999; margin:5px; padding:5px;" align="center">
<a href="/data/images/companies/{$el.companies_id}/galleries/{$el.companies_services_id}/{$el.id}_big.jpg" target="_blank"><IMG src="/data/images/companies/{$el.companies_id}/galleries/{$el.companies_services_id}/{$el.id}.jpg" border="0"></a><BR />
<a href="{getUrl add="1" action="edit" id=$el.id}">Редактировать</a>
<a href="{getUrl add="1" action="delete" id=$el.id}" onclick="return Shelby_Backend.confirmDelete();">Удалить</a><BR />
  <FORM method="post" action="{getUrl add="1" action="update" id=$el.id}" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" {if $el.active==1} checked{/if} onclick="this.form.submit();">
  <INPUT type="hidden" name="common[active]" value="{if $el.active==1}0{else}1{/if}">
  <INPUT type="hidden" name="en[dummy]" value="1">
  </FORM>
</DIV>
{/foreach}

{/if}