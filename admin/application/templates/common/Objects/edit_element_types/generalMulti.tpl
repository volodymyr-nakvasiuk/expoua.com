<h4>{#hdrComplexElementEdit#}</h4>

<FORM action="{getUrl add="1" action="update"}" method="post">
<DIV style="border:1px solid #000000">
{#captionElementName#}: <INPUT type="text" name="name" value="{$entry.name}"><BR />
<INPUT type="submit" value="{#updateAction#}">
</DIV>
</FORM>

<p>
<FORM method="post" action="{getUrl add="1" action="update"}">
<DIV style="border:1px solid #000000">
{#captionElementsSet#}:<br />
{foreach from=$list_element_names.data item="el"}
 {$el.id}. <INPUT type="text" size="40" value="{$el.name}" name="multi_name[{$el.id}]"><br />
{/foreach}
<INPUT type="submit" value="{#updateAction#}">
</DIV>
</FORM>
</p>

<p>
<FORM method="post" action="{getUrl add="1" action="insertName"}">
<DIV style="border:1px solid #000000">
{#captionAddNewElement#}:<br />
<INPUT type="text" size="40" name="name"><br />
<INPUT type="submit" value="{#addAction#}">
</DIV>
</FORM>
</p>

<p><a href="{getUrl controller="admin_cms_objects_elements" action="list" parent=$entry.parent_id}">{#linkBackToList#}</a></p>