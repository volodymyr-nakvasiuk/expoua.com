<h4>Зарегистрированные партнеры</h4>

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="active" descr="A"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="login" descr="Имя пользователя"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="partners_id" controller="admin_ep_partner" descr="Партнер"}
 <TH align="center" colspan="2">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
   <TD align="center">
  <FORM method="post" action="{getUrl add="1" action="update" id=$el.id}" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" {if $el.active==1} checked{/if} onclick="this.form.submit();">
  <INPUT type="hidden" name="active" value="{if $el.active==1}0{else}1{/if}">
  </FORM>
 </TD>
  <TD>{$el.login}</TD>
  <TD>{$el.partner_name}</TD>
 {include file="common/Actions/general.tpl" el=$el}
 </TR>
{/foreach}
</TABLE>

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>
objPartnerList = Shelby_Backend.ListHelper.cloneObject('objPartnerList');

objPartnerList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objPartnerList.returnFieldId = 'partners_id';
objPartnerList.feedUrl = '{getUrl controller="admin_ep_partner" action="list" feed="json"}';
objPartnerList.writeForm();
</SCRIPT>

<p>
<form method="post" action="{getUrl add='1' action='insert'}">
<INPUT type="hidden" name="super" value="1" />
<TABLE>
<TR><TD>Имя пользователя:</TD><TD><INPUT type="text" name="login" /></TD></TR>
<TR><TD>Пароль:</TD><TD><INPUT type="text" name="passwd" /></TD></TR>
<TR><TD>Партнер:</TD><TD><INPUT type="text" name="partners_id" id="partners_id" size="5" /> <INPUT type="button" onclick="objPartnerList.showPopUp();" value="Выбрать"></TD></TR>
</TABLE>
<INPUT type="submit" value="Добавить" />
</FORM>
</p>