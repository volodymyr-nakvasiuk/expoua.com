<script type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></script>

<script>
  objCountriesList = Shelby_Backend.ListHelper.cloneObject('objCountriesList');

  objCountriesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
  objCountriesList.returnFieldId = 'countries_id';
  objCountriesList.feedUrl = '{getUrl controller="admin_ep_locations_countries" action="list" feed="json"}';
  objCountriesList.writeForm();
</script>


<h4>Список рекламодателей</h4>

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
  {include file="common/Lists/headerElementGeneral.tpl" align="center" name="login" descr="Пользователь"}
  {include file="common/Lists/headerElementGeneral.tpl" align="left" name="name" descr="Имя"}
  {include file="common/Lists/headerElementGeneral.tpl" align="left" name="company" descr="Компания"}
  <th align="center">Депозит</th>
  <th align="center">Дисконт</th>
  {include file="common/Lists/headerElementGeneral.tpl" align="center" name="date_lastlogin" descr="Последний вход"}
  <th align="center" colspan="3" width="16">Действия</th>
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
    <form method="post" action="{getUrl add="1" action="update" id=$element.id}" style="padding:0px; margin:0px;">
      <input type="checkbox" {if $element.active==1} checked{/if} onclick="this.form.submit();">
      <input type="hidden" name="active" value="{if $element.active==1}0{else}1{/if}">
     </form>
  </td>
  
  <td align="center">{$element.login}</td>

  <td align="left">{$element.name}</td>

  <td align="left">{$element.company}</td>

  <td align="center">
    {if $element.deposit <= 0}<span style="color:#900">{$element.deposit}</span>{else}{$element.deposit}{/if}
  </td>

  <td align="center">{$element.discount}</td>

  <td align="center">{$element.date_lastlogin}</td>

  {include file="common/Actions/general.tpl" el=$element}
</tr>
{/foreach}
</table>

<hr />

<h4>Добавить нового рекламодателя</h4> 

<form method="post" action="{getUrl add='1' action='insert'}">
  
  <table width="50%">
  <tr valign="top">
    <td align="left" width="25%">Логин</td>
    <td align="left" width="25%"><input type="text" name="login" value="{$entry.login}" style="width:100%" /></td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
  
  <tr valign="top">
    <td align="left">Пароль</td>
    <td align="left"><input type="text" name="passwd" value="{$entry.passwd}" style="width:100%" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr valign="top">
    <td align="left">Имя</td>
    <td align="left" colspan="3"><input type="text" name="name" value="{$entry.name}" style="width:100%" /></td>
  </tr>
  
  <tr valign="top">
    <td align="left">Компания</td>
    <td align="left" colspan="3"><input type="text" name="company" value="{$entry.company}" style="width:100%" /></td>
  </tr>
  
  <tr valign="top">
    <td>Страна:</td>
    <td colspan="3">
      <input type="text" size="5" name="countries_id" id="countries_id" value="{$entry.countries_id}" /> <input type="button" onclick="objCountriesList.showPopUp();" value="Выбрать"> <span id="countries_id_name">{$entry.country_name}</span>
    </td>
  </tr>

  <tr valign="top">
    <td align="left">Телефон</td>
    <td align="left" colspan="3"><input type="text" name="phone" value="{$entry.phone}" style="width:100%" /></td>
  </tr>
  
  <tr valign="top">
    <td align="left">E-mail</td>
    <td align="left" colspan="3"><input type="text" name="email" value="{$entry.email}" style="width:100%" /></td>
  </tr>
  
  <tr valign="top">
    <td align="left">Веб-сайт</td>
    <td align="left" colspan="3"><input type="text" name="url" value="{$entry.url}" style="width:100%" /></td>
  </tr>
  
  <tr valign="top">
    <td align="left">Депозит</td>
    <td align="left">
      <input type="text" name="deposit" id="deposit" value="{$entry.deposit}" style="width:80%" />
    </td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  
  <tr valign="top">
    <td align="left">Дисконт</td>
    <td align="left"><input type="text" name="discount" value="{$entry.discount}" style="width:80%" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  </table>
  
  <input type="submit" value=" {#addAction#} " />
</form>

