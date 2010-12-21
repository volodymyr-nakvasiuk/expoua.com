<?php /* Smarty version 2.6.18, created on 2010-12-21 15:03:10
         compiled from controllers/admin_acl_admins_groups/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'controllers/admin_acl_admins_groups/list.tpl', 21, false),array('function', 'getUrl', 'controllers/admin_acl_admins_groups/list.tpl', 35, false),)), $this); ?>
<h4>Группы пользователей</h4>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/generalFilterDescription.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (empty ( $this->_tpl_vars['list']['data'] )): ?>
<p>Записи отсутсвуют</p>

<?php else: ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/generalPaging.tpl", 'smarty_include_vars' => array('pages' => $this->_tpl_vars['list']['pages'],'page' => $this->_tpl_vars['list']['page'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<TABLE border="0" class="list" width="100%">

 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('width' => '30','name' => 'id','descr' => 'Id')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('name' => 'name','descr' => "Имя")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementAutocomplete.tpl", 'smarty_include_vars' => array('name' => 'parent_group_id','controller' => 'admin_acl_admins_groups','descr' => "Родительская группа")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('width' => '30','name' => 'description','descr' => "Описание")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <TH align="center" colspan="2">Действия</TH>

<?php $_from = $this->_tpl_vars['list']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fe'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['el']):
        $this->_foreach['fe']['iteration']++;
?>
 <TR class="<?php echo smarty_function_cycle(array('values' => "odd,even"), $this);?>
">
 <TD align="center"><?php echo $this->_tpl_vars['el']['id']; ?>
</TD>
 <TD><?php echo $this->_tpl_vars['el']['name']; ?>
</TD>
 <TD align="center"><?php if (! empty ( $this->_tpl_vars['el']['parent_group_id'] )): ?><?php echo $this->_tpl_vars['list']['data'][$this->_tpl_vars['el']['parent_group_id']]['name']; ?>
<?php else: ?>(не установлена)<?php endif; ?></TD>
 <TD><?php echo $this->_tpl_vars['el']['description']; ?>
</TD>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Actions/general.tpl", 'smarty_include_vars' => array('isFirst' => ($this->_foreach['fe']['iteration'] <= 1),'isLast' => ($this->_foreach['fe']['iteration'] == $this->_foreach['fe']['total']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 </TR>
<?php endforeach; endif; unset($_from); ?>
</TABLE>

<?php endif; ?>

<p>Добавляем новую запись</p>

<FORM action="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'insert'), $this);?>
" method="POST">
 Название: <INPUT type="text" name="name"><br />
 Родительская группа:
  <SELECT name="parent_group_id">
   <OPTION value="">(не установлена)</OPTION>
   <?php $_from = $this->_tpl_vars['list']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['el']):
?>
    <OPTION value="<?php echo $this->_tpl_vars['el']['id']; ?>
"><?php echo $this->_tpl_vars['el']['name']; ?>
</OPTION>
   <?php endforeach; endif; unset($_from); ?>
  </SELECT><BR />
 Описание: <BR /><TEXTAREA name="description" cols="50" rows="5"></TEXTAREA><br />
 <INPUT type="submit" value="Добавить">
</FORM>