{if !empty($user_params.parent)}

<FORM method="post" action="{getUrl add="1" action="insert"}" enctype="multipart/form-data">
<INPUT type="hidden" name="common[companies_services_id]" value="{$user_params.parent}">
{debug}
<h4>Добавляем новое изображение в галерею товаров и услуг</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Изображение:</TD>
  <TD><INPUT type="file" name="logo"></TD>
 </TR>
{foreach from=$list_user_languages item="lang"}
 <TR>
  <TD>Заголовок ({$lang.name}):</TD>
  <TD><INPUT type="text" size="50" name="{$lang.code}[title]"></TD>
 </TR>
{/foreach}
{foreach from=$list_user_languages item="lang"}
 <TR>
  <TD colspan="2">Описание ({$lang.name}):<BR />
  <TEXTAREA name="{$lang.code}[description]" style="width:90%; height:100px;"></TEXTAREA>
 </TR>
{/foreach}

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Добавить"></TD></TR>
</TABLE>

</FORM>

{/if}

<p><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></p>