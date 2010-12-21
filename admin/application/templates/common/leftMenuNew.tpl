<div style="width:160px;{if isset($smarty.cookies.shelby_left_menu) && $smarty.cookies.shelby_left_menu=="hide"}display:none;{/if}" id="shelby_left_menu">

<div class="leftbg">
  <div class="leftbg">
    <div class="leftbg" id="accordion">
{capture name="el"}{strip}
  {include file="common/menuItem.tpl" controller="admin_cms_pages" text="Редактируем контент"}
  {include file="common/menuItem.tpl" controller="admin_cms_news" text="Редактируем новости"}
  {include file="common/menuItem.tpl" controller="admin_cms_menus" text="Редактируем меню"}
  {include file="common/menuItem.tpl" controller="admin_cms_votes" text="Голосования"}
  {include file="common/menuItem.tpl" controller="admin_cms_galleries" text="Галереи" action="list"}
  {include file="common/menuItem.tpl" controller="admin_cms_objects" text="Дополнительные опции"}
{/strip}{/capture}

    {if !empty($smarty.capture.el)}
      <div class="leftMenuGroup"><ul><li>Редактируем контент</li></ul></div>

      <ul class="leftMenuSubElement">
        {$smarty.capture.el}
      </ul>
    {/if}

{capture name="el"}{strip}
  {include file="common/menuItem.tpl" controller="admin_ep_events" text="События"}
  {include file="common/menuItem.tpl" controller="admin_ep_brands" text="Бренды"}
  {include file="common/menuItem.tpl" controller="admin_ep_brandsevents" text="Бренд+Событие"}
  {include file="common/menuItem.tpl" controller="admin_ep_brandscategories" text="Категории брендов"}
  {include file="common/menuItem.tpl" controller="admin_ep_socorgs" text="Общественные организации"}
  {include file="common/menuItem.tpl" controller="admin_ep_organizers" text="Организаторы"}
  {include file="common/menuItem.tpl" controller="admin_ep_expocenters" text="Выставочные центры"}
  {include file="common/menuItem.tpl" controller="admin_ep_servicecomp" text="Сервисные компании"}
  {include file="common/menuItem.tpl" controller="admin_ep_servicecompcats" text="Категории сервисных компаний"}
  {include file="common/menuItem.tpl" controller="admin_ep_drafts" text="Черновики событий"}
  {include file="common/menuItem.tpl" controller="admin_ep_galleries_events" text="Галереи событий"}
{/strip}{/capture}

    {if !empty($smarty.capture.el)}
      <div class="leftMenuGroup"><ul><li>Управление базой выставок</li></ul></div>

      <ul class="leftMenuSubElement">
        {$smarty.capture.el}
      </ul>
    {/if}

{capture name="el"}{strip}
  {include file="common/menuItem.tpl" controller="admin_ep_ads_participants" text="Участники"}
  {include file="common/menuItem.tpl" controller="admin_ep_ads_tours" text="Туры"}
  {include file="common/menuItem.tpl" controller="admin_ep_ads_adverts" text="Объявления"}
  {include file="common/menuItem.tpl" controller="admin_ep_ads_messages" text="Сообщения объявлений"}
{/strip}{/capture}

    {if !empty($smarty.capture.el)}
      <div class="leftMenuGroup"><ul><li>Реклама на карточках выставок</li></ul></div>

      <ul class="leftMenuSubElement">
        {$smarty.capture.el}
      </ul>
    {/if}

{capture name="el"}{strip}
  {include file="common/menuItem.tpl" controller="admin_ep_companies_manage" text="Список компаний" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_companies_news" text="Новости" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_companies_services" text="Товары/услуги" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_companies_servicescats" text="Категории товаров/услуг" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_companies_sgalleries" text="Галлереи товаров/услуг" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_companies_employers" text="Сотрудники компаний" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_companies_messages" text="Сообщения компаниям" action="list"}
{/strip}{/capture}

    {if !empty($smarty.capture.el)}
      <div class="leftMenuGroup"><ul><li>Компании</li></ul></div>

      <ul class="leftMenuSubElement">
        {$smarty.capture.el}
      </ul>
    {/if}

{capture name="el"}{strip}
  {include file="common/menuItem.tpl" controller="admin_ep_banners_advertisers" text="Рекламодатели" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_banners_companies" text="Кампании" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_banners_plans" text="Планы показов" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_banners_banners" text="Баннера" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_banners_publishers" text="Издатели"}
  {include file="common/menuItem.tpl" controller="admin_ep_banners_places" text="Площадки" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_banners_stat" text="Статистика" action="list"}
  <li><hr/></li>
  {include file="common/menuItem.tpl" controller="admin_ep_pblbanners_users" text="Рекламодатели" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_pblbanners_banners" text="Объявления" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_pblbanners_bannersarchive" text="Архив" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_pblbanners_deleted" text="Удаленные" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_pblbanners_bannersgags" text="Заглушки" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_pblbanners_stat" text="Статистика баннеров" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_pblbanners_publishersstat" text="Статистика партнеров" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_pblbanners_clicksstat" text="Статистика кликов" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_pblbanners_test" text="Просмотр" action="list"}
{/strip}{/capture}

    {if !empty($smarty.capture.el)}
      <div class="leftMenuGroup"><ul><li>Баннера</li></ul></div>

      <ul class="leftMenuSubElement">
        {$smarty.capture.el}
      </ul>
    {/if}

{capture name="el"}{strip}
  {include file="common/menuItem.tpl" controller="admin_ep_operators_messages" text="Операторов/организаторов" count=$oq_list.data|@count}
  {include file="common/menuItem.tpl" controller="admin_ep_partners_messages" text="Веб-партнеров" count=$pq_list.data|@count}
  {include file="common/menuItem.tpl" controller="admin_ep_travel_messages"   text="Туркомпаний" count=$tq_list.data|@count}
  {include file="common/menuItem.tpl" controller="admin_ep_pblbanners_messages" text="Рекламодателей" count=$aq_list.data|@count}
{/strip}{/capture}

    {if !empty($smarty.capture.el)}
      <div class="leftMenuGroup"><ul><li{if $pq_list.data|@count || $tq_list.data|@count || $oq_list.data|@count || $aq_list.data|@count} style="color:#900"{/if}>Вопросы/Ответы</li></ul></div>

      <ul class="leftMenuSubElement">
        {$smarty.capture.el}
      </ul>
    {/if}


{capture name="el"}{strip}
  {include file="common/menuItem.tpl" controller="admin_ep_eventsparticipants" text="Участники"}
  {include file="common/menuItem.tpl" controller="admin_ep_newsparticipants" text="Новости участников"}
  {include file="common/menuItem.tpl" controller="admin_ep_comments" text="Комментарии"}
  {include file="common/menuItem.tpl" controller="admin_ep_news" text="Новости"}
  {include file="common/menuItem.tpl" controller="admin_ep_articles" text="Статьи"}
  {include file="common/menuItem.tpl" controller="admin_ep_locations_regions" text="Регионы/страны/города"}
  {include file="common/menuItem.tpl" controller="admin_ep_analyze" text="Анализаторы"}
{/strip}{/capture}

    {if !empty($smarty.capture.el)}
      <div class="leftMenuGroup"><ul><li>Разное</li></ul></div>

      <ul class="leftMenuSubElement">
        {$smarty.capture.el}
      </ul>
    {/if}

{capture name="el"}{strip}
  {include file="common/menuItem.tpl" controller="admin_files_download" text="Файлы для загрузки"}
  {include file="common/menuItem.tpl" controller="admin_files_images" text="Изображения для контента"}
  {include file="common/menuItem.tpl" controller="admin_files_flash" text="Flash для контента"}
  {include file="common/menuItem.tpl" controller="admin_ep_eventsfiles" text="Файлы на карточках выставок" action="list"}
{/strip}{/capture}

    {if !empty($smarty.capture.el)}
      <div class="leftMenuGroup"><ul><li>Файлы</li></ul></div>

      <ul class="leftMenuSubElement">
        {$smarty.capture.el}
      </ul>
    {/if}

{capture name="el"}{strip}
  {include file="common/menuItem.tpl" controller="admin_cms_templates_pages" text="Основные шаблоны" action="list"}
  {include file="common/menuItem.tpl" controller=""admin_cms_templates_news text="Шаблоны новостей" action="list"}
  {include file="common/menuItem.tpl" controller="admin_cms_templates_menus" text="Шаблоны меню" action="list"}
  {include file="common/menuItem.tpl" controller="admin_cms_templates_adds" text="Дополнительные шаблоны" action="list"}
  {include file="common/menuItem.tpl" controller="admin_cms_templates_modules" text="Шаблоны модулей" action="list"}
  {include file="common/menuItem.tpl" controller="admin_cms_templates_system" text="Системные шаблоны" action="list"}
{/strip}{/capture}

    {if !empty($smarty.capture.el)}
      <div class="leftMenuGroup"><ul><li>Управление шаблонами</li></ul></div>

      <ul class="leftMenuSubElement">
        {$smarty.capture.el}
      </ul>
    {/if}

{capture name="el"}{strip}
  {include file="common/menuItem.tpl" controller="admin_acl_admins" text="Пользователи админки"}
  {include file="common/menuItem.tpl" controller="admin_acl_admins_groups" text="Группы пользователей админки"}
  {include file="common/menuItem.tpl" controller="admin_acl_operators" text="Внештатные операторы"}
  {include file="common/menuItem.tpl" controller="admin_acl_organizers" text="Организаторы"}
  {include file="common/menuItem.tpl" controller="admin_acl_servcompany" text="Сервисные компании"}
  {include file="common/menuItem.tpl" controller="admin_acl_companies" text="Компании"}
  {include file="common/menuItem.tpl" controller="admin_ep_eventsusers" text="Пользователи событий"}
  {include file="common/menuItem.tpl" controller="admin_ep_eventsadsusers" text="Пользователи объявлений"}
  {include file="common/menuItem.tpl" controller="admin_acl_resources" text="Модули"}
{/strip}{/capture}

    {if !empty($smarty.capture.el)}
      <div class="leftMenuGroup"><ul><li>Управление доступом</li></ul></div>

      <ul class="leftMenuSubElement">
        {$smarty.capture.el}
      </ul>
    {/if}

{capture name="el"}{strip}
  {include file="common/menuItem.tpl" controller="admin_options_languages" text="Языки"}
  {include file="common/menuItem.tpl" controller="admin_options_constants" text="Конфигурационные константы"}
{/strip}{/capture}

    {if !empty($smarty.capture.el)}
      <div class="leftMenuGroup"><ul><li>Настройки</li></ul></div>

      <ul class="leftMenuSubElement">
        {$smarty.capture.el}
      </ul>
    {/if}

{capture name="el"}{strip}
  {include file="common/menuItem.tpl" controller="admin_ep_partners"    text="Веб-партнеры"}
  {include file="common/menuItem.tpl" controller="admin_ep_travel"      text="Туркомпании"}
  {include file="common/menuItem.tpl" controller="admin_ep_sites"       text="Зарегистрированные сайты" action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_siteusers"   text="Пользователи сайтов"      action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_subscribers" text="Подписчики"               action="list"}
  {include file="common/menuItem.tpl" controller="admin_ep_requests"    text="Запросы пользователей"}
{/strip}{/capture}

    {if !empty($smarty.capture.el)}
      <div class="leftMenuGroup"><ul><li>Экспорт</li></ul></div>
      <ul class="leftMenuSubElement">
        {$smarty.capture.el}
      </UL>
    {/if}

{capture name="el"}{strip}
  {include file="common/menuItem.tpl" controller="admin_system_logactions" text="Журнал работы пользователей" action="list"}
  {include file="common/menuItem.tpl" controller="admin_system_logoperators" text="Журнал работы операторов" action="list"}
  {include file="common/menuItem.tpl" controller="admin_system_backup" text="Резервное копирование"}
  {include file="common/menuItem.tpl" controller="admin_system_upgrade" text="Обновления"}
  {include file="common/menuItem.tpl" controller="admin_system_fulltext" text="Состояние поискового индекса"}
{/strip}{/capture}

 {if !empty($smarty.capture.el)}
 <DIV class="leftMenuGroup"><ul><li>Системные операции</li></ul></DIV>
 <UL class="leftMenuSubElement">
  {$smarty.capture.el}
 </UL>
 {/if}

</div></div></div>

</DIV>


<script type="text/javascript">
{literal}
  $(function() {
    $("#shelby_left_menu").tabs("#shelby_left_menu div.leftMenuSubElement", { 
      tabs: '.leftMenuGroup li',  
      effect: 'slide' 
    });
  });
{/literal}
</script>

