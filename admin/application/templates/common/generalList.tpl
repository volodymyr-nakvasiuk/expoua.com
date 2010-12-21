<h4>Автогенерируемый список</h4>

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
{assign var="end" value=0}
{foreach from=$list.data item="element"}
 {if $end ==0}
  {assign var="end" value=1}
  {foreach from=$element item="el" key="el_key"}
   {if $el_key!="content"}
    <TH width="50" align="center">
     <a href="{getUrl add="1" sort=$HMixed->getSortOrder($el_key, $list.sort_by)}">{$el_key}</a>
     <DIV style="float:right; cursor:pointer;" onclick="Shelby_Backend.toggle_search('{$el_key}');">S</DIV>
     <DIV style="clear:both; display:none;" align="center" id="list_header_search_div_{$el_key}">
      <FORM method="post" onsubmit="Shelby_Backend.table_header_search('{getUrl add="1" del="search,page"}', '{$el_key}'); return false;">
       <INPUT type="text" style="width:90%;" id="list_header_search_kw_{$el_key}">
      </FORM>
     </DIV>
    </TH>
   {/if}
  {/foreach}
  <TH align="center" colspan="3" width="16">Действия</TH>
 {/if}
{/foreach}
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="element" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">
   {assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}
   {$npp}
  </TD>
 {foreach from=$element item="el" key="el_key"}
  {if $el_key=='active'}
     <td align="center"><FORM method="post" action="{getUrl add="1" action="update" id=$element.id}" style="padding:0px; margin:0px;">
   <INPUT type="checkbox" {if $el==1} checked{/if} onclick="this.form.submit();">
   <INPUT type="hidden" name="active" value="{if $el==1}0{else}1{/if}">
   </FORM></td>
  {elseif $el_key!="content"}
   <TD align="center">{$el}</TD>
  {/if}
 {/foreach}
 {include file="common/Actions/general.tpl" el=$element}
 </TR>
{/foreach}
</TABLE>

<p>
<h4>Добавляем новую запись</h4>
<FORM method="post" action="{getUrl add="1" action="insert"}">
<INPUT type="hidden" name="active" value="1">
{assign var="end" value=0}
{foreach from=$list.data item="element"}
 {if $end ==0}
  {assign var="end" value=1}
  {foreach from=$element item="el" key="el_key"}
   {if $el_key!='id' && $el_key!='parent'}
    {$el_key}: <INPUT type="text" size="20" name="{$el_key}"><br />
   {/if}
  {/foreach}
 {/if}
{/foreach}
<INPUT type="submit" value="Добавить">
</FORM>
</p>