  {foreach from=$list_objects item="object"}
   <DIV style="padding-bottom:5px;{if $object.main==0}visibility:hidden; display:none;{/if}" name="objects_hidden">
   <b>{$object.name}</b>:
   {if $object.multi==1}
    <DIV style="padding-left:5px;">
     {if $object.type=="int"}
      {include file="common/Objects/edit_values/multi_int.tpl" elements=$object.elements}
     {elseif $object.type=="boolean"}
      {include file="common/Objects/edit_values/multi_boolean.tpl" elements=$object.elements}
     {else}
      {include file="common/Objects/edit_values/multi_text.tpl" elements=$object.elements}
     {/if}
    </DIV>
   {else}
    {if $object.type=="int"}
     {include file="common/Objects/edit_values/single_int.tpl" element=$object.elements}
    {elseif $object.type=="boolean"}
     {include file="common/Objects/edit_values/single_boolean.tpl" element=$object.elements}
    {else}
     {include file="common/Objects/edit_values/single_text.tpl" element=$object.elements}
    {/if}
   {/if}
   </DIV>
  {/foreach}
