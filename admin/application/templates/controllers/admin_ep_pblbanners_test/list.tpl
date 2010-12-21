<h4>Просмотр рекламных объявлений для заданных условий</h4>

<script type="text/javascript">
{literal}
  $(function() {
    $("form[name=selector] select").change(
      function() {
        var url = $("#url").val();
        var category = $("#category").val();
        
        if (category) url += 'category/' + category + '/';
        
        location.href = url;
        
        return false;
      }
    );
  });
{/literal}
</script>

<form name="selector" action="{getUrl add=1 del='country,category'}" method="get">
<input type="hidden" id="url" name="url" value="{getUrl add=1 del='country,category'}" />
  <p>
    <select id="category" name="category">
      <option value="">Все страницы</option> 
      {foreach from=$categories_list.data item=el}
      <option value="{$el.id}"{if $category == $el.id} selected="selected"{/if}>{$el.name}</option>
      {/foreach}
    </select>
  </p>
</form>

<table width="380" cellpadding="0" cellspacing="5">

{foreach from=$banners_list item=entry}
<tr valign="top">
  <td width="250">
    <div class="banner-wrapper" style="margin:0;">
      <a target="_blank" href="{$entry.url}">
        <span class="banner-txt-image"><img border="0" alt="{$entry.file_alt}" src="http://62.149.12.130/bn/pbldata/{$entry.file_name}?{1|mt_rand:10000}"/></span>
        <span class="banner-txt-header">{$entry.file_alt}</span>
        <span class="banner-txt-content">{$entry.text_content}</span>
      </a>
    </div>
  </td>
  
  <td>{if is_numeric($entry.price)}Цена: ${/if}{$entry.price}</td>
</tr>
{/foreach}
</table>
