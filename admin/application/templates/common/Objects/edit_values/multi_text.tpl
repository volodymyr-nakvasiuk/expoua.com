<TABLE border="1">
{foreach from=$elements item="element"}
 <tr><td align="right">{$element.name}:</td><td><INPUT type="text" name="object_value[{$element.id}]" value="{$element.value}" size="20"></td></tr>
{/foreach}
</TABLE>
