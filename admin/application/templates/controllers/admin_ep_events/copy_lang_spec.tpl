<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD width="200">Бренд:</TD>
  <TD>{$entry[$lang].brand_name}<BR />{$entry[$lang].brand_name_extended}</TD>
 </TR>
 <TR>
  <TD>Номер события:</TD>
  <TD><INPUT type="text" size="5" name="{$lang}[number]" value="{$entry[$lang].number}"></TD>
 </TR>
 <TR>
  <TD valign="top">Время работы:</TD>
  <TD><TEXTAREA name="{$lang}[work_time]" style="width:99%; height:50px;">{$entry[$lang].work_time}</TEXTAREA></TD>
 </TR>
 <TR>

 <TR>
  <TD colspan="2"><a name="anchor_description_{$lang}""><!-- --></a>Описание:<BR />
   <TEXTAREA name="{$lang}[description]" id="description_{$lang}" style="width:99%; height:250px;">{$entry[$lang].description|escape:"html"}</TEXTAREA>
  </TD>
 </TR>

 <TR>
  <TD colspan="2">Тематически секции:<BR />
   <TEXTAREA name="{$lang}[thematic_sections]" id="thematic_sections_{$lang}" style="width:99%; height:500px;">{$entry[$lang].thematic_sections|escape:"html"}</TEXTAREA>
  </TD>
 </TR>

 <TR>
  <TD valign="top" colspan="2">

   <TABLE width="100%">
    <TR><TD width="50%">

   <div>Контакты проектной группы:</div>
   <TABLE style="border-collapse:collapse;">
    <TR>
     <TD>Email: </TD>
     <TD><INPUT type="text" name="{$lang}[email]" id="email_{$lang}" size="25" value="{$entry[$lang].email}"></TD>
    </TR>
    <TR>
     <TD>Адрес сайта: </TD>
     <TD>
      <INPUT type="text" name="{$lang}[web_address]" id="web_address_{$lang}" size="25" value="{$entry[$lang].web_address}">
      (<a href="{$entry[$lang].web_address}" target="_blank">перейти</a>)
     </TD>
    </TR>
    <TR>
     <TD>Телефон: </TD>
     <TD><INPUT type="text" name="{$lang}[phone]" id="phone_{$lang}" size="25" value="{$entry[$lang].phone}"></TD>
    </TR>
    <TR>
     <TD>Факс: </TD>
     <TD><INPUT type="text" name="{$lang}[fax]" id="fax_{$lang}" size="25" value="{$entry[$lang].fax}"></TD>
    </TR>
    <TR>
     <TD>Контактное лицо: </TD>
     <TD><INPUT type="text" name="{$lang}[cont_pers_name]" size="25" value="{$entry[$lang].cont_pers_name}"></TD>
    </TR>
    <TR>
     <TD>Телефон КЛ: </TD>
     <TD><INPUT type="text" name="{$lang}[cont_pers_phone]" size="25" value="{$entry[$lang].cont_pers_phone}"></TD>
    </TR>
    <TR>
     <TD>Email КЛ: </TD>
     <TD><INPUT type="text" name="{$lang}[cont_pers_email]" size="25" value="{$entry[$lang].cont_pers_email}"></TD>
    </TR>
   </TABLE>

  </TD>
  <TD valign="top">

   <div>Дополнительная информация о событии:</div>
   <TABLE style="border-collapse:collapse;">
    <TR>
     <TD>Участников всего:</TD>
     <TD><INPUT type="text" name="{$lang}[partic_num]" size="10" value="{$entry[$lang].partic_num}" /></TD>
    </TR>
    <TR>
     <TD>Местных участников:</TD>
     <TD><INPUT type="text" name="{$lang}[local_partic_num]" size="10" value="{$entry[$lang].local_partic_num}" /></TD>
    </TR>
    <TR>
     <TD>Иностранных участников:</TD>
     <TD><INPUT type="text" name="{$lang}[foreign_partic_num]" size="10" value="{$entry[$lang].foreign_partic_num}" /></TD>
    </TR>
    <TR>
     <TD>Общая площадь:</TD>
     <TD><INPUT type="text" name="{$lang}[s_event_total]" size="10" value="{$entry[$lang].s_event_total}" /></TD>
    </TR>
    <TR>
     <TD>Посетителей всего:</TD>
     <TD><INPUT type="text" name="{$lang}[visitors_num]" size="10" value="{$entry[$lang].visitors_num}" /></TD>
    </TR>
    <TR>
     <TD>Местных посетителей:</TD>
     <TD><INPUT type="text" name="{$lang}[local_visitors_num]" size="10" value="{$entry[$lang].local_visitors_num}" /></TD>
    </TR>
    <TR>
     <TD>Иностранных посетителей:</TD>
     <TD><INPUT type="text" name="{$lang}[foreign_visitors_num]" size="10" value="{$entry[$lang].foreign_visitors_num}" /></TD>
    </TR>
   </TABLE>

  </TR></TR>
  </TABLE>

  </TD>
 </TR>
</TABLE>
