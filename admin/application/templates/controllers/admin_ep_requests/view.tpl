<H4>Детали запроса</H4>

<TABLE border="1" style="border-collapse:collapse;" cellpadding="3" align="center">
 <TR>
  <TD>Название компании: </TD>
  <TD>{$entry.details.name}</TD>
 </TR>
 <TR>
  <TD>Контактное лицо: </TD>
  <TD>{$entry.details.contact_person}</TD>
 </TR>
 <TR>
  <TD>Телефон: </TD>
  <TD>{$entry.details.phone}</TD>
 </TR>
 <TR>
  <TD>Факс: </TD>
  <TD>{$entry.details.fax}</TD>
 </TR>
 <TR>
  <TD>Электронный адрес: </TD>
  <TD>{$entry.details.email}</TD>
 </TR>
 <TR>
  <TD>Адрес сайта: </TD>
  <TD>{$entry.details.url}</TD>
 </TR>
 <TR>
  <TD>Адрес: </TD>
  <TD>{$entry.details.address}</TD>
 </TR>
{if !empty($entry.details.purpose)}
 <TR>
  <TD>Цель запроса: </TD>
  <TD>{if $entry.details.purpose==1}для посещения{elseif $entry.details.purpose==2}для возможного участия{elseif $entry.details.purpose==3}для возможного заочного участия{elseif $entry.details.purpose==4}заказать распространение рекламной продукции на выставке{elseif $entry.details.purpose==5}другое{/if}</TD>
 </TR>
{/if}
 <TR>
  <TD>Сообщение: </TD>
  <TD>{$entry.details.message|nl2br}</TD>
 </TR>

{if $entry.type == 'exhibitionParticipationRequest'}
 <TR>
  <TD>Продукция и услуги: </TD>
  <TD>{$entry.details.details|nl2br}</TD>
 </TR>
 <TR>
  <TD>Необорудованная площадь: </TD>
  <TD>{$entry.details.S1} m<sup>2</sup></TD>
 </TR>
 <TR>
  <TD>Оборудованная площадь: </TD>
  <TD>{$entry.details.S2} m<sup>2</sup></TD>
 </TR>
 <TR>
  <TD>Открытая площадь: </TD>
  <TD>{$entry.details.S3} m<sup>2</sup></TD>
 </TR>
 <TR>
  <TD>Типы стендов: </TD>
  <TD>
  {if $entry.details.check1==1}Линейный стенд (открыт в одну сторону)<BR />{/if}
  {if $entry.details.check2==1}Угловой стенд (открыт в две стороны)<BR />{/if}
  {if $entry.details.check3==1}Полуостров (открыт в три стороны)<BR />{/if}
  {if $entry.details.check4==1}Остров (открыт в четыре стороны)<BR />{/if}
  {if $entry.details.check5==1}Двухэтажный стенд<BR />{/if}
  </TD>
 </TR>

{elseif $entry.type == 'exhibitionCatalogAdvertRequest'}
 <TR>
  <TD>Реклама в официальном каталоге выставки:</TD>
  <TD>
   {if $entry.details.check1==1}Цветная информация<BR />{/if}
   {if $entry.details.check1==2}Черно-белая информация<BR />{/if}
  </TD>
 </TR>
 <TR>
  <TD>Дополнительные пожелания: </TD>
  <TD>{$entry.details.details|nl2br}</TD>
 </TR>
{elseif $entry.type == 'exhibitionAdvertSpreadRequest'}
<TR>
 <TD>Распространение со стенда организатора: </TD>
 <TD>{$entry.details.S1} экземпляров</TD>
</TR>
<TR>
 <TD>Целевое распространение (промоутеры): </TD>
 <TD>{$entry.details.S2} экземпляров</TD>
</TR>
<TR>
 <TD>Услуги:</TD>
 <TD>
 {if $entry.details.check1==1}Аудио объявление во время выставки<BR />{/if}
 {if $entry.details.check2==1}Другое (уточните в комментарии)<BR />{/if}
 </TD>
</TR>
<TR>
 <TD>Распространение рекламы:</TD>
 <TD>
 {if $entry.details.check3==1}Листовка форматом до А4<BR />{/if}
 {if $entry.details.check4==1}Буклет форматом до А4(вес до 100 г)<BR />{/if}
 {if $entry.details.check5==1} Другое (уточните в комментарии)<BR />{/if}
 </TD>
</TR>
{elseif $entry.type == 'exhibitionRemoteAttendanceRequest'}
<TR>
 <TD>Дополнительные опции:</TD>
 <TD>
 {if $entry.details.check1==1}посещение дополнительных стендов участников выставки (50 компаний)<BR />{/if}
 {if $entry.details.check2==1}фотоматериалы выставки (50-100 отчетных снимков)<BR />{/if}
 {if $entry.details.check3==1}видеоматериалы выставки (до 60 минут)<BR />{/if}
 </TD>
</TR>
{/if}

</TABLE>

<P><A href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</A></P>