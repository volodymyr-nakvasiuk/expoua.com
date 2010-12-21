<?php /* Smarty version 2.6.18, created on 2010-12-21 13:09:57
         compiled from controllers/admin_ep_brands/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'controllers/admin_ep_brands/edit.tpl', 9, false),array('modifier', 'escape', 'controllers/admin_ep_brands/edit.tpl', 54, false),)), $this); ?>
<SCRIPT type="text/javascript" language="javascript" src="<?php echo $this->_tpl_vars['document_root']; ?>
js/adminListHelper.js"></SCRIPT>

<SCRIPT>

objOrganizersList = Shelby_Backend.ListHelper.cloneObject('objOrganizersList');

objOrganizersList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objOrganizersList.returnFieldId = 'organizers_id';
objOrganizersList.feedUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_organizers','action' => 'list','feed' => 'json'), $this);?>
';
objOrganizersList.writeForm();

</SCRIPT>

<FORM method="post" action="<?php if (isset ( $this->_tpl_vars['formActionUrl'] )): ?><?php echo $this->_tpl_vars['formActionUrl']; ?>
<?php else: ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'update'), $this);?>
<?php endif; ?>">

<h4><?php if (isset ( $this->_tpl_vars['formTitle'] )): ?><?php echo $this->_tpl_vars['formTitle']; ?>
<?php else: ?>Редактируем бренд<?php endif; ?></h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Организатор:</TD>
  <TD><INPUT type="text" size="5" name="organizers_id" id="organizers_id" value="<?php echo $this->_tpl_vars['entry']['organizers_id']; ?>
"> <INPUT type="button" onclick="objOrganizersList.showPopUp();" value="Выбрать"> <SPAN id="organizers_id_name"><?php echo $this->_tpl_vars['entry']['organizer_name']; ?>
</SPAN></TD>
 </TR>
 <TR>
  <TD>Основная категория:</TD>
  <TD>
   <SELECT name="brands_categories_id">
    <?php $_from = $this->_tpl_vars['list_categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cat']):
?>
     <OPTION value="<?php echo $this->_tpl_vars['cat']['id']; ?>
"<?php if ($this->_tpl_vars['cat']['id'] == $this->_tpl_vars['entry']['brands_categories_id']): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['cat']['name']; ?>
</OPTION>
    <?php endforeach; endif; unset($_from); ?>
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Дополнительные категории:</TD>
  <TD>
  <DIV style="overflow:auto; height:350px;">
  <?php echo ''; ?><?php $_from = $this->_tpl_vars['list_categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cat']):
?><?php echo '<INPUT type="checkbox" name="cat['; ?><?php echo $this->_tpl_vars['cat']['id']; ?><?php echo ']" id="cat'; ?><?php echo $this->_tpl_vars['cat']['id']; ?><?php echo '" value="'; ?><?php echo $this->_tpl_vars['cat']['id']; ?><?php echo '"'; ?><?php if (isset ( $this->_tpl_vars['entry']['categories'][$this->_tpl_vars['cat']['id']] )): ?><?php echo ' checked'; ?><?php endif; ?><?php echo ' /> <b>'; ?><?php echo $this->_tpl_vars['cat']['name']; ?><?php echo '</b><BR />'; ?><?php $_from = $this->_tpl_vars['cat']['subcats']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subcat']):
?><?php echo '&nbsp; &nbsp; <INPUT type="checkbox" name="subcat['; ?><?php echo $this->_tpl_vars['subcat']['id']; ?><?php echo ']" value="'; ?><?php echo $this->_tpl_vars['subcat']['id']; ?><?php echo '"'; ?><?php if (isset ( $this->_tpl_vars['entry']['sub_categories'][$this->_tpl_vars['subcat']['id']] )): ?><?php echo ' checked'; ?><?php endif; ?><?php echo ' onclick="$(\'#cat'; ?><?php echo $this->_tpl_vars['cat']['id']; ?><?php echo '\').attr(\'checked\', \'checked\');" /> '; ?><?php echo $this->_tpl_vars['subcat']['name']; ?><?php echo '<BR />'; ?><?php endforeach; endif; unset($_from); ?><?php echo ''; ?><?php endforeach; endif; unset($_from); ?><?php echo ''; ?>

  </DIV>
  </TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="80" name="name" value="<?php echo $this->_tpl_vars['entry']['name']; ?>
"></TD>
 </TR>
 <TR>
  <TD colspan="2">Расширенное название:<BR />
   <TEXTAREA name="name_extended" id="name_extended" style="width:95%; height:50px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['entry']['name_extended'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</TEXTAREA>
  </TD>
 </TR>

 <TR>
  <TD>Мертвый:</TD>
  <TD>
   <INPUT type="checkbox"<?php if ($this->_tpl_vars['entry']['dead'] == 1): ?> checked="checked"<?php endif; ?> onclick="Shelby_Backend.objects_multi_checkbox('dead_id', this.checked);" />
   <INPUT type="hidden" name="dead" id="dead_id" value="<?php echo $this->_tpl_vars['entry']['dead']; ?>
" />
  </TD>
 </TR>
 <TR>
  <TD>Email для запросов:</TD>
  <TD><INPUT type="text" size="20" name="email_requests" value="<?php echo $this->_tpl_vars['entry']['email_requests']; ?>
"></TD>
 </TR>

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="<?php if (isset ( $this->_tpl_vars['formSubmitName'] )): ?><?php echo $this->_tpl_vars['formSubmitName']; ?>
<?php else: ?>Обновить<?php endif; ?>"></TD></TR>
</TABLE>

</FORM>