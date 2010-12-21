<HTML>
 <HEAD>
  <TITLE>{#hdrNewsPreview#}</TITLE>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <LINK rel="stylesheet" href="{$document_root}css/admin/admin_style.css" type="text/css" media="screen" />
  <LINK rel="stylesheet" href="{$document_root}css/admin/org_style.css" type="text/css" media="screen" />
  <SCRIPT type="text/javascript" src="{$document_root}js/adminGeneral.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/jquery.js"></SCRIPT>
 </HEAD>
 <BODY>

<SCRIPT type="text/javascript">
{literal}
$(document).ready(function(){
	window.resizeTo(800, 600);
});
{/literal}
</SCRIPT>

<TABLE border="0" width="100%" style="border-collapse:collapse;" cellpadding="5">
 <TR>
  <TD class="wb">{#captionExhibition#}:</TD>
  <TD>{$data.brand_name}</TD>
 </TR>
 {foreach from=$list_languages item="lang"}
 <TR>
  <TD class="wb">{#captionHeadline#} ({$lang.name}):</TD>
  <TD>{$data[$lang.code].name}</TD>
 </TR>
 {/foreach}
 <TR>
  <TD class="wb">{#captionDate#}:</TD>
  <TD>{$data.common.date_public}</TD>
 </TR>

 {foreach from=$list_languages item="lang"}
 <TR>
  <TD class="wb" colspan="2">{#captionDescription#} ({$lang.name}):<BR />
{$data[$lang.code].preambula|nl2br}
  </TD>
 </TR>
 {/foreach}
 {foreach from=$list_languages item="lang"}
 <TR>
  <TD colspan="2">{#captionText#} ({$lang.name}):<BR />
{$data[$lang.code].content}
  </TD>
 </TR>
 {/foreach}
 <TR><TD align="center" colspan="2"><INPUT type="button" value="{#closeAction#}" onClick="window.close();" /></TD></TR>
</TABLE>

 </BODY>
</HTML>
