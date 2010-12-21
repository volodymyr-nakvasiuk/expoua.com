{config_load file=global.conf}{config_load file=`$user_params.controller`.conf}<html>
<head>
  <title>Exhibition Global Marketing System</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="{$document_root}css/admin/expopromoter_style.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="{$document_root}css/admin/org_style.css" type="text/css" media="screen" />
  <script type="text/javascript" src="{$document_root}js/adminGeneral.js"></script>
  <script type="text/javascript" src="{$document_root}js/jquery.js"></script>

  <script>
  {literal}
    $(document).ready(function() {
      changeLanguage('{/literal}{$selected_language}{literal}');
    });

    function changeLanguage(code) {
      {/literal}{foreach from=$list_languages item="lang"}
      $("#data_top_{$lang.code}, #data_bottom_{$lang.code}").css("display", "none");
      {/foreach}{literal}
      $("#data_top_" + code + ", #data_bottom_" + code).css("display", "block");

      $("#langbar td").removeClass('active');
      $("#langbar #langtab-" + code).addClass('active');
    }
    {/literal}
  </script>
</head>

<body>

{include file="common/editor-langbar.tpl"}

{foreach from=$list_languages item="lang"}
<table border="0" width="100%" style="border-collapse:collapse;" cellpadding="5" id="data_top_{$lang.code}">
<tr>
  <td>Название выставки:</td>
  <td>{$data[$lang.code].brand_name_new}</td>
</tr>

<tr>
  <td valign="top">Расширеное название выставки:</td>
  <td>{$data[$lang.code].brand_name_extended_new|nl2br}</td>
</tr>
</table>
{/foreach}

<table border="0" width="100%" style="border-collapse:collapse;" cellpadding="5">
<tr>
  <td>Главная тематика выставки:</td>
  <td>{$data.brand_categories_name}</td>
</tr>
<tr>
  <td>Город:</td>
  <td>{$data.city_name}</td>
</tr>
<tr>
  <td>Выставочный центр:</td>
  <td>{$data.expocenter_name}</td>
</tr>
<tr>
  <td>Даты проведения:</td>
  <td>с {$data.common.date_from} по {$data.common.date_to}</td>
</tr>
<tr>
  <td>Период проведения:</td>
  <td>{$data.period_name}</td>
</tr>

<tr>
  <td colspan="2" valign="top">
    <table style="border-collapse:collapse; margin-right:50px;" align="left">
    <tr>
      <th align="left" colspan="2">Дополнительная информация о событии</th>
    </tr>
    <tr>
      <td>Участников всего:</td>
      <td><strong>{$data.common.partic_num}</strong></td>
    </tr>
    <tr>
      <td>Местных участников:</td>
      <td><strong>{$data.common.local_partic_num}</strong></td>
    </tr>
    <tr>
      <td>Иностранных участников:</td>
      <td><strong>{$data.common.foreign_partic_num}</strong></td>
    </tr>
    <tr>
      <td>Общая площадь:</td>
      <td><strong>{$data.common.s_event_total}</strong></td>
    </tr>
    <tr>
      <td>Посетителей всего:</td>
      <td><strong>{$data.common.visitors_num}</strong></td>
    </tr>
    <tr>
      <td>Местных посетителей:</td>
      <td><strong>{$data.common.local_visitors_num}</strong></td>
    </tr>
    <tr>
      <td>Иностранных посетителей:</td>
      <td><strong>{$data.common.foreign_visitors_num}</strong></td>
    </tr>
    </table>

    <table style="border-collapse:collapse;">
    <tr>
      <th align="left" colspan="2">Контакты проектной группы</th>
    </tr>
    <tr>
      <td>Email: </td>
      <td><strong>{$data.common.email}</strong></td>
    </tr>
    <tr>
      <td>Адрес сайта: </td>
      <td><strong>{$data.common.web_address}</strong></td>
    </tr>
    <tr>
      <td>Телефон: </td>
      <td><strong>{$data.common.phone}</strong></td>
    </tr>
    <tr>
      <td>Факс: </td>
      <td><strong>{$data.common.fax}</strong></td>
    </tr>
    <tr>
      <td>Контактное лицо: </td>
      <td><strong>{$data.common.cont_pers_name}</strong></td>
    </tr>
    <tr>
      <td>Телефон контактного лица:</td>
      <td><strong>{$data.common.cont_pers_phone}</strong></td>
    </tr>
    <tr>
      <td>Email контактного лица:</td>
      <td><strong>{$data.common.cont_pers_email}</strong></td>
    </tr>
    </table>
  </td>
</tr>
</table>

{foreach from=$list_languages item="lang"}
<table border="0" width="100%" style="border-collapse:collapse;" cellpadding="5" id="data_bottom_{$lang.code}">
<tr>
  <td valign="top" width="200">Номер события:</td>
  <td><strong>{$data[$lang.code].number}</strong></td>
</tr>

<tr>
  <td valign="top" width="200">Время работы:</td>
  <td><strong>{$data[$lang.code].work_time|nl2br}</strong></td>
</tr>

<tr>
  <td colspan="2">
    <p>Разделы выставки:</p>
    <div style="padding:10px; border:1px solid #CF3752;">{$data[$lang.code].thematic_sections|nl2br}</div>
  </td>
</tr>

<tr>
  <td colspan="2">
    <p>{#desc#}:</p>
    <div style="padding:10px; border:1px solid #CF3752;">{$data[$lang.code].description|nl2br}</div>
  </td>
</tr>
</table>
{/foreach}


<table border="0" width="100%" style="border-collapse:collapse;">
<tr>
  <td align="center">
    <input type="button" value=" {#closeAction#} " onClick="window.close();" />
  </td>
</tr>
</table>

</body>
</html>
