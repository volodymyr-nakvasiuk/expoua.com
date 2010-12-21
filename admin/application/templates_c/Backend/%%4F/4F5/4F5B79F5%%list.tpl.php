<?php /* Smarty version 2.6.18, created on 2010-12-21 13:08:40
         compiled from controllers/admin_ep_news/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'controllers/admin_ep_news/list.tpl', 24, false),array('function', 'getUrl', 'controllers/admin_ep_news/list.tpl', 28, false),)), $this); ?>
<h4>База новостей</h4>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/generalFilterDescription.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (empty ( $this->_tpl_vars['list']['data'] )): ?>
<p>Записи отсутсвуют</p>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/generalPaging.tpl", 'smarty_include_vars' => array('pages' => $this->_tpl_vars['list']['pages'],'page' => $this->_tpl_vars['list']['page'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('width' => '30','align' => 'center','name' => 'id','descr' => 'Id')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('width' => '30','align' => 'center','name' => 'active','descr' => 'A')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('align' => 'center','name' => 'name','descr' => "Заголовок")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('align' => 'center','name' => 'date_created','descr' => "Дата добавления")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <TH align="center" colspan="3">Действия</TH>
</TR>

<?php $this->assign('npp_base', $this->_tpl_vars['HMixed']->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')); ?>
<?php $this->assign('npp_base', ($this->_tpl_vars['npp_base']*$this->_tpl_vars['list']['page']-$this->_tpl_vars['npp_base'])); ?>
<?php $_from = $this->_tpl_vars['list']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fe'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['el']):
        $this->_foreach['fe']['iteration']++;
?>
 <TR class="<?php echo smarty_function_cycle(array('values' => "odd,even"), $this);?>
">
  <TD align="center"><?php $this->assign('npp', ($this->_foreach['fe']['iteration']+$this->_tpl_vars['npp_base'])); ?><?php echo $this->_tpl_vars['npp']; ?>
</TD>
  <TD align="center"><?php echo $this->_tpl_vars['el']['id']; ?>
</TD>
   <TD align="center">
  <FORM method="post" action="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'update','id' => $this->_tpl_vars['el']['id']), $this);?>
" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" <?php if ($this->_tpl_vars['el']['active'] == 1): ?> checked<?php endif; ?> onclick="this.form.submit();">
  <INPUT type="hidden" name="active" value="<?php if ($this->_tpl_vars['el']['active'] == 1): ?>0<?php else: ?>1<?php endif; ?>">
  </FORM>
 </TD>
  <TD><?php echo $this->_tpl_vars['el']['name']; ?>
</TD>
  <TD align="center"><?php echo $this->_tpl_vars['el']['date_created']; ?>
</TD>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Actions/general.tpl", 'smarty_include_vars' => array('el' => $this->_tpl_vars['el'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 </TR>
<?php endforeach; endif; unset($_from); ?>
</TABLE>

<p><a href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'add','del' => 'page'), $this);?>
">Добавить новую запись</a></p>