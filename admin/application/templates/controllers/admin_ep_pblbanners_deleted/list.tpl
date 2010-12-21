<h4>Список удаленных рекламных объявлений</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Нет удаленных рекламных объявлений</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}


<table border="0" width="100%" class="list">
<tr>
  <th align="center">N</th>
  {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="id" descr="Id"}
  {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="active" descr="А"}
  <th align="center" style="width:100px;">Картинка</th>
  {include file="common/Lists/headerElementGeneral.tpl" align="left" name="name" descr="Название рекламного блока"}
  {include file="common/Lists/headerElementGeneral.tpl" align="center" name="login" descr="Пользователь"}
  <th align="center">Сумма</th>
  <th align="center">Дн.лимит</th>
  <th align="center">Период показа</th>
  <th align="center">Язык</th>
</tr>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="element" name="fe"}

<tr class="{cycle values="odd,even"}">
  <td align="center">
    {assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}
    {$npp}
  </td>
  
  <td align="center">{$element.id}</td>
  
  <td align="center">
    <input type="checkbox" {if $element.active==1} checked{/if} disabled="disabled">
  </td>
  
  <td align="center">
    <img src="http://62.149.12.130/bn/pbldata/{$element.file_name}" alt="" style="max-width:100px; max-height:100px; width:100px;" /><br />

  <td align="left">{$element.name}</td>

  <td align="center"><a href="/{$selected_language}/admin_ep_pblbanners_users/edit/id/{$element.users_id}/">{$element.user_login}</a></td>

  <td align="center">{$element.price}</td>

  <td align="center">{$element.limit_daily|default:"&mdash;"}</td>

  <td align="center">{$element.date_from} – {$element.date_to}</td>

  <td align="center">{$element.language_name}</td>
</tr>
{/foreach}
</table>

