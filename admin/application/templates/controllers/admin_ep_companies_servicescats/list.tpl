<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<h4>База категорий товаров и услуг компаний</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center" width="30">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="id" descr="Id"}
 {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="active" descr="A"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="name" descr="Название"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="companies_id" controller="admin_ep_companies_manage" descr="Компания"}
 <TH align="center" colspan="2">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
   <TD align="center">
  <FORM method="post" action="{getUrl add="1" action="update" id=$el.id}" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" {if $el.active==1} checked{/if} onclick="this.form.submit();">
  <INPUT type="hidden" name="active" value="{if $el.active==1}0{else}1{/if}">
  </FORM>
 </TD>
  <TD>{$el.name}</TD>
  <td>{$el.company_name}&nbsp;</td>
 {include file="common/Actions/general.tpl" el=$el}
 </TR>
{/foreach}
</TABLE>

<p>
<SCRIPT type="text/javascript">
objCompaniesList = Shelby_Backend.ListHelper.cloneObject('objCompaniesList');

objCompaniesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objCompaniesList.returnFieldId = 'companies_id';
objCompaniesList.feedUrl = '{getUrl controller="admin_ep_companies_manage" action="list" feed="json"}';
objCompaniesList.writeForm();
</SCRIPT>

<p>Добавление новой категории:</p>

<FORM action="{getUrl add="1" action="insert"}" method="post">
 Название: <INPUT type="text" name="name" size="40" /><BR />
 Компания: <INPUT type="text" size="5" name="companies_id" id="companies_id"{if !empty($user_params.parent)} value="{$user_params.parent}"{/if}> <INPUT type="button" onclick="objCompaniesList.showPopUp();" value="Выбрать"> <SPAN id="companies_id_name"></SPAN><BR />
 <INPUT type="submit" value="Добавить" />
</FORM>
</p>