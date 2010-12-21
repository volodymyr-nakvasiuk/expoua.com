<?php /* Smarty version 2.6.18, created on 2010-12-21 13:08:40
         compiled from common/Actions/general.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'common/Actions/general.tpl', 2, false),)), $this); ?>
<?php if (! empty ( $this->_tpl_vars['session_user_allow'][$this->_tpl_vars['user_params']['controller']]['view'] )): ?>
<TD width="20" align="center"><a href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'view','id' => $this->_tpl_vars['el']['id']), $this);?>
"><img alt="<?php echo $this->_config[0]['vars']['previewAction']; ?>
" src="<?php echo $this->_tpl_vars['document_root']; ?>
images/admin/icons/page_text.gif" border="0" width="16"></a></TD>
<?php endif; ?>
<?php if (! empty ( $this->_tpl_vars['session_user_allow'][$this->_tpl_vars['user_params']['controller']]['update'] )): ?>
<TD width="20" align="center"><a href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'edit','id' => $this->_tpl_vars['el']['id']), $this);?>
"><img title="<?php echo $this->_config[0]['vars']['editAction']; ?>
" src="<?php echo $this->_tpl_vars['document_root']; ?>
images/admin/list-edit.gif" border="0" width="23" height="21"></a></TD>
<?php endif; ?>
<?php if (! empty ( $this->_tpl_vars['session_user_allow'][$this->_tpl_vars['user_params']['controller']]['delete'] )): ?>
<TD width="20" align="center"><a href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'delete','id' => $this->_tpl_vars['el']['id']), $this);?>
" onclick="return Shelby_Backend.confirmDelete();"><img alt="<?php echo $this->_config[0]['vars']['deleteAction']; ?>
" src="<?php echo $this->_tpl_vars['document_root']; ?>
images/admin/icons/delete.gif" border="0" width="16"></a></TD>
<?php endif; ?>