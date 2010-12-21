{if !empty($entry_event[$lang])}
<TABLE width="100%">
<TR><TD width="50%" valign="top">

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD width="150">Номер события:</TD>
  <TD><INPUT type="text" size="5" value="{$entry_event[$lang].number}" readonly /></TD>
 </TR>
 <TR>
  <TD valign="top">Время работы:</TD>
  <TD><div style="width:99%; height:50px; border: 1px solid #CCCCCC;">{$entry_event[$lang].work_time}</DIV></TD>
 </TR>
 <TR>

 <TR>
  <TD colspan="2">Описание:<BR />
   <div style="height:27px; padding:20px 0; background:#F0F0EE; border: 1px solid #CCCCCC;">
     &nbsp;&nbsp;&nbsp;&nbsp;<button type="button" onclick="$('#description_{$lang}').val($('#sdescription_{$lang}').html()); tinyMCE.updateContent('description_{$lang}');">&nbsp;&gt;&gt;&gt;&nbsp;</button>
     Нажмите чтобы скопировать
   </div>
   <DIV style="height:180px; overflow:auto; border: 1px solid #CCCCCC;">
     <div class="inner_content" id="sdescription_{$lang}">{$entry_event[$lang].description}</div>
   </DIV>
  </TD>
 </TR>

 <TR>
  <TD colspan="2">Тематически секции:<BR />
   <div style="height:27px; padding:20px 0; background:#F0F0EE; border: 1px solid #CCCCCC;">
     &nbsp;&nbsp;&nbsp;&nbsp;<button type="button" onclick="$('#thematic_sections_{$lang}').val($('#sthematic_sections_{$lang}').html()); tinyMCE.updateContent('thematic_sections_{$lang}');">&nbsp;&gt;&gt;&gt;&nbsp;</button>
     Нажмите чтобы скопировать
   </div>
   <DIV style="height:430px; overflow:auto; border: 1px solid #CCCCCC;">
     <div class="inner_content" id="sthematic_sections_{$lang}">{$entry_event[$lang].thematic_sections}</div>
   </DIV>
  </TD>
 </TR>

 <TR>
  <TD colspan="2" valign="top">

   <div>Контакты проектной группы:</div>
   <TABLE style="border-collapse:collapse;">
    <TR>
     <TD>Email: </TD>
     <TD><INPUT value="{$entry_event[$lang].email}" size="25" readonly /></TD>
    </TR>
    <TR>
     <TD>Адрес сайта: </TD>
     <TD><a href="{$entry_event[$lang].web_address}" target="_blank">{$entry_event[$lang].web_address}</a></TD>
    </TR>
    <TR>
     <TD>Телефон: </TD>
     <TD><INPUT type="text" value="{$entry_event[$lang].phone}" size="25" readonly /></TD>
    </TR>
    <TR>
     <TD>Факс: </TD>
     <TD><INPUT type="text" value="{$entry_event[$lang].fax}" size="25" readonly /></TD>
    </TR>
    <TR>
     <TD>Контактное лицо: </TD>
     <TD><INPUT type="text" value="{$entry_event[$lang].cont_pers_name}" size="25" readonly /></TD>
    </TR>
    <TR>
     <TD>Телефон КЛ: </TD>
     <TD><INPUT type="text" value="{$entry_event[$lang].cont_pers_phone}" size="25" readonly /></TD>
    </TR>
    <TR>
     <TD>Email КЛ: </TD>
     <TD><INPUT type="text" value="{$entry_event[$lang].cont_pers_email}" size="25" readonly /></TD>
    </TR>
   </TABLE>

  </TD>
 </TR>
</TABLE>

{* Новый черновик *}

 </TD>
 <TD valign="top">
{/if}

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Номер события:</TD>
  <TD><INPUT type="text" size="5" name="{$lang}[number]"></TD>
 </TR>
 <TR>
  <TD valign="top">Время работы:</TD>
  <TD><TEXTAREA name="{$lang}[work_time]" style="width:99%; height:50px;"></TEXTAREA></TD>
 </TR>
 <TR>

 <TR>
  <TD colspan="2"><a name="anchor_description_{$lang}"><!-- --></a>Описание:<BR />
   <TEXTAREA name="{$lang}[description]" id="description_{$lang}" style="width:99%; height:250px;"></TEXTAREA>
  </TD>
 </TR>

 <TR>
  <TD colspan="2">Тематически секции:<BR />
   <TEXTAREA name="{$lang}[thematic_sections]" id="thematic_sections_{$lang}" style="width:99%; height:500px;"></TEXTAREA>
  </TD>
 </TR>

 <TR>
  <TD colspan="2" valign="top">

   <div>Контакты проектной группы:</div>
   <TABLE style="border-collapse:collapse;">
    <TR>
     <TD>Email: </TD>
     <TD><INPUT type="text" name="{$lang}[email]" id="email_{$lang}" size="25" value="{$entry_event[$lang].email}" onblur="copyField('{$lang}', 'email_', this.value);"></TD>
    </TR>
    <TR>
     <TD>Адрес сайта: </TD>
     <TD><INPUT type="text" name="{$lang}[web_address]" id="web_address_{$lang}" size="25" value="{$entry_event[$lang].web_address}" onblur="copyField('{$lang}', 'web_address_', this.value);"></TD>
    </TR>
    <TR>
     <TD>Телефон: </TD>
     <TD><INPUT type="text" name="{$lang}[phone]" id="phone_{$lang}" size="25" value="{$entry_event[$lang].phone}" onblur="copyField('{$lang}', 'phone_', this.value);"></TD>
    </TR>
    <TR>
     <TD>Факс: </TD>
     <TD><INPUT type="text" name="{$lang}[fax]" id="fax_{$lang}" size="25" value="{$entry_event[$lang].fax}" onblur="copyField('{$lang}', 'fax_', this.value);"></TD>
    </TR>
    <TR>
     <TD>Контактное лицо: </TD>
     <TD><INPUT type="text" name="{$lang}[cont_pers_name]" size="25" value="{$entry_event[$lang].cont_pers_name}"></TD>
    </TR>
    <TR>
     <TD>Телефон КЛ: </TD>
     <TD><INPUT type="text" name="{$lang}[cont_pers_phone]" size="25" value="{$entry_event[$lang].cont_pers_phone}"></TD>
    </TR>
    <TR>
     <TD>Email КЛ: </TD>
     <TD><INPUT type="text" name="{$lang}[cont_pers_email]" size="25" value="{$entry_event[$lang].cont_pers_email}"></TD>
    </TR>
   </TABLE>

  </TD>
 </TR>
</TABLE>

{if !empty($entry_event[$lang])}
 </TD>
</TR>
</TABLE>
{/if}