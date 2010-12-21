<?php /* Smarty version 2.6.18, created on 2010-12-21 13:08:40
         compiled from common/Lists/headerElementGeneral.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'common/Lists/headerElementGeneral.tpl', 2, false),)), $this); ?>
<TH <?php if (! empty ( $this->_tpl_vars['width'] )): ?>width="<?php echo $this->_tpl_vars['width']; ?>
"<?php endif; ?> align="<?php if (! empty ( $this->_tpl_vars['align'] )): ?><?php echo $this->_tpl_vars['align']; ?>
<?php else: ?>center<?php endif; ?>">
 <a href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','sort' => $this->_tpl_vars['HMixed']->getSortOrder($this->_tpl_vars['name'],$this->_tpl_vars['list']['sort_by'])), $this);?>
"><?php echo $this->_tpl_vars['descr']; ?>
</a>
 <IMG src="<?php echo $this->_tpl_vars['document_root']; ?>
images/admin/icons/icon_search.gif" style="float:right; cursor:pointer;" onclick="Shelby_Backend.toggle_search('<?php echo $this->_tpl_vars['name']; ?>
');" />
 <DIV style="clear:both; display:none;" align="center" id="list_header_search_div_<?php echo $this->_tpl_vars['name']; ?>
">
  <FORM method="post" onsubmit="Shelby_Backend.table_header_search('<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','del' => "search,page"), $this);?>
', '<?php echo $this->_tpl_vars['name']; ?>
'<?php if ($this->_tpl_vars['stype']): ?>, '<?php echo $this->_tpl_vars['stype']; ?>
'<?php endif; ?>); return false;">
   <INPUT type="text" style="width:90%;" id="list_header_search_kw_<?php echo $this->_tpl_vars['name']; ?>
">
  </FORM>
 </DIV>
</TH>