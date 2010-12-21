<h4>Журнал работы координаторов</h4>

<SCRIPT type="text/javascript">
{literal}

function setFilter() {
	var baseUrl = '{/literal}{getUrl controller="admin_system_logcoordinators" action="list"}{literal}';
	var search = '';

	if ($('#date_from').val() != "") {
		search += 'date_from>' + $('#date_from').val() + ';';
	}
	if ($('#date_to').val() != "") {
		search += 'date_to<' + $('#date_to').val() + ';';
	}
	if ($('#acl_admin_users_id').val() != "") {
		search += 'acl_admin_users_id=' + $('#acl_admin_users_id').val() + ';';
	}
	if ($('#acl_resources_id').val() != "") {
		search += 'acl_resources_id=' + $('#acl_resources_id').val() + ';';
	}
	if ($('#acl_resources_actions_id').val() != "") {
		search += 'acl_resources_actions_id=' + $('#acl_resources_actions_id').val();
	}

	if (search != "") {
		document.location.href = baseUrl + 'search/' + search + '/';
	}
}

$(document).ready(function(){
	$('#date_from').datepicker();
	$('#date_to').datepicker();
});
{/literal}
</SCRIPT>

{include file="common/Lists/generalFilterDescription.tpl"}

<b>Фильтр:</b><br />
с даты: <INPUT type="text" id="date_from" size="12" value="{$search_params.date_from}" />
по дату: <INPUT type="text" id="date_to" size="12" value="{$search_params.date_to}" /><br />
пользователь:
<select id="acl_admin_users_id">
 <option value="">(Все)</option>
{foreach from=$list_users item="el"}
 <option value="{$el.id}"{if $search_params.acl_admin_users_id == $el.id} selected="selected"{/if}>{$el.login}</option>
{/foreach}
</select>
модуль:
<select id="acl_resources_id" style="width:200px;">
 <option value="">(Все)</option>
 {foreach from=$list_resources item="el"}
  <option value="{$el.id}"{if $search_params.acl_resources_id == $el.id} selected="selected"{/if}>{$el.description} ({$el.code})</option>
 {/foreach}
</select>
действие:
<select id="acl_resources_actions_id">
 <option value="">(Все)</option>
 <option value="5"{if $search_params.acl_resources_actions_id == 5} selected="selected"{/if}>Добавление (add)</option>
 <option value="4"{if $search_params.acl_resources_actions_id == 4} selected="selected"{/if}>Обновление (update)</option>
 <option value="6"{if $search_params.acl_resources_actions_id == 6} selected="selected"{/if}>Удаление (delete)</option>
</select>
<br />
<INPUT type="button" value="Установить" onclick="setFilter();" />

{if $list.pages > 500}
	{include file="common/generalPaging.tpl" pages=500 page=$list.page}
{else}
	{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}
{/if}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 <th width="70" align="center">Дата</th>
 <th align="center">Модуль</th>
 <th align="center">Действие</th>
 <th align="center">Параметр</th>
 <th align="center">Координатор</th>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.date_event}</TD>
  <TD>{$el.code_resource}</TD>
  <TD>{$el.code_action}</TD>
  <TD align="center">{$el.param_id}</TD>
  <TD align="center">{$el.admin_login}</TD>
 </TR>
{/foreach}
</TABLE>
<b>Всего записей: </b> {$list.rows}