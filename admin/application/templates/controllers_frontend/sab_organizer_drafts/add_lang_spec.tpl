{foreach from=$list_languages item="lang"}
<div id="data_bottom_{$lang.code}">
<TABLE border="0" width="600" style="border-collapse:collapse;" cellpadding="5">
{* <TR>
  <TD class="wb" valign="top" width="200">{#eventNumber#}:</TD>
  <TD>
	 <INPUT type="text" size="5" name="{$lang.code}[number]" id="number_{$lang}" /><BR />
  </TD>
 </TR> *}
 <TR>
  <TD class="wb" valign="top" width="200">{#workingHours#}:</TD>
  <TD>
	 <TEXTAREA name="{$lang.code}[work_time]" style="width:99%; height:50px;" id="work_time_{$lang.code}"></TEXTAREA><BR />
  </TD>
 </TR>
 <TR>
  <TR>
 </Table>

 <TABLE border="0" width="100%" style="border-collapse:collapse;" cellpadding="5">
  <tr><TD width="200" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="wb">{#exhibitionSectors#}: </td>
		</tr>
	</table>
  	<BR /><BR />
  </TD>
 	<TD style="padding-left:20px;"><textarea name="{$lang.code}[thematic_sections]" id="thematic_sections_{$lang.code}" style="width:99%; height:250px;"></textarea></TD>
  </TR>

  <TR>
  <TD width="200" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="wb">{#desc#}: </td>
		</tr>
	</table>
  	<BR /><BR />
  </TD>
 <TD style="padding-left:20px;"><textarea name="{$lang.code}[description]" id="description_{$lang.code}" style="width:99%; height:250px;"></textarea></TD>
 </TR>
</TABLE>
</div>
 {/foreach}
