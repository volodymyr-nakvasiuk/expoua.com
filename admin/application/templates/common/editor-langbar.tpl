<div class="box box-noborders tabs-holder relative">
	<ul class="tabs">
		{foreach from=$list_languages item="lang"}
		<li class="item{if $lang.code==$selected_language} current{/if}"><a href="javascript:changeLanguage('{$lang.code}');" class="tab"><ins class="t">{$lang.name}</ins></a></li>
		{/foreach}
	</ul>
	<div class="contents" style="height:0; border-width:1px 0 0;"><!-- --></div>
	<script type="text/javascript">
	<!--{literal}
	$.fn.minitabs = function() {
		return this.each(function(){
			var $this = $(this);
			var $tabs = $this.find('.tabs').children();
			var $contents = $this.find('.contents').children();
			var selected = 0;
			$tabs.each(function(index){
				var $tab = $(this);
				if ($tab.hasClass('current'))
					selected = index;
				$tab.find('.tab').click(function(e){
					$tabs.eq(selected).removeClass('current');
					$contents.eq(selected).hide();
					selected = index;
					$tabs.eq(selected).addClass('current');
					$contents.eq(selected).show();
					
				});			
			});
		});
	}
	$(document).ready(function(){ $('.tabs-holder').minitabs(); });
	{/literal}//-->
	</script>
</div>