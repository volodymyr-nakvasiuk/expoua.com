<table cellspacing="0" class="list">
<tr valign="middle">
	<td><a href="{getUrl add=1 controller="sab_organizer_info" action="whatis"}" class="link{if $user_params.action=='whatis'} current{/if}">{#menuWhatIs#}</a></td>
	<td><a href="{getUrl add=1 controller="sab_organizer_info" action="websites"}" class="link{if $user_params.action=='websites'} current{/if}">{#menuWebSitesList#}</a></td>
	<td><a href="{getUrl add=1 controller="sab_organizer_info" action="stats"}" class="link{if $user_params.action=='stats'} current{/if}">{#menuStats#}</a></td>
	{if $selected_language == 'ru'}
	<td><a href="{getUrl add=1 controller="sab_organizer_info" action="terms"}" class="link{if $user_params.action=='terms'} current{/if}">{#menuTerms#}</a></td>
	{/if}
</tr>
</table>