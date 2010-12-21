{if !empty($session_user_allow[$user_params.controller].view)}
<TD width="20" align="center"><a href="{getUrl add="1" action="view" id=$el.id}"><img alt="{#previewAction#}" src="{$document_root}images/admin/icons/page_text.gif" border="0" width="16"></a></TD>
{/if}
{if !empty($session_user_allow[$user_params.controller].update)}
<TD width="20" align="center"><a href="{getUrl add="1" action="edit" id=$el.id}"><img title="{#editAction#}" src="{$document_root}images/admin/list-edit.gif" border="0" width="23" height="21"></a></TD>
{/if}
{if !empty($session_user_allow[$user_params.controller].delete)}
<TD width="20" align="center"><a href="{getUrl add="1" action="delete" id=$el.id}" onclick="return Shelby_Backend.confirmDelete();"><img alt="{#deleteAction#}" src="{$document_root}images/admin/icons/delete.gif" border="0" width="16"></a></TD>
{/if}