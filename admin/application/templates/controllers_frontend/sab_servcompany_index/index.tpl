<!--<H4 align="center">Servcompany index.tpl</H4>-->

<CENTER>
<div style="width:700px;" align="left">

{assign var="page" value=$HCms->getEntry(32)}
{* $HMixed->dump($page) *}
{$page.content}


</div>
</center>