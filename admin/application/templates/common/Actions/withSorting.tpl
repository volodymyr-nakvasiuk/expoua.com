{if !empty($session_user_allow[$user_params.controller].position)}
 <TD width="20" align="center">
  {if !$isFirst or $list.page>1}
   <a href="{getUrl add="1" action="position" direction="up" id=$el.id del="sort"}"><img alt="{#upAction#}" src="{$document_root}images/admin/icons/page_up.gif" border="0" width="16"></a>
  {/if}
 </TD>
 <TD width="20" align="center">
  {if !$isLast or ($list.pages>$list.page)}
   <a href="{getUrl add="1" action="position" direction="down" id=$el.id del="sort"}"><img alt="{#downAction#}" src="{$document_root}images/admin/icons/page_down.gif" border="0" width="16"></a>
  {/if}
 </TD>
{/if}
{if !empty($session_user_allow[$user_params.controller].update)}
 <TD width="20" align="center"><a href="{getUrl add="1" action="edit" id=$el.id}"><img alt="{#editAction#}" src="{$document_root}images/admin/icons/page_edit.gif" border="0" width="16"></a></TD>
{/if}
{if !empty($session_user_allow[$user_params.controller].delete)}
 <TD width="20" align="center"><a href="{getUrl add="1" action="delete" id=$el.id}" onclick="return Shelby_Backend.confirmDelete();"><img alt="{#deleteAction#}" src="{$document_root}images/admin/icons/page_delete.gif" border="0" width="16"></a></TD>
{/if}