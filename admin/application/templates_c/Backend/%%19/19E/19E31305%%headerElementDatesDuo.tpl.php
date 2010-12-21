<?php /* Smarty version 2.6.18, created on 2010-12-21 13:09:31
         compiled from common/Lists/headerElementDatesDuo.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'common/Lists/headerElementDatesDuo.tpl', 2, false),)), $this); ?>
<TH <?php if (! empty ( $this->_tpl_vars['width'] )): ?>width="<?php echo $this->_tpl_vars['width']; ?>
"<?php endif; ?> align="<?php if (! empty ( $this->_tpl_vars['align'] )): ?><?php echo $this->_tpl_vars['align']; ?>
<?php else: ?>center<?php endif; ?>">
 <a href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','sort' => $this->_tpl_vars['HMixed']->getSortOrder($this->_tpl_vars['name'],$this->_tpl_vars['list']['sort_by'])), $this);?>
"><?php echo $this->_tpl_vars['descr']; ?>
</a>
 <IMG src="<?php echo $this->_tpl_vars['document_root']; ?>
images/admin/icons/icon_search.gif" style="float:right; cursor:pointer;" onclick="Shelby_Backend.toggle_search('<?php echo $this->_tpl_vars['name']; ?>
');" />
 <DIV style="clear:both; display:none; position:absolute; width:100px; background:white; border: 1px solid #6f5d15;" align="center" id="list_header_search_div_<?php echo $this->_tpl_vars['name']; ?>
">
 <DIV style="border-bottom:1px solid #DDDDDD; width:100%; cursor:pointer;" onClick="$('#list_header_search_div_<?php echo $this->_tpl_vars['name']; ?>
').css('display', 'none');">X</DIV>
  <FORM method="post" onsubmit="Shelby_Backend.table_header_search_dates_duo('<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','del' => "search,page"), $this);?>
'); return false;" style="clear:both; padding:0px; float:right;">
   с <INPUT type="text" style="width:80px;" id="list_header_search_date_from"><BR />
   по <INPUT type="text" style="width:80px;" id="list_header_search_date_to">
   <INPUT type="submit" value="&gt;">
  </FORM>
 </DIV>
</TH>

<SCRIPT type="text/javascript">
$(document).ready(function() {
	$('#list_header_search_date_from').datepicker();
	$('#list_header_search_date_to').datepicker();
});
</SCRIPT>