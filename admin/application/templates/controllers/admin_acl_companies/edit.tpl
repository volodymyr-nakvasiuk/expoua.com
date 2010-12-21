<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>
objCompaniesList = Shelby_Backend.ListHelper.cloneObject('objCompaniesList');

objCompaniesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objCompaniesList.returnFieldId = 'companies_id';
objCompaniesList.feedUrl = '{getUrl controller="admin_ep_companies_manage" action="list" feed="json"}';
objCompaniesList.writeForm();
</SCRIPT>

<style>
{literal}
  ul {
    margin: 0;
    padding: 0;
    
    list-style: square;
  }
  
  
  ul li {
    margin: 0 0 2px 1.1em;
    padding: 0;
  }
  
  
  #photo {
    float: left; 
    
    margin: 0; 
    padding: 0; 
    
    width: 180px; 
    
    text-align: left; 
  }
  
  
  .mtable {
    width: 100%;
    
    font-size: 90%;
    
    border-top: 1px solid #E4EBEC;
  }
  
  .mtable td, .mtable th {
    padding: 4px 0;
    
    border-bottom: 1px solid #E4EBEC;
  }
  
  
  .mtable th {
    width: 25%;
    text-align: left;
    color: #999;
  }
{/literal}
</style>


<h4>Редактирование пользователя компании</h4>
  {if $entry.photo_exists}
  <img src="http://admin.expopromoter.com/data/images/user_sites/{$entry.id}.jpg?salt={$smarty.now}" alt="" style="float:left; margin:5px 0 10px 10px;" />
  {/if}

  <div class="form-container"{if $entry.photo_exists} style="margin:0 0 0 200px"{/if}>
    <form method="post" class="cmxform" action="{getUrl add="1" action="update"}">
      <input type="hidden" id="company_id" name="company_id" value="{$entry.companyId}" />
    
      <fieldset>
        <legend>Авторизация</legend>
        
        <ol>
          <li class="even">
            <label for="_login">Логин</label>
            <input type="text" name="login" id="_login" value="{$entry.login}" class="w30" readonly="readonly" />
          </li>
        
          <li class="odd">
            <label for="_passwd">Пароль</label>
            <input type="text" name="passwd" id="_passwd" value="{$entry.passwd}" class="w30" />
          </li>
{*        
          <li class="even">
            <fieldset>
              <legend>Язык пользователя</legend>

              {foreach from=$list_languages item=el}
                <label><input type="radio" name="userLangId" value="{$el.id}"{if $el.code==$user_params.lang} checked="checked"{/if} /> {$el.name}</label>
              {/foreach}
            </fieldset>
          </li>
*}
        </ol>
      </fieldset>


      <fieldset>
        <legend>Персональная информация</legend>

        <ol>
          <li class="even">
             <label for="lname">Фамилия</label>
             <input type="text" id="lname" name="name" value="{$entry.name}" />
          </li>
    
          <li class="odd">
             <label for="fname">Имя/отчество</label>
             <input type="text" id="fname" name="fname" value="{$entry.fname}" />
          </li>
    
          <li class="even">
             <label for="email">Персональный email</label>
             <input type="text" id="email" name="email" value="{$entry.email}" />
          </li>
        </ol>
      </fieldset>


      <fieldset>
        <legend>Информация о компании</legend>

        <ol>
          <li class="odd">
            <label for="companies_id">Компания</label>
            <input type="text" size="5" name="companies_id" id="companies_id" value="{$entry.companies_id}" class="w10" />
            <button type="button" onclick="objCompaniesList.showPopUp();"> Выбрать </button>
            <span id="companies_id_name">{$entry.company_name}</span>
          </li>
        </ol>
      </fieldset>
      
      <fieldset>
        <legend>Данные посетитетя</legend>
        
        <ol>
{*
          <li class="even">
             <label for="photo">Фото</label>
             <input type="file" id="photo" name="photo" />
          </li>
*}    
          <li class="even">
             <label for="photo_exists">Фото загружено</label>
             <select name="photo_exists" id="photo_exists" class="w10">
               <option value="1"{if $entry.photo_exists} selected{/if}>Да</option>
               <option value="0"{if !$entry.photo_exists} selected{/if}>Нет</option>
             </select>
          </li>
    
          <li class="odd">
            <label for="position">Должность</label>
            <input type="text" id="position" name="text_dolgnost" value="{$entry.text_dolgnost}" />
          </li>
          
          <li class="even">
            <label for="position2">Статус</label>

            <select name="status" id="position2">
              <option value="">- нет выбора -</option>
              <option value="1"{if $entry.status == 1} selected="selected"{/if}>руководитель/владелец предприятия/организации</option>
              <option value="2"{if $entry.status == 2} selected="selected"{/if}>менеджер высшего звена/член совета директоров</option>
              <option value="3"{if $entry.status == 3} selected="selected"{/if}>руководитель подразделения/менеджер проекта</option>
              <option value="4"{if $entry.status == 4} selected="selected"{/if}>техн. специалист</option>
              <option value="5"{if $entry.status == 5} selected="selected"{/if}>финанс. специалист</option>
              <option value="6"{if $entry.status == 6} selected="selected"{/if}>специалист по маркетингу/рекламе</option>
              <option value="7"{if $entry.status == 7} selected="selected"{/if}>специалист по продаже/сбыту</option>
              <option value="8"{if $entry.status == 8} selected="selected"{/if}>специалист по логистике</option>
              <option value="9"{if $entry.status == 9} selected="selected"{/if}>специалист по закупкам</option>
              <option value="10"{if $entry.status == 10} selected="selected"{/if}>студент</option>
              <option value="11"{if $entry.status == 11} selected="selected"{/if}>прочее</option>
              <option value="12"{if $entry.status == 12} selected="selected"{/if}>специалист</option>
            </select>            
          </li>


          <li class="odd">
              <fieldset>
                <legend>Выполняемые функции</legend>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="1"{if in_array(1, $entry.functions)} checked="checked"{/if} /> менеджмент / руководство</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="2"{if in_array(2, $entry.functions)} checked="checked"{/if} /> проектирование / разработки</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="3"{if in_array(3, $entry.functions)} checked="checked"{/if} /> информационные системы</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="4"{if in_array(4, $entry.functions)} checked="checked"{/if} /> образование</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="5"{if in_array(5, $entry.functions)} checked="checked"{/if} /> реклама / маркетинг</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="6"{if in_array(6, $entry.functions)} checked="checked"{/if} /> финансы / бухучет</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="7"{if in_array(7, $entry.functions)} checked="checked"{/if} /> сбыт / продажи</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="8"{if in_array(8, $entry.functions)} checked="checked"{/if} /> делопроизводство / кадры</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="9"{if in_array(9, $entry.functions)} checked="checked"{/if} /> производство / технологический процесс</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="10"{if in_array(10, $entry.functions)} checked="checked"{/if} /> техническое обслуживание</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="11"{if in_array(11, $entry.functions)} checked="checked"{/if} /> логистика</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="12"{if in_array(12, $entry.functions)} checked="checked"{/if} /> закупки / снабжение</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="13"{if in_array(13, $entry.functions)} checked="checked"{/if} /> прочее</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="14"{if in_array(14, $entry.functions)} checked="checked"{/if} /> автоматизация производства</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="15"{if in_array(15, $entry.functions)} checked="checked"{/if} /> телекоммуникации / связь</label>

                <label class="column50">
                  <input type="checkbox" name="functions[]" value="16"{if in_array(16, $entry.functions)} checked="checked"{/if} /> безопасность</label>
              </fieldset>
          </li>
        </ol>
      </fieldset>
      
      <div class="buttons"><button type="submit"><span>Сохранить</span></button></div>

    </form>

  </div>

{*
<FORM method="post" action="{getUrl add="1" action="update"}">

<TABLE border="1" width="100%" style="border-collapse:collapse;">
<TR>
  <TD>Компания:</TD>
  <TD><INPUT type="text" size="5" name="companies_id" id="companies_id" value="{$entry.companies_id}"> <INPUT type="button" onclick="objCompaniesList.showPopUp();" value="Выбрать"> <SPAN id="companies_id_name">{$entry.company_name}</SPAN></TD>
 </TR>

<TR>
  <TD>Логин:</TD>
  <TD><INPUT type="text" size="20" name="login" value="{$entry.login}"></TD>
</TR>

<TR>
  <TD>Пароль:</TD>
  <TD><INPUT type="text" size="20" name="passwd" value="{$entry.passwd}"></TD>
</TR>

<TR>
  <TD>Имя:</TD>
  <TD><INPUT type="text" size="40" name="name" value="{$entry.name}"></TD>
</TR>

<TR>
  <TD>Email:</TD>
  <TD><INPUT type="text" size="20" name="email" value="{$entry.email}"></TD>
</TR>

<TR><TD align="center" colspan="2"><INPUT type="submit" value="Сохранить"></TD></TR>
</TABLE>

</FORM>
*}

<p><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></p>