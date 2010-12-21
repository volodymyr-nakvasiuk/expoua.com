<table cellspacing="0" class="list">
<tr valign="middle">
	<td><a href="{getUrl add=1 controller="sab_tourpartner_info" action="whatis"}" class="link{if $user_params.action=='whatis'} current{/if}">{#menuWhatIs#}</a></td>
	<td><a href="{getUrl add=1 controller="sab_tourpartner_info" action="howitworks"}" class="link{if $user_params.action=='howitworks'} current{/if}">{#menuHowItWorks#}</a></td>
	<td><a href="{getUrl add=1 controller="sab_tourpartner_info" action="stats"}" class="link{if $user_params.action=='stats'} current{/if}">{#menuStats#}</a></td>
	<td><a href="{getUrl add=1 controller="sab_tourpartner_info" action="test"}" class="link{if $user_params.action=='test'} current{/if}">{#menuTestAccess#}</a></td>
</tr>
</table>