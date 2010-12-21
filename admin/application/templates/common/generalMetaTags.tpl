  <TABLE class="selected_table">
   <TR><TD>Заголовок:</TD><TD><INPUT type="text" size="50" name="title" id="meta_title" {if !empty($entry.title)}value="{$entry.title}"{/if}></TD></TR>
   <TR><TD>Ключевые слова:</TD><TD><INPUT type="text" size="50" name="keywords" id="meta_keywords" {if !empty($entry.keywords)}value="{$entry.keywords}"{/if}></TD></TR>
   <TR><TD colspan="2">Описание:</TD></TR>
   <TR><TD colspan="2"><TEXTAREA cols="60" rows="2" name="description" id="meta_description">{if !empty($entry.description)}{$entry.description|escape}{/if}</TEXTAREA></TD></TR>
  </TABLE>
