<?php /* Smarty version 2.6.18, created on 2010-12-21 12:56:51
         compiled from common/listLanguages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'common/listLanguages.tpl', 2, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['list_languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['el']):
?>
  <a href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','language' => $this->_tpl_vars['el']['code']), $this);?>
" class="<?php if ($this->_tpl_vars['el']['code'] == $this->_tpl_vars['selected_language']): ?>current_lang<?php else: ?>lang<?php endif; ?>"><?php echo $this->_tpl_vars['el']['name']; ?>
</a>
<?php endforeach; endif; unset($_from); ?>