<FORM method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data">
<INPUT type="hidden" name="common[dummy]" value="1">

<h4>Редактируем изображение в галлерее</h4>

<IMG src="/data/images/companies/{$entry.en.companies_id}/galleries/{$entry.en.companies_services_id}/{$entry.en.id}.jpg" />

<TABLE border="0" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD class="wb">Изображение:</TD>
  <TD><INPUT type="file" name="logo"><a href="/data/images/companies/{$entry.en.companies_id}/galleries/{$entry.en.companies_services_id}/{$entry.en.id}_big.jpg" target="_blank">Просмотреть</a></TD>
 </TR>
 {foreach from=$list_user_languages item="lang"}
 <TR>
  <TD class="wb">Название ({$lang.name}):</TD>
  <TD><INPUT type="text" size="70" name="{$lang.code}[title]" value="{$entry[$lang.code].title}"></TD>
 </TR>
 {/foreach}
 {foreach from=$list_user_languages item="lang"}
 <TR>
  <TD class="wb">Описание ({$lang.name}):</TD>
  <TD><TEXTAREA name="{$lang.code}[description]" style="width:100%; height:100px;">{$entry[$lang.code].description|escape:"html"}</TEXTAREA></TD>
  </TD>
 </TR>
 {/foreach}
 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Изменить"></TD></TR>
</TABLE>

</FORM>

<p><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></p>