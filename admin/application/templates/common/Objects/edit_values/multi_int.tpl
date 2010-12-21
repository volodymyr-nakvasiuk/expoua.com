<TABLE border="0">
{foreach from=$elements item="element"}
 <tr><td align="right">{$element.name}:</td><td><INPUT type="text" name="object_value[{$element.id}]" value="{$element.value}" size="10"></td></tr>
{/foreach}
</TABLE>
