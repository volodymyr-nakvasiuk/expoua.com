<link rel="stylesheet" href="http://www.expopromoter.com/css/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="http://www.expopromoter.com/js/thickbox.js"></script>

<p style="border:1px solid #900; background:#FDEBEA; padding:10px;">{#disclaimer#}</p>

{if empty($list.data)}
<p><br />{#msgListEmpty#}</p>

{/if}

<FORM method="post" enctype="multipart/form-data" action="{getUrl add=1 action="insert"}">
  <div style="margin:10px 0;">
    <INPUT type="file" name="image" />
    <INPUT type="submit" value=" {#uploadAction#} " />
    <INPUT type="hidden" name="dummy" value="0" />
  </div>
</FORM>

<p>&nbsp;</p>

<table cellspacing="4" width="95%">
<tr valign="bottom">
{foreach name="ilist" from=$list.data item="el"}
  <td width="20%" align="center" style="background:#efefef; padding:5px;">
    <p><a href="/data/images/servcomps/{$el.servcomps_id}/gallery/{$el.id}.jpg" class="thickbox" rel="gallery" target="_blank" title="">
      <img src="/data/images/servcomps/{$el.servcomps_id}/gallery/{$el.id}_tb.jpg" class="tb"/></a></p>
    <p><a href="{getUrl add="1" action="delete" id=$el.id}" onclick="return Shelby_Backend.confirmDelete();">
      <img title="{#deleteAction#}" src="/images/admin/icons/delete.gif" border="0" width="16"></a></p>
  </td>
  {if $smarty.foreach.ilist.iteration % 5 == 0 && !$smarty.foreach.ilist.last}</tr><tr valign="bottom">{/if}
{/foreach}
</tr>
</table>

