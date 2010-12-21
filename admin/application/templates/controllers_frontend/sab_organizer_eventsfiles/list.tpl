{if empty($list.data)}
  <p>{#msgNoFiles#}</p>
{else}
  {include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

  <table border="0" width="100%" class="list">
  <tr>
  <th align="center">N</th>
  {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="id" descr=#captionId#}
  {include file="common/Lists/headerElementGeneral.tpl" align="center" name="events_id"     descr=#captionEventId#}
  {include file="common/Lists/headerElementGeneral.tpl" align="center" name="name"          descr=#captionFileName#}
  {include file="common/Lists/headerElementGeneral.tpl" align="center" name="size"          descr=#captionFileSize#}
  {include file="common/Lists/headerElementGeneral.tpl" align="center" name="brand_name"    descr=#captionBrand#}
  <th align="center" colspan="3">{#actions#}</th>
  </tr>

  {assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
  {assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
  {foreach from=$list.data item="el" name="fe"}
  <tr class="{cycle values="odd,even"}">
    <td align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</td>
    <td align="center">{$el.id}</td>
    <td align="center">{$el.events_id}</td>
    <td>{$el.name}</td>
    <td align="center">{$el.size}</td>
    <td align="center">{$el.brand_name}</td>
  {include file="common/Actions/general.tpl" el=$el}
  </tr>
  {/foreach}
  </table>
{/if}

{if isset($user_params.event_id)}
  <form method="post" action="{getUrl add="1" action="insert"}" enctype="multipart/form-data">
    <input type="hidden" name="events_id" value="{$user_params.event_id}" />

    <table width="400" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td class="wb">{#captionFileName#}:</td>
      <td><input type="text" name="name" size="30" value="Default file name"></td>
    </tr>
    <tr>
      <td class="wb">{#captionFile#}:</td>
      <td><input type="file" name="upload_file"></td>
    </tr>
    <tr>
      <td colspan="2"><input type="submit" value=" {#uploadAction#} "></td>
    </tr>
    </table>

  </form>

  <p><a href="{getUrl controller="sab_organizer_brandsevents" action="edit" id=$user_params.event_id}">{#linkBackToList#}</a></p>
{/if}