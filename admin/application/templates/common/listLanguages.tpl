{foreach from=$list_languages item="el"}
  <a href="{getUrl add="1" language=$el.code}" class="{if $el.code==$selected_language}current_lang{else}lang{/if}">{$el.name}</a>
{/foreach}
