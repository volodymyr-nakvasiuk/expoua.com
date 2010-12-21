{if isset($session_user_allow.sab_organizer_requests) && isset($session_user_allow.sab_organizer_reqemails)}
<TABLE width="90%" align="center">
 <TR>
  <TD align="center" width="50%"><a href="{getUrl controller="sab_organizer_reqemails" action="list"}">{#top_menu1#}</a></TD>
  <TD align="center" width="50%"><a href="{getUrl controller="sab_organizer_requests" action="list"}">{#top_menu2#}</a></TD>
 </TR>
</TABLE>
{/if}