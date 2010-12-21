{* <div style="height:350px;"></div> *}

<fieldset>
  <table cellspacing="2" width="100%">
  <tr>
    <td style="width:35%; height:340px;">Бренд:</td>
    <td style="width:65%;">
      <input type="text" size="5" value="{$entry_event[$selected_language].brands_id}" readonly />
      {$entry_event[$selected_language].brand_name}<br />
      {$entry_event[$selected_language].brand_name_extended}
    </td>
  </tr>
  
  <tr>
    <td style="border-top:1px solid #333">Город:</td>
    <td style="border-top:1px solid #333">
      <input type="text" size="5" value="{$entry_event[$selected_language].cities_id}" readonly />
      {$entry_event[$selected_language].city_name}
    </td>
  </tr>
  
  <tr>
    <td>Выставочный центр:</td>
    <td><input type="text" size="5" value="{$entry_event[$selected_language].expocenters_id}" readonly /> {$entry_event[$selected_language].expocenter_name}</td>
  </tr>
  
  <tr>
    <td>Даты проведения:</td>
    <td>с <input type="text" size="12" value="{$entry_event[$selected_language].date_from}" readonly /> по <input type="text" size="12" value="{$entry_event[$selected_language].date_to}" readonly /></td>
  </tr>
  
  <tr>
    <td>Период проведения:</td>
    <td>
      <select disabled>
        <option value="">(Не выбрано)</option>
        {foreach from=$list_periods item="el"}
          <option {if $el.id==$entry_event[$selected_language].periods_id}selected{/if}>{$el.name}</option>
        {/foreach}
      </select>
    </td>
  </tr>
  
 <tr>
  <td>Вход на выставку:</td>
  <td>
   <SELECT disabled="disabled">
   {if is_null($entry_event[$selected_language].is_free)}
    <OPTION>(Не выбрано)</OPTION>
   {elseif $entry_event[$selected_language].is_free == 1}
    <OPTION>Бесплатный</OPTION>
   {elseif $entry_event[$selected_language].is_free == 0}
    <OPTION>Платный</OPTION>
   {/if}
   </SELECT>
  </td>
 </tr>
 <tr>
  <td>Стоимость входа:</td>
  <td><input type="text" value="{$entry_event[$selected_language].ticket_fee}" readonly="readonly"/></td>
 </tr>

  <tr>
    <td colspan="2" valign="top">
     <div>Дополнительная информация о событии:</div>
  
     <table style="border-collapse:collapse;">
     <tr>
       <td>Участников всего:</td>
       <td><input type="text" value="{$entry_event[$selected_language].partic_num}" size="10" readonly /></td>
     </tr>
  
     <tr>
       <td>Местных участников:</td>
       <td><input type="text" value="{$entry_event[$selected_language].local_partic_num}" size="10" readonly /></td>
     </tr>
  
     <tr>
       <td>Иностранных участников:</td>
       <td><input type="text" value="{$entry_event[$selected_language].foreign_partic_num}" size="10" readonly /></td>
     </tr>
  
     <tr>
       <td>Общая площадь:</td>
       <td><input type="text" value="{$entry_event[$selected_language].s_event_total}" size="10" readonly /></td>
     </tr>
  
     <tr>
       <td>Посетителей всего:</td>
       <td><input type="text" value="{$entry_event[$selected_language].visitors_num}" size="10" readonly /></td>
     </tr>
  
     <tr>
       <td>Местных посетителей:</td>
       <td><input type="text" value="{$entry_event[$selected_language].local_visitors_num}" size="10" readonly /></td>
     </tr>
  
     <tr>
       <td>Иностранных посетителей:</td>
       <td><input type="text" value="{$entry_event[$selected_language].foreign_visitors_num}" size="10" readonly /></td>
     </tr>
     </table>
  
    </td>
  </tr>
  </table>
</fieldset>


{foreach from=$list_languages item="lang"}
<fieldset>
  <legend><strong>{$lang.name}</strong></legend>

  <table cellspacing="2" width="100%">
  <tr>
    <td width="150">Номер события:</td>
    <td><input type="text" size="5" value="{$entry_event[$lang.code].number}" readonly /></td>
  </tr>

  <tr>
    <td valign="top">Время работы:</td>
    <td><div style="width:99%; height:50px; border: 1px solid #CCCCCC;">{$entry_event[$lang.code].work_time}</div></td>
  </tr>

  <tr>
    <td colspan="2">Описание:<br />
     <div style="width:99%; height:250px; overflow:auto; border: 1px solid #CCCCCC;">
       {$entry_event[$lang.code].description}</div>
    </td>
  </tr>
  
  <tr>
    <td colspan="2">Тематически секции:<br />
      <div style="width:99%; height:500px; overflow:auto; border: 1px solid #CCCCCC;">
        {$entry_event[$lang.code].thematic_sections}</div>
    </td>
  </tr>
  
  <tr>
    <td colspan="2" valign="top">
      <div>Контакты проектной группы:</div>
  
      <table style="border-collapse:collapse;">
      <tr>
        <td>Email: </td>
        <td><input value="{$entry_event[$lang.code].email}" size="25" readonly /></td>
      </tr>
  
      <tr>
        <td>Адрес сайта: </td>
        <td><a href="{$entry_event[$lang.code].web_address}" target="_blank">{$entry_event[$lang.code].web_address}</a></td>
      </tr>
  
      <tr>
        <td>Телефон: </td>
        <td><input type="text" value="{$entry_event[$lang.code].phone}" size="25" readonly /></td>
      </tr>
  
      <tr>
        <td>Факс: </td>
        <td><input type="text" value="{$entry_event[$lang.code].fax}" size="25" readonly /></td>
      </tr>
  
      <tr>
        <td>Контактное лицо: </td>
        <td><input type="text" value="{$entry_event[$lang.code].cont_pers_name}" size="25" readonly /></td>
      </tr>
  
      <tr>
        <td>Телефон КЛ: </td>
        <td><input type="text" value="{$entry_event[$lang.code].cont_pers_phone}" size="25" readonly /></td>
      </tr>
  
      <tr>
        <td>Email КЛ: </td>
        <td><input type="text" value="{$entry_event[$lang.code].cont_pers_email}" size="25" readonly /></td>
      </tr>
      </table>
    </td>
  </tr>
  </table>
</fieldset>

{/foreach}