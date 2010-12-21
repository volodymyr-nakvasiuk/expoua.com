<?php /* Smarty version 2.6.18, created on 2010-12-21 13:09:31
         compiled from controllers/admin_ep_brandsevents/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'controllers/admin_ep_brandsevents/list.tpl', 30, false),array('function', 'getUrl', 'controllers/admin_ep_brandsevents/list.tpl', 34, false),)), $this); ?>
<?php echo '<h4>Бренд+Событие</h4>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/generalFilterDescription.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php if (empty ( $this->_tpl_vars['list']['data'] )): ?><?php echo '<p>Записи отсутсвуют</p>'; ?><?php endif; ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/generalPaging.tpl", 'smarty_include_vars' => array('pages' => $this->_tpl_vars['list']['pages'],'page' => $this->_tpl_vars['list']['page'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '<TABLE border="0" width="100%" class="list"><TR><TH align="center">N</TH>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('width' => '30','name' => 'id','descr' => 'Id')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementCheckbox.tpl", 'smarty_include_vars' => array('width' => '30','name' => 'active','descr' => 'A')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementCheckbox.tpl", 'smarty_include_vars' => array('width' => '30','name' => 'premium','descr' => 'P')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementDatesDuo.tpl", 'smarty_include_vars' => array('width' => '100','name' => 'date_from','descr' => "Даты")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementAutocomplete.tpl", 'smarty_include_vars' => array('name' => 'organizers_id','controller' => 'admin_ep_organizers','descr' => "Организатор")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementAutocomplete.tpl", 'smarty_include_vars' => array('name' => 'brands_id','controller' => 'admin_ep_brands','descr' => "Бренд")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementAutocomplete.tpl", 'smarty_include_vars' => array('name' => 'expocenters_id','controller' => 'admin_ep_expocenters','descr' => "Выставочный центр")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementAutocomplete.tpl", 'smarty_include_vars' => array('name' => 'countries_id','controller' => 'admin_ep_locations_countries','descr' => "Страна")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementAutocomplete.tpl", 'smarty_include_vars' => array('name' => 'cities_id','controller' => 'admin_ep_locations_cities','descr' => "Город")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '<TH align="center" colspan="5">Действия</TH></TR>'; ?><?php $this->assign('npp_base', $this->_tpl_vars['HMixed']->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')); ?><?php echo ''; ?><?php $this->assign('npp_base', ($this->_tpl_vars['npp_base']*$this->_tpl_vars['list']['page']-$this->_tpl_vars['npp_base'])); ?><?php echo ''; ?><?php $_from = $this->_tpl_vars['list']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fe'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['el']):
        $this->_foreach['fe']['iteration']++;
?><?php echo '<TR class="'; ?><?php if ($this->_tpl_vars['el']['brand_dead'] == 1): ?><?php echo 'marked_blue'; ?><?php else: ?><?php echo ''; ?><?php echo smarty_function_cycle(array('values' => "odd,even"), $this);?><?php echo ''; ?><?php endif; ?><?php echo '"><TD align="center">'; ?><?php $this->assign('npp', ($this->_foreach['fe']['iteration']+$this->_tpl_vars['npp_base'])); ?><?php echo ''; ?><?php echo $this->_tpl_vars['npp']; ?><?php echo '</TD><TD align="center">'; ?><?php echo $this->_tpl_vars['el']['id']; ?><?php echo '</TD><TD align="center"><FORM method="post" action="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','controller' => 'admin_ep_events','action' => 'update','id' => $this->_tpl_vars['el']['id']), $this);?><?php echo '" style="padding:0px; margin:0px;"><INPUT type="checkbox" '; ?><?php if ($this->_tpl_vars['el']['active'] == 1): ?><?php echo ' checked'; ?><?php endif; ?><?php echo ' onclick="this.form.submit();"><INPUT type="hidden" name="active" value="'; ?><?php if ($this->_tpl_vars['el']['active'] == 1): ?><?php echo '0'; ?><?php else: ?><?php echo '1'; ?><?php endif; ?><?php echo '"></FORM></TD><TD align="center"><FORM method="post" action="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','controller' => 'admin_ep_events','action' => 'update','id' => $this->_tpl_vars['el']['id']), $this);?><?php echo '" style="padding:0px; margin:0px;"><INPUT type="checkbox" '; ?><?php if ($this->_tpl_vars['el']['premium'] == 1): ?><?php echo ' checked'; ?><?php endif; ?><?php echo ' onclick="this.form.submit();"><INPUT type="hidden" name="show_list_logo" value="'; ?><?php if ($this->_tpl_vars['el']['premium'] == 1): ?><?php echo '0'; ?><?php else: ?><?php echo '1'; ?><?php endif; ?><?php echo '"></FORM></TD><TD align="center"><nobr>'; ?><?php echo $this->_tpl_vars['el']['date_from']; ?><?php echo '</nobr><br />'; ?><?php echo $this->_tpl_vars['el']['date_to']; ?><?php echo '</TD><TD align="center"><a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','search' => "organizers_id=".($this->_tpl_vars['el']['organizers_id'])), $this);?><?php echo '">'; ?><?php echo $this->_tpl_vars['el']['organizer_name']; ?><?php echo '</a></TD><TD align="center">'; ?><?php echo $this->_tpl_vars['el']['name']; ?><?php echo '</TD><TD align="center">'; ?><?php echo $this->_tpl_vars['el']['expocenter_name']; ?><?php echo '</TD><TD align="center">'; ?><?php echo $this->_tpl_vars['el']['country_name']; ?><?php echo '</TD><TD align="center"><a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','search' => "cities_id=".($this->_tpl_vars['el']['cities_id'])), $this);?><?php echo '">'; ?><?php echo $this->_tpl_vars['el']['city_name']; ?><?php echo '</a></TD><TD><a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_galleries_events','action' => 'list','parent' => $this->_tpl_vars['el']['id']), $this);?><?php echo '"><img title="Галерея" src="/images/admin/icons/page_component.gif" border="0" width="16"></a></TD><TD><a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','controller' => 'admin_ep_brands','action' => 'edit','id' => $this->_tpl_vars['el']['brands_id']), $this);?><?php echo '"><img title="Изменить бренд" src="/images/admin/icons/edit_brand.gif" border="0" width="16"></a></TD><TD><a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','controller' => 'admin_ep_events','action' => 'edit','id' => $this->_tpl_vars['el']['id']), $this);?><?php echo '"><img title="Изменить событие" src="/images/admin/icons/edit_event.gif" border="0" width="16"></a></TD><TD><a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','controller' => 'admin_ep_events','action' => 'copy','id' => $this->_tpl_vars['el']['id']), $this);?><?php echo '"><img title="Копировать событие" src="/images/admin/icons/copy.gif" border="0" width="15"></a></TD><TD><a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','controller' => 'admin_ep_events','action' => 'delete','id' => $this->_tpl_vars['el']['id']), $this);?><?php echo '" onclick="return Shelby_Backend.confirmDelete();"><img title="Удалить событие" src="/images/admin/icons/delete.gif" border="0" width="16"></a></TD></TR>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</TABLE><b>Всего записей: </b> '; ?><?php echo $this->_tpl_vars['list']['rows']; ?><?php echo '<p><a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_events','action' => 'add'), $this);?><?php echo '">Добавить новое событие</a></p><p><a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_brands','action' => 'add'), $this);?><?php echo '">Добавить новый бренд</a></p><p><a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_brandsevents','action' => 'add'), $this);?><?php echo '">Добавить новое бренд+событие</a></p>'; ?>