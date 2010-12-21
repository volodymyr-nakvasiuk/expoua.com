<?php /* Smarty version 2.6.18, created on 2010-12-21 13:11:53
         compiled from controllers/admin_ep_banners_stat/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'controllers/admin_ep_banners_stat/list.tpl', 29, false),array('function', 'getUrl', 'controllers/admin_ep_banners_stat/list.tpl', 31, false),array('function', 'debug', 'controllers/admin_ep_banners_stat/list.tpl', 54, false),array('modifier', 'string_format', 'controllers/admin_ep_banners_stat/list.tpl', 35, false),)), $this); ?>
<?php echo '<h4>Статистика показов баннеров</h4>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/generalFilterDescription.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php if (empty ( $this->_tpl_vars['list']['data'] )): ?><?php echo '<p>Записи отсутсвуют</p>'; ?><?php endif; ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/generalPaging.tpl", 'smarty_include_vars' => array('pages' => $this->_tpl_vars['list']['pages'],'page' => $this->_tpl_vars['list']['page'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '<TABLE border="0" width="100%" class="list"><TR>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('width' => '30','name' => 'id','descr' => 'Id')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('name' => 'name','descr' => "План показа")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementAutocomplete.tpl", 'smarty_include_vars' => array('name' => 'companies_id','controller' => 'admin_ep_banners_companies','descr' => "Кампания")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '<TH align="center" width="60">Показов</TH><TH align="center" width="60">Кликов</TH><TH align="center" width="60">CTR</TH><TH align="center" colspan="1">Действия</TH></TR>'; ?><?php $this->assign('total_shows', 0); ?><?php echo ''; ?><?php $this->assign('total_clicks', 0); ?><?php echo ''; ?><?php $this->assign('npp_base', $this->_tpl_vars['HMixed']->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')); ?><?php echo ''; ?><?php $this->assign('npp_base', ($this->_tpl_vars['npp_base']*$this->_tpl_vars['list']['page']-$this->_tpl_vars['npp_base'])); ?><?php echo ''; ?><?php $_from = $this->_tpl_vars['list']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fe'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['el']):
        $this->_foreach['fe']['iteration']++;
?><?php echo '<TR class="'; ?><?php echo smarty_function_cycle(array('values' => "odd,even"), $this);?><?php echo '"><TD align="center">'; ?><?php echo $this->_tpl_vars['el']['id']; ?><?php echo '</TD><TD><a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'view','id' => $this->_tpl_vars['el']['id']), $this);?><?php echo '">'; ?><?php echo $this->_tpl_vars['el']['name']; ?><?php echo '</a></TD><TD>'; ?><?php echo $this->_tpl_vars['el']['company_name']; ?><?php echo '</TD><TD align="center">'; ?><?php echo $this->_tpl_vars['el']['shows']; ?><?php echo '</TD><TD align="center">'; ?><?php echo $this->_tpl_vars['el']['clicks']; ?><?php echo '</TD><TD align="center">'; ?><?php if ($this->_tpl_vars['el']['clicks'] == 0): ?><?php echo '0'; ?><?php else: ?><?php echo ''; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['el']['clicks']/$this->_tpl_vars['el']['shows']*100)) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.4f") : smarty_modifier_string_format($_tmp, "%.4f")); ?><?php echo ''; ?><?php endif; ?><?php echo '%</TD>'; ?><?php $this->assign('total_shows', $this->_tpl_vars['total_shows']+$this->_tpl_vars['el']['shows']); ?><?php echo ''; ?><?php $this->assign('total_clicks', $this->_tpl_vars['total_clicks']+$this->_tpl_vars['el']['clicks']); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Actions/general.tpl", 'smarty_include_vars' => array('el' => $this->_tpl_vars['el'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '</TR>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '<TR><TD colspan="3" align="right"><b>Всего по странице:</b> </TD><td align="center"><b>'; ?><?php echo $this->_tpl_vars['total_shows']; ?><?php echo '</b></td><td align="center"><b>'; ?><?php echo $this->_tpl_vars['total_clicks']; ?><?php echo '</b></td><td align="center"><b>'; ?><?php if ($this->_tpl_vars['total_clicks'] == 0): ?><?php echo '0'; ?><?php else: ?><?php echo ''; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['total_clicks']/$this->_tpl_vars['total_shows']*100)) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.4f") : smarty_modifier_string_format($_tmp, "%.4f")); ?><?php echo ''; ?><?php endif; ?><?php echo '%</b></td></TR></TABLE><b>Всего записей: </b> '; ?><?php echo $this->_tpl_vars['list']['rows']; ?><?php echo ''; ?>



<?php if ($_GET['debug']): ?><?php echo smarty_function_debug(array(), $this);?>
<?php endif; ?>