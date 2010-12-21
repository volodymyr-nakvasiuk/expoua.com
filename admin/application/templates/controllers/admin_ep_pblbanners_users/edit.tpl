<script type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></script>

<script>
  objCountriesList = Shelby_Backend.ListHelper.cloneObject('objCountriesList');

  objCountriesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
  objCountriesList.returnFieldId = 'countries_id';
  objCountriesList.feedUrl = '{getUrl controller="admin_ep_locations_countries" action="list" feed="json"}';
  objCountriesList.writeForm();
</script>

<h4>Редактирование записи рекламодателя</h4>

{if is_array($entry)}

<form method="post" action="{getUrl add='1' action='update'}">
  <input type="hidden" name="id" value="{$entry.id}" />
  
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
      <input type="text" name="deposit" id="deposit" value="{$entry.deposit}" style="width:80%" readonly="readlonly" />
    </td>
    <td align="left">&nbsp;&nbsp;&lt;&lt; Пополнение</td>
    <td align="left"><input type="text" name="add" id="add" value="0.00" style="width:80%" /></td>
  </tr>
  
  <tr valign="top">
    <td align="left">Дисконт</td>
    <td align="left"><input type="text" name="discount" value="{$entry.discount}" style="width:80%" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  </table>
  
  <input type="submit" value=" {#updateAction#} " />
</form>

<hr />

<form action="http://advertise.expopromoter.com{getUrl controller='sab_banners_auth' action='login'}" method="post" target="_blank">
<input type="hidden" name="login" value="{$entry.login}" /><input type="hidden" name="passwd" value="{$entry.passwd}" /><input type="submit" value=" Вход " />
</form>

{else}

<p>Запись не существует</p>
<p><a href="{getUrl add="1" action="list"}">{#linkBackToList#}</a></p>

{/if}