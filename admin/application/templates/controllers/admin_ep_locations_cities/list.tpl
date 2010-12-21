<h4>База регионов/стран/городов: Города</h4>

<p>
  <a href="{getUrl controller='admin_ep_locations_regions' action='list'}">Регионы</a> &gt;
  <a href="{getUrl controller='admin_ep_locations_countries' parent=$user_params.region action='list'}">Страны</a> &gt;
  <strong>Города</strong>
</p>

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center" width="40">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="40" align="center" name="id" descr="Id"}
 {include file="common/Lists/headerElementGeneral.tpl" width="70" align="center" name="active" descr="Aктивен"}
 {* include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="extended" descr="Расш.список" *}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="name" descr="Название"}
 <TH align="center" colspan="2" width="100">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}

{foreach from=$list.data item="el" name="fe"}
<TR class="{cycle values="odd,even"}{if is_null($el.geonameid)} marked{/if}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD align="center">
{if $HMixed->isCountryOwned($el.countries_id) == true}
    <FORM method="post" action="{getUrl add="1" action="update" id=$el.id}" style="padding:0px; margin:0px;">
      <INPUT type="checkbox" {if $el.active==1} checked{/if} onclick="this.form.submit();">
      <INPUT type="hidden" name="active" value="{if $el.active==1}0{else}1{/if}">
    </FORM>
{else}
	<INPUT type="checkbox" {if $el.active==1} checked="checked"{/if} disabled="disabled" />
{/if}
  </TD>

{*  <TD align="center">
    <INPUT type="checkbox" {if $el.extended==1} checked="checked"{/if} disabled="disabled">
  </TD> *}

  <TD>{$el.name}</TD>
{if $HMixed->isCountryOwned($el.countries_id) == true}
  {include file="common/Actions/general.tpl" el=$el}
{else}
  <TD width="50"></TD><TD width="50"></TD>
{/if}
</TR>
{/foreach}
</TABLE>

{if $HMixed->isCountryOwned($user_params.parent) == true}
<p>
<FORM method="post" action="{getUrl add="1" action="insert"}">
 <INPUT type="text" name="name" /><BR />
 <INPUT type="submit" value="Добавить" />
</FORM>
</p>
{/if}