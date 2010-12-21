<H4>{#query_details#}</H4>

<p class="important">{#msgWarning1#}</p>

<TABLE border="1" style="border-collapse:collapse;" cellpadding="3" align="center">
<TR>
  <TD>{#exhibition#}:</TD>
  <TD>{$entry.brand_name}</TD>
</TR>
<TR>
  <TD>{#query_type#}:</TD>
  <TD>{if $entry.type=="exhibitionExtraInfoRequest"}{#type_extrainfo#}{elseif $entry.type=="exhibitionParticipationRequest"}{#type_participation#}{elseif $entry.type=="exhibitionCatalogAdvertRequest"}{#type_catalogadv#}{elseif $entry.type=="exhibitionAdvertSpreadRequest"}{#type_advspread#}{/if}</TD>
</TR>
<TR>
  <TD>{#company#}: </TD>
  <TD>{$entry.details.name}</TD>
</TR>
<TR>
  <TD>{#contact_person#}: </TD>
  <TD>{$entry.details.contact_person}</TD>
</TR>
<TR>
  <TD>{#phone#}: </TD>
  <TD>{$entry.details.phone}</TD>
</TR>
<TR>
  <TD>{#fax#}: </TD>
  <TD>{$entry.details.fax}</TD>
</TR>
<TR>
  <TD>{#email#}: </TD>
  <TD>{$entry.details.email}</TD>
</TR>
<TR>
  <TD>{#webPage#}: </TD>
  <TD>{$entry.details.url}</TD>
</TR>
<TR>
  <TD>{#address#}: </TD>
  <TD>{$entry.details.address}</TD>
</TR>
{if !empty($entry.details.purpose)}
<TR>
  <TD>{#about_req#}: </TD>
  <TD>{if $entry.details.purpose==1}{#pur_visiting#}{elseif $entry.details.purpose==2}{#pur_participation#}{elseif $entry.details.purpose==3}{#pur_participation2#}{elseif $entry.details.purpose==4}{#pur_advspread#}{elseif $entry.details.purpose==5}{#pur_other#}{/if}</TD>
</TR>
{/if}
<TR>
  <TD>{#message#}: </TD>
  <TD>{$entry.details.message|nl2br}</TD>
</TR>

{if $entry.type == 'exhibitionParticipationRequest'}
<TR>
  <TD>{#products_services#}: </TD>
  <TD>{$entry.details.details|nl2br}</TD>
</TR>
<TR>
  <TD>{#pr_S1#}: </TD>
  <TD>{$entry.details.S1} m<sup>2</sup></TD>
</TR>
<TR>
  <TD>{#pr_S2#}: </TD>
  <TD>{$entry.details.S2} m<sup>2</sup></TD>
</TR>
<TR>
  <TD>{#pr_S3#}: </TD>
  <TD>{$entry.details.S3} m<sup>2</sup></TD>
</TR>
<TR>
  <TD>{#booth_types#}: </TD>
  <TD>
  {if $entry.details.check1==1}{#stand_type1#}<BR />{/if}
  {if $entry.details.check2==1}{#stand_type2#}<BR />{/if}
  {if $entry.details.check3==1}{#stand_type3#}<BR />{/if}
  {if $entry.details.check4==1}{#stand_type4#}<BR />{/if}
  {if $entry.details.check5==1}{#stand_type5#}<BR />{/if}
  </TD>
</TR>

{elseif $entry.type == 'exhibitionCatalogAdvertRequest'}
<TR>
  <TD>{#catalog_advert#}:</TD>
  <TD>
  {if $entry.details.check1==1}{#catalog_advert_type1#}<BR />{/if}
  {if $entry.details.check1==2}{#catalog_advert_type2#}<BR />{/if}
  </TD>
</TR>
<TR>
  <TD>{#extra_wishes#}: </TD>
  <TD>{$entry.details.details|nl2br}</TD>
</TR>
{elseif $entry.type == 'exhibitionAdvertSpreadRequest'}
<TR>
  <TD>{#advspread_title1#}: </TD>
  <TD>{$entry.details.S1} {#copies#}</TD>
</TR>
<TR>
  <TD>{#advspread_title2#}: </TD>
  <TD>{$entry.details.S2} {#copies#}</TD>
</TR>
<TR>
  <TD>{#advspread_services#}:</TD>
  <TD>
    {if $entry.details.check1==1}{#advspread_services_type1#}<BR />{/if}
    {if $entry.details.check2==1}{#advspread_services_type2#}<BR />{/if}
  </TD>
</TR>
<TR>
  <TD>{#advspread_title3#}:</TD>
  <TD>
  {if $entry.details.check3==1}{#advspread_services_type3#}<BR />{/if}
  {if $entry.details.check4==1}{#advspread_services_type4#}<BR />{/if}
  {if $entry.details.check5==1}{#advspread_services_type5#}<BR />{/if}
  </TD>
</TR>
{/if}

</TABLE>

{if !isset($user_params.simple)}
<FORM method="post" action="{getUrl add=1 action="send"}">
<p style="text-align:center;">{#captionSendRequest#}: <INPUT type="text" name="email" size="20"/> <INPUT type="submit" value=" {#sendAction#} " /></p>
</FORM>

<P><A href="{getUrl add="1" action="list" del="id"}">{#back_to_list#}</A></P>
{/if}