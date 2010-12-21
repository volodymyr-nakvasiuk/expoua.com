<?php /* Smarty version 2.6.18, created on 2010-12-21 13:09:31
         compiled from common/Lists/headerElementCheckbox.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'common/Lists/headerElementCheckbox.tpl', 2, false),)), $this); ?>
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
'); return false;">
    <INPUT type="checkbox" onclick="var obj=document.getElementById('list_header_search_kw_<?php echo $this->_tpl_vars['name']; ?>
'); if (this.checked) {obj.value='1';}else {obj.value='0';}">
    <INPUT type="submit" value="&gt;">
    <INPUT type="hidden" style="width:90%;" id="list_header_search_kw_<?php echo $this->_tpl_vars['name']; ?>
" value="0">
  </FORM>
 </DIV>
</TH>