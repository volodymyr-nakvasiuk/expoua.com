
<FORM method="post" action="{getUrl add="1" action="update"}">

<h4>Редактируем комментарий</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Выставочный центр:</TD>
  <TD><INPUT type="text" size="5" name="expocenters_id" value="{$entry.expocenters_id}"></TD>
 </TR>
 <TR>
  <TD>Сервисная компания:</TD>
  <TD><INPUT type="text" size="5" name="service_companies_id" value="{$entry.service_companies_id}"></TD>
 </TR>
 <TR>
  <TD>Новость:</TD>
  <TD><INPUT type="text" size="5" name="news_id" value="{$entry.news_id}"></TD>
 </TR>
 <TR>
  <TD>Статья:</TD>
  <TD><INPUT type="text" size="5" name="articles_id" value="{$entry.articles_id}"></TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="60" name="name" value="{$entry.name}"></TD>
 </TR>
 <TR>
  <TD>Email:</TD>
  <TD><INPUT type="text" size="60" name="email" value="{$entry.email}"></TD>
 </TR>
 <TR>
  <TD colspan="2">Сообщение:<BR />
   <TEXTAREA name="message" style="width:95%; height:200px;">{$entry.message}</TEXTAREA>
  </TD>
 </TR>

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Обновить"></TD></TR>
</TABLE>

</FORM>