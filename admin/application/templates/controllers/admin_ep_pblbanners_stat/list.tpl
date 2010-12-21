<script type="text/javascript">
{literal}
  $(function() {
    $("#period_form").submit(function() {
      var url    = $("#period_form_url").val();

      var period_start = $("#period_start").val();
      var period_end = $("#period_end").val();
      var filter_str = $("#filter_part").val();

      if (period_start || period_end) {
        url += 'search/' + filter_str;

        if (period_start) url += 'date_start>=' + period_start;
        if (period_end)   url += (period_start ? ';' : '') + 'date_end<' + period_end;
      }

      location.href = url + '/';

      return false;
    });

    $('.datepick').datepicker();
  });
{/literal}
</script>

<h4>Статистика по рекламным объявлениям</h4>

<form id="period_form" action="" method="get">
  <input type="hidden" id="period_form_url" name="url" value="{getUrl add=1 del='search,page'}" />
  <input type="hidden" id="filter_part" name="filter" value="{foreach from=$filter key=k item=i}{$k}{$i};{/foreach}" />
  <table cellspacing="2">
  <tr>
    <td>Начало периода: </td>
    <td><input type="text" id="period_start" class="datepick" value="{$date_start}" />&nbsp;&nbsp;</td>
    <td>Конец периода: </td>
    <td><input type="text" id="period_end" class="datepick" value="{$date_end}" />&nbsp;&nbsp;</td>
    <td><button type="submit"> Выбрать </button></td>
  </tr>
  </table>
</form>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<table border="0" width="100%" class="list">
<tr>
  <th align="center">N</th>
  {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="id" descr="Id"}
  {include file="common/Lists/headerElementGeneral.tpl" align="left" name="name" descr="Название рекламного объявления"}
  {include file="common/Lists/headerElementGeneral.tpl" align="center" name="user_login" descr="Пользователь"}
  {include file="common/Lists/headerElementGeneral.tpl" align="center" name="language" descr="Язык" stype="~"}
  {include file="common/Lists/headerElementGeneral.tpl" align="center" name="country_name" descr="Страна" stype="~"}
  <th align="center">Показы</th>
  <th align="center">Клики</th>
  <th align="center">Стоимость</th>
  <th align="center" colspan="1">Действия</th>
</tr>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="element" name="fe"}

<tr class="{cycle values="odd,even"}">
  <td align="center">
    {assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}
    {$npp}
  </td>

  <td align="center">{$element.id}</td>
  <td align="left">{$element.name}</td>
  <td align="center">{$element.user_login}</td>
  <td align="center">{$element.language}</td>
  <td align="center">{$element.country_name|default:"&mdash;"}</td>
  <td align="center">{$element.shows}</td>
  <td align="center">{$element.clicks}</td>
  <td align="center">{$element.price}</td>

  {include file="common/Actions/general.tpl" el=$element}
</tr>
{/foreach}
<tr style="font-weight:bold">
  <td>&nbsp;</td>

  <td align="center">&nbsp;</td>
  <td align="left" colspan="4">ИТОГО:</td>
  <td align="center">{$list.total.shows}</td>
  <td align="center">{$list.total.clicks}</td>
  <td align="center">{$list.total.price}</td>

  <td align="center">&nbsp;</td>
</tr>
</table>


{if $smarty.get.debug}{debug}{/if}