
<DIV style="width:160px;{if isset($smarty.cookies.shelby_left_menu) && $smarty.cookies.shelby_left_menu=="hide"}display:none;{/if}" id="shelby_left_menu">

<div class="leftbg"><div class="leftbg"><div class="leftbg" id="accordion">

{capture name="el"}{strip}
{if !empty($session_user_allow.admin_cms_pages.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_cms_pages" action="list"}">Редактируем контент</A></LI>{/if}
  {if !empty($session_user_allow.admin_cms_news.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_cms_news" action="list"}">Редактируем новости</A></LI>{/if}
  {if !empty($session_user_allow.admin_cms_menus.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_cms_menus" action="list"}">Редактируем меню</A></LI>{/if}
  {if !empty($session_user_allow.admin_cms_votes.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_cms_votes" action="list"}">Голосования</A></LI>{/if}
  {if !empty($session_user_allow.admin_cms_galleries.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_cms_galleries" action="list"}">Галлереи</A></LI>{/if}
  {if !empty($session_user_allow.admin_cms_objects.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_cms_objects" action="list"}">Дополнительные опции</A></LI>{/if}
{/strip}{/capture}

 {if !empty($smarty.capture.el)}
 <DIV class="leftMenuGroup"><ul><li>Редактируем контент</li></ul></DIV>
 <UL class="leftMenuSubElement">
  {$smarty.capture.el}
 </UL>
 {/if}

{capture name="el"}{strip}
 {if !empty($session_user_allow.admin_ep_events.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_events" action="list"}">События</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_brands.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_brands" action="list"}">Бренды</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_brandsevents.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_brandsevents" action="list"}">Бренд+Событие</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_brandscategories.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_brandscategories" action="list"}">Категории брендов</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_socorgs.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_socorgs" action="list"}">Общественные организации</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_organizers.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_organizers" action="list"}">Организаторы</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_expocenters.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_expocenters" action="list"}">Выставочные центры</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_servicecomp.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_servicecomp" action="list"}">Сервисные компании</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_servicecompcats.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_servicecompcats" action="list"}">Категории сервисных компаний</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_drafts)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_drafts" action="list"}">Черновики событий</A></LI>{/if}
{/strip}{/capture}

 {if !empty($smarty.capture.el)}
 <DIV class="leftMenuGroup"><ul><li>Управление базой выставок</li></ul></DIV>
 <UL class="leftMenuSubElement">
  {$smarty.capture.el}
 </UL>
 {/if}

{capture name="el"}{strip}
 {if !empty($session_user_allow.admin_ep_ads_participants.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_ads_participants" action="list"}">Участники</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_ads_tours.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_ads_tours" action="list"}">Туры</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_ads_adverts.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_ads_adverts" action="list"}">Объявления</A></LI>{/if}
{if !empty($session_user_allow.admin_ep_ads_messages.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_ads_messages" action="list"}">Сообщения объявлений</A></LI>{/if}
{/strip}{/capture}

 {if !empty($smarty.capture.el)}
 <DIV class="leftMenuGroup"><ul><li>Реклама на карточках выставок</li></ul></DIV>
 <UL class="leftMenuSubElement">
  {$smarty.capture.el}
 </UL>
 {/if}

{capture name="el"}{strip}
 {if !empty($session_user_allow.admin_ep_companies_manage.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_companies_manage" action="list"}">Список компаний</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_companies_news.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_companies_news" action="list"}">Новости</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_companies_services.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_companies_services" action="list"}">Товары/услуги</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_companies_servicescats.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_companies_servicescats" action="list"}">Категории товаров/услуг</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_companies_sgalleries.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_companies_sgalleries" action="list"}">Галлереи товаров/услуг</A></LI>{/if}
{if !empty($session_user_allow.admin_ep_companies_employers.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_companies_employers" action="list"}">Сотрудники компаний</A></LI>{/if}
{/strip}{/capture}

 {if !empty($smarty.capture.el)}
 <DIV class="leftMenuGroup"><ul><li>Компании</li></ul></DIV>
 <UL class="leftMenuSubElement">
  {$smarty.capture.el}
 </UL>
 {/if}

{capture name="el"}{strip}
 {if !empty($session_user_allow.admin_ep_banners_advertisers.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_banners_advertisers" action="list"}">Рекламодатели</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_banners_companies.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_banners_companies" action="list"}">Кампании</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_banners_plans.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_banners_plans" action="list"}">Планы показов</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_banners_banners.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_banners_banners" action="list"}">Баннера</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_banners_publishers.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_banners_publishers" action="list"}">Издатели</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_banners_places.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_banners_places" action="list"}">Площадки</A></LI>{/if}
  {if !empty($session_user_allow.admin_ep_banners_stat.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_banners_stat" action="list"}">Статистика</A></LI>{/if}
{/strip}{/capture}

 {if !empty($smarty.capture.el)}
 <DIV class="leftMenuGroup"><ul><li>Баннера</li></ul></DIV>
 <UL class="leftMenuSubElement">
  {$smarty.capture.el}
 </UL>
 {/if}

{capture name="el"}{strip}
 {if !empty($session_user_allow.admin_ep_eventsparticipants.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_eventsparticipants" action="list"}">Участники</A></LI>{/if}
 {if !empty($session_user_allow.admin_ep_newsparticipants.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_newsparticipants" action="list"}">Новости участников</A></LI>{/if}
  {if !empty($session_user_allow.admin_ep_comments.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_comments" action="list"}">Комментарии</A></LI>{/if}
  {if !empty($session_user_allow.admin_ep_news.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_news" action="list"}">Новости</A></LI>{/if}
  {if !empty($session_user_allow.admin_ep_articles.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_articles" action="list"}">Статьи</A></LI>{/if}
  {if !empty($session_user_allow.admin_ep_locations_regions.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_locations_regions" action="list"}">Регионы/страны/города</A></LI>{/if}
  {if !empty($session_user_allow.admin_ep_analyze)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_analyze"}">Анализаторы</A></LI>{/if}
{/strip}{/capture}

 {if !empty($smarty.capture.el)}
 <DIV class="leftMenuGroup"><ul><li>Разное</li></ul></DIV>
 <UL class="leftMenuSubElement">
  {$smarty.capture.el}
 </UL>
{/if}

{capture name="el"}{strip}
  {if !empty($session_user_allow.admin_files_download.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_files_download" action="list"}">Файлы для загрузки</A></LI>{/if}
  {if !empty($session_user_allow.admin_files_images.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_files_images" action="list"}">Изображения для контента</A></LI>{/if}
  {if !empty($session_user_allow.admin_files_flash.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_files_flash" action="list"}">Flash для контента</A></LI>{/if}
  {if !empty($session_user_allow.admin_ep_eventsfiles.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_eventsfiles" action="list"}">Файлы на карточках выставок</A></LI>{/if}
{/strip}{/capture}

 {if !empty($smarty.capture.el)}
 <DIV class="leftMenuGroup"><ul><li>Файлы</li></ul></DIV>
 <UL class="leftMenuSubElement">
  {$smarty.capture.el}
 </UL>
 {/if}

{capture name="el"}{strip}
  {if !empty($session_user_allow.admin_cms_templates_pages.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_cms_templates_pages" action="list"}">Основные шаблоны</A></LI>{/if}
  {if !empty($session_user_allow.admin_cms_templates_news.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_cms_templates_news" action="list"}">Шаблоны новостей</A></LI>{/if}
  {if !empty($session_user_allow.admin_cms_templates_menus.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_cms_templates_menus" action="list"}">Шаблоны меню</A></LI>{/if}
  {if !empty($session_user_allow.admin_cms_templates_adds.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_cms_templates_adds" action="list"}">Дополнительные шаблоны</A></LI>{/if}
  {if !empty($session_user_allow.admin_cms_templates_modules.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_cms_templates_modules" action="list"}">Шаблоны модулей</A></LI>{/if}
  {if !empty($session_user_allow.admin_cms_templates_system.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_cms_templates_system" action="list"}">Системные шаблоны</A></LI>{/if}
{/strip}{/capture}

 {if !empty($smarty.capture.el)}
 <DIV class="leftMenuGroup"><ul><li>Управление шаблонами</li></ul></DIV>
 <UL class="leftMenuSubElement">
  {$smarty.capture.el}
 </UL>
 {/if}

{capture name="el"}{strip}
  {if !empty($session_user_allow.admin_acl_admins.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_acl_admins" action="list"}">Пользователи админки</A></LI>{/if}
  {if !empty($session_user_allow.admin_acl_admins_groups.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_acl_admins_groups" action="list"}">Группы пользователей админки</A></LI>{/if}
  {if !empty($session_user_allow.admin_acl_operators.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_acl_operators" action="list"}">Внештатные операторы</A></LI>{/if}
  {if !empty($session_user_allow.admin_acl_organizers.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_acl_organizers" action="list"}">Организаторы</A></LI>{/if}
  {if !empty($session_user_allow.admin_acl_servcompany.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_acl_servcompany" action="list"}">Сервисные компании</A></LI>{/if}
  {if !empty($session_user_allow.admin_acl_companies.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_acl_companies" action="list"}">Компании</A></LI>{/if}
  {if !empty($session_user_allow.admin_ep_eventsusers.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_eventsusers" action="list"}">Пользователи событий</A></LI>{/if}
  {if !empty($session_user_allow.admin_ep_eventsadsusers.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_eventsadsusers" action="list"}">Пользователи объявлений</A></LI>{/if}
  {if !empty($session_user_allow.admin_acl_resources.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_acl_resources" action="list"}">Модули</A></LI>{/if}
{/strip}{/capture}

 {if !empty($smarty.capture.el)}
 <DIV class="leftMenuGroup"><ul><li>Управление доступом</li></ul></DIV>
 <UL class="leftMenuSubElement">
  {$smarty.capture.el}
 </UL>
 {/if}

{capture name="el"}{strip}
  {if !empty($session_user_allow.admin_options_languages.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_options_languages" action="list"}">Языки</A></LI>{/if}
  {if !empty($session_user_allow.admin_options_constants.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_options_constants" action="list"}">Конфигурационные константы</A></LI>{/if}
{/strip}{/capture}

 {if !empty($smarty.capture.el)}
 <DIV class="leftMenuGroup"><ul><li>Настройки</li></ul></DIV>
 <UL class="leftMenuSubElement">
  {$smarty.capture.el}
 </UL>
 {/if}

{capture name="el"}{strip}
  {if !empty($session_user_allow.admin_ep_sites.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_sites" action="list"}">Зарегистрированные сайты</A></LI>{/if}
  {if !empty($session_user_allow.admin_ep_siteusers.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_siteusers" action="list"}">Пользователи сайтов</A></LI>{/if}
  {if !empty($session_user_allow.admin_ep_subscribers.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_subscribers" action="list"}">Подписчики</A></LI>{/if}
  {if !empty($session_user_allow.admin_ep_requests.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_ep_requests" action="index"}">Запросы пользователей</A></LI>{/if}
{/strip}{/capture}

 {if !empty($smarty.capture.el)}
 <DIV class="leftMenuGroup"><ul><li>Экспорт</li></ul></DIV>
 <UL class="leftMenuSubElement">
  {$smarty.capture.el}
 </UL>
 {/if}

{capture name="el"}{strip}
  {if !empty($session_user_allow.admin_system_logactions.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_system_logactions" action="list"}">Журнал работы пользователей</A></LI>{/if}
  {if !empty($session_user_allow.admin_system_logoperators.list)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_system_logoperators" action="list"}">Журнал работы операторов</A></LI>{/if}
  {if !empty($session_user_allow.admin_system_backup.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_system_backup"}">Резервное копирование</A></LI>{/if}
  {if !empty($session_user_allow.admin_system_upgrade.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_system_upgrade"}">Обновления</A></LI>{/if}
  {if !empty($session_user_allow.admin_system_fulltext.index)}<LI><A class="leftMenuSubElement" href="{getUrl controller="admin_system_fulltext"}">Состояние поискового индекса</A></LI>{/if}
{/strip}{/capture}

 {if !empty($smarty.capture.el)}
 <DIV class="leftMenuGroup"><ul><li>Системные операции</li></ul></DIV>
 <UL class="leftMenuSubElement">
  {$smarty.capture.el}
 </UL>
 {/if}

</div></div></div>

</DIV>

<SCRIPT type="text/javascript">
{literal}
$(document).ready(function() {
	jQuery('#accordion').Accordion({
		navigation: false,
		autoheight: false,
		animated: false,
		event: "click",
		header: ".leftMenuGroup"
	});

	//jQuery('#accordion').activate(-1);
	var select = $("#accordion").find("a").filter(function() {
		if (this.href.indexOf('{/literal}{$user_params.controller}{literal}') !== -1) {
			return true;
		}
		return false;
	});

	jQuery('#accordion').activate(select.parent().parent().prev());
});
{/literal}
</SCRIPT>