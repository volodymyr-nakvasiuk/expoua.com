<?php /* Smarty version 2.6.18, created on 2010-12-21 13:11:50
         compiled from controllers/admin_ep_banners_places/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'controllers/admin_ep_banners_places/list.tpl', 23, false),array('function', 'getUrl', 'controllers/admin_ep_banners_places/list.tpl', 36, false),)), $this); ?>
<?php echo '<h4>Баннероместа</h4>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/generalFilterDescription.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php if (empty ( $this->_tpl_vars['list']['data'] )): ?><?php echo '<p>Записи отсутсвуют</p>'; ?><?php endif; ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/generalPaging.tpl", 'smarty_include_vars' => array('pages' => $this->_tpl_vars['list']['pages'],'page' => $this->_tpl_vars['list']['page'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '<TABLE border="0" width="100%" class="list"><TR><TH align="center" width="30">N</TH>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('width' => '30','name' => 'id','descr' => 'Id')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('name' => 'name','descr' => "Название")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '<TH align="center" colspan="2">Действия</TH></TR>'; ?><?php $this->assign('npp_base', $this->_tpl_vars['HMixed']->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')); ?><?php echo ''; ?><?php $this->assign('npp_base', ($this->_tpl_vars['npp_base']*$this->_tpl_vars['list']['page']-$this->_tpl_vars['npp_base'])); ?><?php echo ''; ?><?php $_from = $this->_tpl_vars['list']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fe'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['el']):
        $this->_foreach['fe']['iteration']++;
?><?php echo '<TR class="'; ?><?php echo smarty_function_cycle(array('values' => "odd,even"), $this);?><?php echo '"><TD align="center">'; ?><?php $this->assign('npp', ($this->_foreach['fe']['iteration']+$this->_tpl_vars['npp_base'])); ?><?php echo ''; ?><?php echo $this->_tpl_vars['npp']; ?><?php echo '</TD><TD align="center">'; ?><?php echo $this->_tpl_vars['el']['id']; ?><?php echo '</TD><TD>'; ?><?php echo $this->_tpl_vars['el']['name']; ?><?php echo '</TD>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Actions/general.tpl", 'smarty_include_vars' => array('el' => $this->_tpl_vars['el'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '</TR>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</TABLE><b>Всего записей: </b> '; ?><?php echo $this->_tpl_vars['list']['rows']; ?><?php echo '<br /><p><a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'add'), $this);?><?php echo '">Добавить новую запись</a></p>'; ?>