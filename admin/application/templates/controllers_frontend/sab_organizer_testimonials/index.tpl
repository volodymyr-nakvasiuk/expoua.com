<table width="100%" cellpadding="0" cellspacing="0">
<tr valign="top">
  <td style="padding-right:20px;">
    {assign var="page" value=$HCms->getEntry(99)}
    {* $HMixed->dump($page) *}
    {$page.content}
  </td>
  
  <td style="padding-left:10px; width:210px; border-left:1px solid #B3C4D8;">
    <h1>{#hdrPageTitle1#}</h1>
    
    {if $smarty.post.testimonial}
    
      <p>{#msgTestimonialSent#}</p>
    
    {else}
    
    <form method="post" action="{getUrl add="1" action="index"}">
      <p>{#msgTestimonials#}</p>
    
      <textarea name="testimonial" style="width:100%; height:100px;"></textarea>
    
      <p><input type="submit" value=" {#sendAction#} "/></p>
    </form>
    
    {/if}
  </td>
</tr>
</table>