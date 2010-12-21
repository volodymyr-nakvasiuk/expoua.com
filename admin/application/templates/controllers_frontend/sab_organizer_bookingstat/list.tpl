{if empty($list.data)}
	<br/><br/><center>{#bookingNoStats#}</center>
{else}
<table border="0" width="100%" class="list">
<tr>
  <th align="center" width="90">{#bookingTitleDay#}</th>
  <th align="center">{#title#}</th>
  <th align="center" width="90">{#dates#}</th>
  <th align="center">{#bookingTitleBookingsCnt#}</th>
  <th align="center">{#mnuPartnerIncome#}</th>
</tr>

{foreach from=$list.data item="el" name="fe"}
<tr class="{cycle values="odd,even"}">
  <td align="center">{$el.date_cut}</td>
  <td>{$el.brand_name}&nbsp;</td>
  <td align="center">{$el.date_from}<br />{$el.date_to}</td>
  <td align="center">{$el.cnt}&nbsp;</td>
  <td align="center">{$el.comission}&nbsp;</td>
</tr>
{/foreach}
</table>
{/if}