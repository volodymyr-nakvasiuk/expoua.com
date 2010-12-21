<table cellspacing="0" class="list">
<tr valign="middle">
	<td><a href="{getUrl add=1 controller="sab_partner_info" action="main"}" class="link{if $user_params.action=='main'} current{/if}">{#menuMain#}</a></td>
	<td><a href="{getUrl add=1 controller="sab_partner_info" action="tradeshowswidget"}" class="link{if $user_params.action=='tradeshowswidget'} current{/if}">{#menuTradeShowsWidget#}</a></td>
	<td><a href="{getUrl add=1 controller="sab_partner_info" action="tradeshowssyndication"}" class="link{if $user_params.action=='tradeshowssyndication' || $user_params.action=='description' || $user_params.action=='payments' || $user_params.action=='becomepartner'} current{/if}">{#menuTradeShowsSyndication#}</a></td>
	<td><a href="{getUrl add=1 controller="sab_partner_info" action="customtradeshowsintegration"}" class="link{if $user_params.action=='customtradeshowsintegration'} current{/if}">{#menuCustomTradeShowsIntegration#}</a></td>
</tr>
</table>