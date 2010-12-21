<?php /* Smarty version 2.6.18, created on 2010-12-21 12:56:52
         compiled from common/leftMenu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'common/leftMenu.tpl', 125, false),)), $this); ?>
<DIV style="width:160px;<?php if (isset ( $_COOKIE['shelby_left_menu'] ) && $_COOKIE['shelby_left_menu'] == 'hide'): ?>display:none;<?php endif; ?>" id="shelby_left_menu">

<div class="leftbg">
  <div class="leftbg">
    <div class="leftbg" id="accordion">
<?php ob_start(); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_cms_pages','text' => "Редактируем контент")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_cms_galleries','text' => "Галереи",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>
<?php $this->_smarty_vars['capture']['el'] = ob_get_contents(); ob_end_clean(); ?>

    <?php if (! empty ( $this->_smarty_vars['capture']['el'] )): ?>
      <div class="leftMenuGroup"><ul><li>Редактируем контент</li></ul></div>

      <ul class="leftMenuSubElement">
        <?php echo $this->_smarty_vars['capture']['el']; ?>

      </ul>
    <?php endif; ?>

<?php ob_start(); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_events','text' => "События")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_brands','text' => "Бренды")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_brandsevents','text' => "Бренд+Событие")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_brandscategories','text' => "Категории брендов")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_socorgs','text' => "Общественные организации")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_organizers','text' => "Организаторы")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_expocenters','text' => "Выставочные центры")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_servicecomp','text' => "Сервисные компании")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_servicecompcats','text' => "Категории сервисных компаний")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_drafts','text' => "Черновики событий")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_galleries_events','text' => "Галереи событий")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>
<?php $this->_smarty_vars['capture']['el'] = ob_get_contents(); ob_end_clean(); ?>

    <?php if (! empty ( $this->_smarty_vars['capture']['el'] )): ?>
      <div class="leftMenuGroup"><ul><li>Управление базой выставок</li></ul></div>

      <ul class="leftMenuSubElement">
        <?php echo $this->_smarty_vars['capture']['el']; ?>

      </ul>
    <?php endif; ?>


<?php ob_start(); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_ads_participants','text' => "Участники")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_ads_tours','text' => "Туры")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_ads_adverts','text' => "Объявления")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_ads_messages','text' => "Сообщения объявлений")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>
<?php $this->_smarty_vars['capture']['el'] = ob_get_contents(); ob_end_clean(); ?>

    <?php if (! empty ( $this->_smarty_vars['capture']['el'] )): ?>
      <div class="leftMenuGroup"><ul><li>Реклама на карточках выставок</li></ul></div>

      <ul class="leftMenuSubElement">
        <?php echo $this->_smarty_vars['capture']['el']; ?>

      </ul>
    <?php endif; ?>

<?php ob_start(); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_companies_manage','text' => "Список компаний",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_companies_news','text' => "Новости",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_companies_services','text' => "Товары/услуги",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_companies_servicescats','text' => "Категории товаров/услуг",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_companies_sgalleries','text' => "Галлереи товаров/услуг",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_companies_employers','text' => "Сотрудники компаний",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_companies_messages','text' => "Сообщения компаниям",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>
<?php $this->_smarty_vars['capture']['el'] = ob_get_contents(); ob_end_clean(); ?>

    <?php if (! empty ( $this->_smarty_vars['capture']['el'] )): ?>
      <div class="leftMenuGroup"><ul><li>Компании</li></ul></div>

      <ul class="leftMenuSubElement">
        <?php echo $this->_smarty_vars['capture']['el']; ?>

      </ul>
    <?php endif; ?>

<?php ob_start(); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_banners_advertisers','text' => "Рекламодатели",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_banners_companies','text' => "Кампании",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_banners_plans','text' => "Планы показов",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_banners_banners','text' => "Баннера",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_banners_publishers','text' => "Издатели")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_banners_places','text' => "Площадки",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_banners_stat','text' => "Статистика",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '<li><hr/></li>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_pblbanners_users','text' => "Рекламодатели",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_pblbanners_banners','text' => "Объявления",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_pblbanners_bannersarchive','text' => "Архив",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_pblbanners_deleted','text' => "Удаленные",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_pblbanners_stat','text' => "Статистика баннеров",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_pblbanners_publishersstat','text' => "Статистика партнеров",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_pblbanners_clicksstat','text' => "Статистика кликов",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_pblbanners_test','text' => "Просмотр",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>
<?php $this->_smarty_vars['capture']['el'] = ob_get_contents(); ob_end_clean(); ?>

    <?php if (! empty ( $this->_smarty_vars['capture']['el'] )): ?>
      <div class="leftMenuGroup"><ul><li>Баннера</li></ul></div>

      <ul class="leftMenuSubElement">
        <?php echo $this->_smarty_vars['capture']['el']; ?>

      </ul>
    <?php endif; ?>

<?php ob_start(); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_operators_messages','text' => "Операторов/организаторов",'count' => count($this->_tpl_vars['oq_list']['data']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_pblbanners_messages','text' => "Рекламодателей",'count' => count($this->_tpl_vars['aq_list']['data']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>
<?php $this->_smarty_vars['capture']['el'] = ob_get_contents(); ob_end_clean(); ?>

    <?php if (! empty ( $this->_smarty_vars['capture']['el'] )): ?>
      <div class="leftMenuGroup"><ul><li<?php if (count($this->_tpl_vars['pq_list']['data']) || count($this->_tpl_vars['tq_list']['data']) || count($this->_tpl_vars['oq_list']['data']) || count($this->_tpl_vars['aq_list']['data'])): ?> style="color:#900"<?php endif; ?>>Вопросы/Ответы</li></ul></div>

      <ul class="leftMenuSubElement">
        <?php echo $this->_smarty_vars['capture']['el']; ?>

      </ul>
    <?php endif; ?>


<?php ob_start(); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_eventsparticipants','text' => "Участники")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_newsparticipants','text' => "Новости участников")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_comments','text' => "Комментарии")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_news','text' => "Новости")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_articles','text' => "Статьи")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_locations_regions','text' => "Регионы/страны/города")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_analyze','text' => "Анализаторы")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>
<?php $this->_smarty_vars['capture']['el'] = ob_get_contents(); ob_end_clean(); ?>

    <?php if (! empty ( $this->_smarty_vars['capture']['el'] )): ?>
      <div class="leftMenuGroup"><ul><li>Разное</li></ul></div>

      <ul class="leftMenuSubElement">
        <?php echo $this->_smarty_vars['capture']['el']; ?>

      </ul>
    <?php endif; ?>

<?php ob_start(); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_files_download','text' => "Файлы для загрузки")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_files_images','text' => "Изображения для контента")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_files_flash','text' => "Flash для контента")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_eventsfiles','text' => "Файлы на карточках выставок",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>
<?php $this->_smarty_vars['capture']['el'] = ob_get_contents(); ob_end_clean(); ?>

    <?php if (! empty ( $this->_smarty_vars['capture']['el'] )): ?>
      <div class="leftMenuGroup"><ul><li>Файлы</li></ul></div>

      <ul class="leftMenuSubElement">
        <?php echo $this->_smarty_vars['capture']['el']; ?>

      </ul>
    <?php endif; ?>

<?php ob_start(); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_cms_templates_pages','text' => "Основные шаблоны",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_cms_templates_adds','text' => "Дополнительные шаблоны",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_cms_templates_modules','text' => "Шаблоны модулей",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_cms_templates_system','text' => "Системные шаблоны",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>
<?php $this->_smarty_vars['capture']['el'] = ob_get_contents(); ob_end_clean(); ?>

    <?php if (! empty ( $this->_smarty_vars['capture']['el'] )): ?>
      <div class="leftMenuGroup"><ul><li>Управление шаблонами</li></ul></div>

      <ul class="leftMenuSubElement">
        <?php echo $this->_smarty_vars['capture']['el']; ?>

      </ul>
    <?php endif; ?>

<?php ob_start(); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_acl_admins','text' => "Пользователи админки")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_acl_admins_groups','text' => "Группы пользователей админки")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_acl_operators','text' => "Внештатные операторы")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_acl_organizers','text' => "Организаторы")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_acl_servcompany','text' => "Сервисные компании")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_acl_companies','text' => "Компании")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_eventsusers','text' => "Пользователи событий")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_eventsadsusers','text' => "Пользователи объявлений")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_acl_resources','text' => "Модули")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>
<?php $this->_smarty_vars['capture']['el'] = ob_get_contents(); ob_end_clean(); ?>

    <?php if (! empty ( $this->_smarty_vars['capture']['el'] )): ?>
      <div class="leftMenuGroup"><ul><li>Управление доступом</li></ul></div>

      <ul class="leftMenuSubElement">
        <?php echo $this->_smarty_vars['capture']['el']; ?>

      </ul>
    <?php endif; ?>

<?php ob_start(); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_options_languages','text' => "Языки")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_options_constants','text' => "Конфигурационные константы")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>
<?php $this->_smarty_vars['capture']['el'] = ob_get_contents(); ob_end_clean(); ?>

    <?php if (! empty ( $this->_smarty_vars['capture']['el'] )): ?>
      <div class="leftMenuGroup"><ul><li>Настройки</li></ul></div>

      <ul class="leftMenuSubElement">
        <?php echo $this->_smarty_vars['capture']['el']; ?>

      </ul>
    <?php endif; ?>

<?php ob_start(); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_siteusers','text' => "Пользователи сайта",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_subscribers','text' => "Подписчики",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_ep_requests','text' => "Запросы пользователей")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>
<?php $this->_smarty_vars['capture']['el'] = ob_get_contents(); ob_end_clean(); ?>

    <?php if (! empty ( $this->_smarty_vars['capture']['el'] )): ?>
      <div class="leftMenuGroup"><ul><li>Экспорт</li></ul></div>
      <ul class="leftMenuSubElement">
        <?php echo $this->_smarty_vars['capture']['el']; ?>

      </UL>
    <?php endif; ?>

<?php ob_start(); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_system_logactions','text' => "Журнал работы пользователей",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_system_logoperators','text' => "Журнал работы операторов",'action' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_system_logcoordinators','text' => "Журнал работы координаторов")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/menuItem.tpl", 'smarty_include_vars' => array('controller' => 'admin_system_fulltext','action' => 'index','text' => "Состояние поискового индекса")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>
<?php $this->_smarty_vars['capture']['el'] = ob_get_contents(); ob_end_clean(); ?>

 <?php if (! empty ( $this->_smarty_vars['capture']['el'] )): ?>
 <DIV class="leftMenuGroup"><ul><li>Системные операции</li></ul></DIV>
 <UL class="leftMenuSubElement">
  <?php echo $this->_smarty_vars['capture']['el']; ?>

 </UL>
 <?php endif; ?>

</div></div></div>

</DIV>

<script type="text/javascript">
<?php echo '
$(document).ready(function() {
  jQuery(\'#accordion\').Accordion({
    navigation: false,
    autoheight: false,
    animated: false,
    event: "click",
    header: ".leftMenuGroup"
  });

  //jQuery(\'#accordion\').activate(-1);
  var select = $("#accordion").find("a").filter(function() {
    if (this.href.indexOf(\''; ?>
<?php echo $this->_tpl_vars['user_params']['controller']; ?>
<?php echo '/\') !== -1) {
      return true;
    }
    return false;
  });

  jQuery(\'#accordion\').activate(select.parent().parent().prev());
});
'; ?>

</script>