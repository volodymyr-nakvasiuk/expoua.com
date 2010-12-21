<p>{#captionExhibition#}: {$entry[$selected_language].brand_name}</p>

<form method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data">
  <input type="hidden" name="dummy" value="dummyvalue" />

  {foreach from=$list_languages item="lang"}
  <hr />

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="wb">{#captionLogo#} ({$lang.name}):</td>
  </tr>
  </table>

  <input type="file" name="logo[{$lang.code}]" />
  <p>{if $entry[$lang.code].logo==1}<img src="/data/images/events/logo/{$lang.id}/{$entry[$lang.code].id}.jpg" />{else}{#captionNoImage#}{/if}
  {/foreach}

  <hr />

  <input type="submit" value=" {#updateAction#} " />

</form>