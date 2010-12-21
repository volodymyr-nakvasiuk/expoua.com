<?php /* Smarty version 2.6.18, created on 2010-12-21 13:10:48
         compiled from common/generalList.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'common/generalList.tpl', 19, false),array('function', 'cycle', 'common/generalList.tpl', 37, false),)), $this); ?>
<h4>Автогенерируемый список</h4>

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
<?php $this->assign('end', 0); ?>
<?php $_from = $this->_tpl_vars['list']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['element']):
?>
 <?php if ($this->_tpl_vars['end'] == 0): ?>
  <?php $this->assign('end', 1); ?>
  <?php $_from = $this->_tpl_vars['element']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['el_key'] => $this->_tpl_vars['el']):
?>
   <?php if ($this->_tpl_vars['el_key'] != 'content'): ?>
    <TH width="50" align="center">
     <a href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','sort' => $this->_tpl_vars['HMixed']->getSortOrder($this->_tpl_vars['el_key'],$this->_tpl_vars['list']['sort_by'])), $this);?>
"><?php echo $this->_tpl_vars['el_key']; ?>
</a>
     <DIV style="float:right; cursor:pointer;" onclick="Shelby_Backend.toggle_search('<?php echo $this->_tpl_vars['el_key']; ?>
');">S</DIV>
     <DIV style="clear:both; display:none;" align="center" id="list_header_search_div_<?php echo $this->_tpl_vars['el_key']; ?>
">
      <FORM method="post" onsubmit="Shelby_Backend.table_header_search('<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','del' => "search,page"), $this);?>
', '<?php echo $this->_tpl_vars['el_key']; ?>
'); return false;">
       <INPUT type="text" style="width:90%;" id="list_header_search_kw_<?php echo $this->_tpl_vars['el_key']; ?>
">
      </FORM>
     </DIV>
    </TH>
   <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
  <TH align="center" colspan="3" width="16">Действия</TH>
 <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</TR>

<?php $this->assign('npp_base', $this->_tpl_vars['HMixed']->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')); ?>
<?php $this->assign('npp_base', ($this->_tpl_vars['npp_base']*$this->_tpl_vars['list']['page']-$this->_tpl_vars['npp_base'])); ?>
<?php $_from = $this->_tpl_vars['list']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fe'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['element']):
        $this->_foreach['fe']['iteration']++;
?>
 <TR class="<?php echo smarty_function_cycle(array('values' => "odd,even"), $this);?>
">
  <TD align="center">
   <?php $this->assign('npp', ($this->_foreach['fe']['iteration']+$this->_tpl_vars['npp_base'])); ?>
   <?php echo $this->_tpl_vars['npp']; ?>

  </TD>
 <?php $_from = $this->_tpl_vars['element']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['el_key'] => $this->_tpl_vars['el']):
?>
  <?php if ($this->_tpl_vars['el_key'] == 'active'): ?>
     <td align="center"><FORM method="post" action="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'update','id' => $this->_tpl_vars['element']['id']), $this);?>
" style="padding:0px; margin:0px;">
   <INPUT type="checkbox" <?php if ($this->_tpl_vars['el'] == 1): ?> checked<?php endif; ?> onclick="this.form.submit();">
   <INPUT type="hidden" name="active" value="<?php if ($this->_tpl_vars['el'] == 1): ?>0<?php else: ?>1<?php endif; ?>">
   </FORM></td>
  <?php elseif ($this->_tpl_vars['el_key'] != 'content'): ?>
   <TD align="center"><?php echo $this->_tpl_vars['el']; ?>
</TD>
  <?php endif; ?>
 <?php endforeach; endif; unset($_from); ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Actions/general.tpl", 'smarty_include_vars' => array('el' => $this->_tpl_vars['element'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 </TR>
<?php endforeach; endif; unset($_from); ?>
</TABLE>

<p>
<h4>Добавляем новую запись</h4>
<FORM method="post" action="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'insert'), $this);?>
">
<INPUT type="hidden" name="active" value="1">
<?php $this->assign('end', 0); ?>
<?php $_from = $this->_tpl_vars['list']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['element']):
?>
 <?php if ($this->_tpl_vars['end'] == 0): ?>
  <?php $this->assign('end', 1); ?>
  <?php $_from = $this->_tpl_vars['element']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['el_key'] => $this->_tpl_vars['el']):
?>
   <?php if ($this->_tpl_vars['el_key'] != 'id' && $this->_tpl_vars['el_key'] != 'parent'): ?>
    <?php echo $this->_tpl_vars['el_key']; ?>
: <INPUT type="text" size="20" name="<?php echo $this->_tpl_vars['el_key']; ?>
"><br />
   <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
 <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
<INPUT type="submit" value="Добавить">
</FORM>
</p>