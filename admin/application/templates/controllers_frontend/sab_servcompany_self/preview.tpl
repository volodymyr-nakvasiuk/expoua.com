<HTML>
 <HEAD>
  <TITLE>Предварительный просмотр анкеты</TITLE>
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
 <!--{*<TR>
  <TD class="wb">{Общественная организация:}</TD>
  <TD>{$data.social_organization_name}</TD>
 </TR>*}-->
 {foreach from=$list_languages item="lang"}
 <TR>
  <TD class="wb">{#Name#} ({$lang.name}):</TD>
  <TD>{$data[$lang.code].name}</TD>
 </TR>
 {/foreach}
 {foreach from=$list_languages item="lang"}
 <TR>
  <TD class="wb">{#Description#} ({$lang.name}):</TD>
  <TD>{$data[$lang.code].description}</TD>
 </TR>
 {/foreach}
 {foreach from=$list_languages item="lang"}
 <TR>
  <TD class="wb">{#Activities#} ({$lang.name}):</TD>
  <td>{$data[$lang.code].activity_forms|nl2br}</td>
 </TR>
 {/foreach}
 
 {foreach from=$list_languages item="lang"}
 <TR>
  <TD class="wb">{#Additional_info#} ({$lang.name}):</TD>
  <td>{$data[$lang.code].additional_info|nl2br}</td>
 </TR>
 {/foreach}
 
 {foreach from=$list_languages item="lang"}
 <TR>
  <TD class="wb">{#Contacts#} ({$lang.name}):</TD>
  <td>
  	<TABLE border="0" width="45%" style="border-collapse:collapse; float:left;">
    <TR>
     <TD>&raquo;&nbsp;Email: </TD>
     <TD>{$data[$lang.code].email}</TD>
    </TR>
    <TR>
     <TD>&raquo;&nbsp;{#Web_page#}: </TD>
     <TD>{$data[$lang.code].web_address}</TD>
    </TR>
    <TR>
     <TD>&raquo;&nbsp;{#Phone#}: </TD>
     <TD>{$data[$lang.code].phone}</TD>
    </TR>
    <TR>
     <TD>&raquo;&nbsp;{#Fax#}: </TD>
     <TD>{$data[$lang.code].fax}</TD>
    </TR>
    <TR>
     <TD>&raquo;&nbsp;{#Address#}: </TD>
     <TD>{$data[$lang.code].address}</TD>
    </TR>
    <TR>
     <TD>&raquo;&nbsp;{#Index#}: </TD>
     <TD>{$data[$lang.code].postcode}</TD>
    </TR>
   </TABLE>
  </td>
 </TR>
 {/foreach}
 
 <TR><TD align="center" colspan="2"><INPUT type="button" value="Закрыть" onClick="window.close();" /></TD></TR>
</TABLE>

 </BODY>
</HTML>
