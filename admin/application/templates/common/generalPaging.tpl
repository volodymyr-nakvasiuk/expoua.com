{if $pages>1}
{strip}
<div align="center" style="padding-bottom:5px;">
{assign var="start" value=0}
{if ($page>20)}
	{assign var="start" value="`$page-10`"}
{/if}

{if $start>0}
 -&nbsp;
 {section name=scroll loop=$start step=10}
	{assign var="tmp" value=$smarty.section.scroll.index+1}
	<a href="{getUrl add="1" page=$tmp}" class="{if $tmp!=$page}page{else}current_page{/if}">{$tmp}</a> -&nbsp;
 {/section}
{/if}

-&nbsp;
{section name=scroll loop=$pages start=$start max=20}
	{assign var="tmp" value=$smarty.section.scroll.index+1}
	<a href="{getUrl add="1" page=$tmp}" class="{if $tmp!=$page}page{else}current_page{/if}">{$tmp}</a> -&nbsp;
{/section}

{if ($start+20)<$pages}
 -&nbsp;
 {section name=scroll loop=$pages start="`$start+20`" step=10}
	{assign var="tmp" value=$smarty.section.scroll.index+1}
	<a href="{getUrl add="1" page=$tmp}" class="{if $tmp!=$page}page{else}current_page{/if}">{$tmp}</a> -&nbsp;
 {/section}
 {if $tmp!=$pages}
  <a href="{getUrl add="1" page=$pages}" class="page">{$pages}</a> -
 {/if}
{/if}

</div>
{/strip}
{/if}