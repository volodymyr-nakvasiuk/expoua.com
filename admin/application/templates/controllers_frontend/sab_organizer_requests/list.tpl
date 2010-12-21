<p class="important">{#msgWarning1#}</p>

{*include file="controllers_frontend/sab_organizer_requests/top_menu.tpl"*}

<p style="text-align:right">
  {include file="common/tip.tpl" topic="organizer_csv_export"} 
  <a href="{getUrl add=1 page=1 results=999999 feed=csv}">CSV Export</a>
</p>

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<SCRIPT type="text/javascript">
function setBrandFilter(id) {ldelim}
	var url = "{getUrl add="1" del="search,page,id"}";
	if (id > 0) {ldelim}
		url += "search/brands_id=" + id + "/";
	{rdelim}
	document.location.href = url;
{rdelim}
</SCRIPT>

{assign var="filter_brand" value=0}
{foreach from=$list.search item="el"}
 {if $el.column == "brands_id"}{assign var="filter_brand" value=$el.value}{/if}
{/foreach}

<p>
  {#captionFilter#}:
  <select onchange="setBrandFilter(this.value);">
    <option value="0">{#msgNotSelected#}</option>
    {foreach from=$list_brands.data item="el"}
    <option value="{$el.id}"{if $el.id==$filter_brand} selected="selected"{/if}>{$el.name}</option>
    {/foreach}
  </select>
</p>

<TABLE border="0" width="100%" class="list" align="center">
<TR>
  <TH align="center"><a href="{getUrl add="1" sort=$HMixed->getSortOrder('exhibition', $list.sort_by)}">{#exhibition#}</a></TH>
  <TH style="text-align:center"><a href="{getUrl add="1" sort=$HMixed->getSortOrder('request_type', $list.sort_by)}">{#about_req#}</a></TH>
  <TH style="text-align:center">{#language_req#}</TH>
  <TH style="text-align:center"><a href="{getUrl add="1" sort=$HMixed->getSortOrder('date_add', $list.sort_by)}">{#date_req#}</a></TH>
  <TH style="text-align:center">{#actions#}</TH>
</TR>

{foreach from=$list.data item="el"}
<TR class="{cycle values="odd,even"}{if $el.show_list_logo==0} disabled{/if}" {if $el.show_list_logo==1}style="cursor:pointer;{if $el.viewed==0} font-weight:bold;{/if}" onclick="document.location.href='{getUrl add="1" action="view" id=$el.id}';"{/if}>
 <TD>{$el.brand_name} ({$el.date_from})</TD>
 <TD align="center">
 {if $el.type=='exhibitionExtraInfoRequest'}

{if $el.purpose==1}{#pur_visiting#}{elseif $el.purpose==2}{#pur_participation#}{elseif $el.purpose==3}{#pur_participation2#}{elseif $el.purpose==4}{#pur_advspread#}{elseif $el.purpose==5}{#pur_other#}{/if}

 {elseif $el.type=="exhibitionParticipationRequest"}{#type_participation#}{elseif $el.type=="exhibitionCatalogAdvertRequest"}{#type_catalogadv#}{elseif $el.type=="exhibitionAdvertSpreadRequest"}{#type_advspread#}{/if}
 </TD>
 <TD align="center">{$el.lang_name}</TD>
 <TD align="center">{$el.date_add}</TD>
 <TD align="center"><a href="{getUrl add="1" action="view" id=$el.id}"><img title="{#captionView#}" src="/images/admin/list-preview.gif" border="0" height="21" width="23"></a></TD>
</TR>
{/foreach}
</TABLE>