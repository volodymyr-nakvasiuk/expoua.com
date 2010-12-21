<?php /* Smarty version 2.6.18, created on 2010-12-21 13:09:31
         compiled from common/Lists/headerElementAutocomplete.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'common/Lists/headerElementAutocomplete.tpl', 4, false),)), $this); ?>
<?php if (! isset ( $this->_tpl_vars['action'] )): ?><?php $this->assign('action', 'list'); ?><?php endif; ?>
<SCRIPT type="text/javascript" language="Javascript">
objAC_<?php echo $this->_tpl_vars['name']; ?>
 = Shelby_Backend.Autocomplete.cloneObject('objAC_<?php echo $this->_tpl_vars['name']; ?>
');
objAC_<?php echo $this->_tpl_vars['name']; ?>
.feedUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => $this->_tpl_vars['controller'],'action' => $this->_tpl_vars['action'],'feed' => 'json'), $this);?>
';
objAC_<?php echo $this->_tpl_vars['name']; ?>
.baseSearchUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','del' => "search,page"), $this);?>
';
objAC_<?php echo $this->_tpl_vars['name']; ?>
.columnName = '<?php echo $this->_tpl_vars['name']; ?>
';
<?php if (isset ( $this->_tpl_vars['persistentFilter'] )): ?>objAC_<?php echo $this->_tpl_vars['name']; ?>
.persistentFilter = '<?php echo $this->_tpl_vars['persistentFilter']; ?>
';<?php endif; ?>
</SCRIPT>

<TH <?php if (! empty ( $this->_tpl_vars['width'] )): ?>width="<?php echo $this->_tpl_vars['width']; ?>
"<?php endif; ?> align="<?php if (! empty ( $this->_tpl_vars['align'] )): ?><?php echo $this->_tpl_vars['align']; ?>
<?php else: ?>center<?php endif; ?>"><NOBR>
 <a href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','sort' => $this->_tpl_vars['HMixed']->getSortOrder($this->_tpl_vars['name'],$this->_tpl_vars['list']['sort_by'])), $this);?>
"><?php echo $this->_tpl_vars['descr']; ?>
</a>
 <IMG src="<?php echo $this->_tpl_vars['document_root']; ?>
images/admin/icons/icon_search.gif" style="float:right; cursor:pointer;" onclick="objAC_<?php echo $this->_tpl_vars['name']; ?>
.toggleForm();" /></NOBR>

 <SCRIPT type="text/javascript">objAC_<?php echo $this->_tpl_vars['name']; ?>
.writeForm();</SCRIPT>
</TH>