<?php /* Smarty version 2.6.18, created on 2010-12-21 14:59:37
         compiled from common/generalInsert.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'common/generalInsert.tpl', 10, false),)), $this); ?>
<?php if (! $this->_tpl_vars['action']): ?><?php $this->assign('action', 'list'); ?><?php endif; ?>
<?php if ($this->_tpl_vars['last_action_result'] == 1): ?>
  <?php echo $this->_config[0]['vars']['msgRecordAdded']; ?>

  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/helperRedirect.tpl", 'smarty_include_vars' => array('id' => $this->_tpl_vars['id'],'action' => $this->_tpl_vars['action'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
  <?php echo $this->_config[0]['vars']['msgRecordAddError']; ?>
: <?php echo $this->_tpl_vars['last_action_result']; ?>

<?php endif; ?>

<p>
 <a href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','del' => 'id','action' => 'list'), $this);?>
"><?php echo $this->_config[0]['vars']['linkBackToList']; ?>
</a>
</p>