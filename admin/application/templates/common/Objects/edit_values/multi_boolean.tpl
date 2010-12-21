<TABLE border="0">
 {foreach from=$elements item="element"}
  <tr><td align="right">
   {$element.name}:
  </td><td>
   <INPUT type="checkbox" name="object_value[{$element.id}]"{if $element.value==1} checked{/if} onclick="Shelby_Backend.objects_multi_checkbox('object_value_{$element.id}', this.checked);">
   <INPUT type="hidden" id="object_value_{$element.id}" name="object_value[{$element.id}]" value="{if $element.value==1}1{else}0{/if}">
  </td></tr>
 {/foreach}
</TABLE>
