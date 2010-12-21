{if $entry_event[$lang].logo}
  {$entry_event[$lang].image_logo}
  <img src="http://admin.expopromoter.com/data/images/events/logo/{$entry_event[$lang].languages_id}/{$entry_event[$lang].id}.jpg" alt="" />
{/if}

<TABLE cellspacing="2" width="100%">
 <TR><TH colspan="2"><h5 align="center">{$lang_name}</h5></TH></TR>
 <TR>
  <TD width="150">Номер события:</TD>
  <TD>
    <INPUT type="text" size="5" name="{$lang}[number]" value="{$entry[$lang].number}">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Активировать:&nbsp;<INPUT type="checkbox" name="{$lang}[active]" value="1" {if !empty($entry[$lang].brands_id) || !empty($entry[$lang].brand_name_extended_new)}checked="checked"{/if} />
  </TD>
 </TR>
 <TR>
  <TD valign="top">Время работы:</TD>
  <TD><TEXTAREA name="{$lang}[work_time]" style="width:99%; height:50px;">{$entry[$lang].work_time}</TEXTAREA></TD>
 </TR>
 <TR>

 <TR>
  <TD colspan="2">Описание:<BR />
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
 </TR>
</TABLE>
