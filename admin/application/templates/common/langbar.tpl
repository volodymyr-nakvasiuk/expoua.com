<div id="languages" class="relative">
	<div id="lang-list" class="list">
	{if $selected_language == 'en'}
		<span class="lang current english"><i class="flag"><!-- --></i><u class="t">English</u><i class="arrow"><!-- --></i></span> 
		<a class="lang inactive russian" href="{getUrl add=1 language="ru"}"><i class="flag"><!-- --></i><u class="t">Русский</u></a> 
	{ /if }
	{if $selected_language == 'ru'}
		<span class="lang current russian"><i class="flag"><!-- --></i><u class="t">Русский</u><i class="arrow"><!-- --></i></span> 
		<a class="lang inactive english" href="{getUrl add=1 language="en"}"><i class="flag"><!-- --></i><u class="t">English</u></a> 
	{/if}
	</div>
</div>
{literal}
<script type="text/javascript">
<!--
$(document).ready(function(){
	$("#lang-list .current").click(function(){
		$("#lang-list").toggleClass("list-open");
	});
	$(document).click(function(){
		$("#lang-list").removeClass("list-open");
	});
	$("#lang-list").click(function(e){
		e.stopPropagation();
	});
});
//-->
</script>
{/literal}				
