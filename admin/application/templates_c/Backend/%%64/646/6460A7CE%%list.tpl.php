<?php /* Smarty version 2.6.18, created on 2010-12-21 14:57:59
         compiled from controllers/admin_acl_admins/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'controllers/admin_acl_admins/list.tpl', 16, false),array('function', 'cycle', 'controllers/admin_acl_admins/list.tpl', 21, false),)), $this); ?>
<h4>Список пользователей админ-панели</h4>

<?php if (empty ( $this->_tpl_vars['list']['data'] )): ?>
<p>Записи отсутсвуют</p>

<?php else: ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/generalPaging.tpl", 'smarty_include_vars' => array('pages' => $this->_tpl_vars['list']['pages'],'page' => $this->_tpl_vars['list']['page'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<TABLE class="list" width="100%">

 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('width' => '30','name' => 'id','descr' => 'Id')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementCheckbox.tpl", 'smarty_include_vars' => array('width' => '30','name' => 'active','descr' => 'A')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('name' => 'login','descr' => "Логин")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('name' => 'name','descr' => "Имя")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <TH align="center"><a href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','sort' => $this->_tpl_vars['HMixed']->getSortOrder('objects_id',$this->_tpl_vars['list']['sort_by'])), $this);?>
">Последний вход</a></TH>
 <TH align="center">Группы</TH>
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
 <TD align="center">
  <FORM method="post" action="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'update','id' => $this->_tpl_vars['el']['id']), $this);?>
" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" <?php if ($this->_tpl_vars['el']['active'] == 1): ?> checked<?php endif; ?> onclick="this.form.submit();">
  <INPUT type="hidden" name="active" value="<?php if ($this->_tpl_vars['el']['active'] == 1): ?>0<?php else: ?>1<?php endif; ?>">
  </FORM>
 </TD>
 <TD><?php echo $this->_tpl_vars['el']['login']; ?>
</TD>
 <TD><?php echo $this->_tpl_vars['el']['name']; ?>
</TD>
 <TD><?php echo $this->_tpl_vars['el']['time_lastlogin']; ?>
</TD>
 <TD>
  <?php $_from = $this->_tpl_vars['el']['groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fe_grp'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_grp']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['grp_el']):
        $this->_foreach['fe_grp']['iteration']++;
?>
   <?php echo $this->_tpl_vars['list_groups'][$this->_tpl_vars['grp_el']]['name']; ?>
<?php if (! ($this->_foreach['fe_grp']['iteration'] == $this->_foreach['fe_grp']['total'])): ?>, <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
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

<p>
Добавляем новую запись
<FORM method="post" action="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'insert'), $this);?>
">
 Логин: <INPUT type="text" size="10" name="login" /><br />
 Имя: <INPUT type="text" size="50" name="name" /><br />
<BR />

<INPUT type="submit" value="Добавить">
</FORM>
</p>