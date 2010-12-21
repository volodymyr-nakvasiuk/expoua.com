<h4>Список рекламных объявлений</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<table border="0" width="100%" class="list">
<tr>
  <th align="center">N</th>
  {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="id" descr="Id"}
  {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="active" descr="А"}
  <th align="center" style="width:100px;">Картинка</th>
  {include file="common/Lists/headerElementGeneral.tpl" align="left" name="name" descr="Название рекламного блока"}
  {include file="common/Lists/headerElementAutocomplete.tpl" name="users_id" controller="admin_ep_pblbanners_users" descr="Пользователь"}
  {include file="common/Lists/headerElementGeneral.tpl" align="left" name="price" descr="Сумма"}
  <th align="center">Дн.лимит</th>
  <th align="center">Период показа</th>
  {include file="common/Lists/headerElementAutocomplete.tpl" name="countries_id" controller="admin_ep_locations_countries" descr="Страна"}
  <th align="center">Язык</th>
  {include file="common/Lists/headerElementGeneral.tpl" align="center" name="date_update" descr="Время обновления"}
  <th align="center" width="20">Действия</th>
</tr>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="element" name="fe"}

<tr class="{if $element.active == -1}marked_red{elseif $element.shows==0}marked_blue{else}{cycle values="odd,even"}{/if}">
  <td align="center">
    {assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}
    {$npp}
  </td>

  <td align="center">{$element.id}</td>

  <td align="center">
    <form method="post" action="{getUrl add="1" action="update" id=$element.id}" style="padding:0px; margin:0px;">
      <input type="checkbox" {if $element.active==1} checked{/if} onclick="this.form.submit();">
      <input type="hidden" name="active" value="{if $element.active==1}-1{else}1{/if}" />
     </form>
  </td>

  <td align="center">
    <img src="http://62.149.12.130/bn/pbldata/{$element.file_name}?{1|mt_rand:10000}" alt="" style="max-width:100px; max-height:100px; width:100px;" /></td>

  <td align="left">{$element.name}</td>

  <td align="center"><a href="/{$selected_language}/admin_ep_pblbanners_users/edit/id/{$element.users_id}/">{$element.user_login}</a></td>

  <td align="center">{$element.price}</td>

  <td align="center">{$element.limit_daily|default:"&mdash;"}</td>

  <td align="center">{$element.date_from} – {$element.date_to}</td>

  <td align="left">{$element.country_name}</td>

  <td align="center">{$element.language_name}</td>

  <td align="center">{$element.date_update}</td>

  <td width="20" align="center">
    <form action="http://advertise.expopromoter.com{getUrl controller='sab_banners_auth' action='login'}" method="post" target="_blank">
<input type="hidden" name="login" value="{$element.user_login}" /><input type="hidden" name="passwd" value="{$element.user_password}" /><input type="image" alt="Зайти к пользователю отредактировать баннера" src="{$document_root}images/admin/icons/page_text.gif" border="0" width="16" /></form>
  </td>
</tr>
{/foreach}
</table>

