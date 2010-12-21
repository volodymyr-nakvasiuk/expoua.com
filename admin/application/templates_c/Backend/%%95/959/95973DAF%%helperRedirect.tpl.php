<?php /* Smarty version 2.6.18, created on 2010-12-21 14:58:46
         compiled from common/helperRedirect.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'common/helperRedirect.tpl', 5, false),)), $this); ?>
<?php if (! $this->_tpl_vars['action']): ?><?php $this->assign('action', 'list'); ?><?php endif; ?>
<script type="text/javascript">
setTimeout("redir()", 1000);
function redir() {
	document.location.href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','del' => 'direction','id' => $this->_tpl_vars['id'],'action' => $this->_tpl_vars['action']), $this);?>
";
}
</script>