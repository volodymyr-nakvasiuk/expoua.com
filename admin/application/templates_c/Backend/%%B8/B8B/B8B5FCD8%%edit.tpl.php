<?php /* Smarty version 2.6.18, created on 2010-12-21 14:58:34
         compiled from controllers/admin_acl_admins/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'controllers/admin_acl_admins/edit.tpl', 3, false),)), $this); ?>
<h4>Редактируем пользователя админки</h4>

<FORM action="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'update'), $this);?>
" method="POST">

<TABLE border="1" style="border-collapse:collapse;" cellpadding="5">

<TR><TD valign="top">

 Login: <INPUT type="text" name="login" value="<?php echo $this->_tpl_vars['entry']['login']; ?>
"><br />
 Имя: <INPUT type="text" name="name" value="<?php echo $this->_tpl_vars['entry']['name']; ?>
"><br />
 Дата добавления: <?php echo $this->_tpl_vars['entry']['time_added']; ?>
<br />
 Дата последнего входа: <?php echo $this->_tpl_vars['entry']['time_lastlogin']; ?>
<br /><br /><br />
 <center><INPUT type="submit" value="Изменить" /></center>

</TD><TD style="padding-left:10px;" valign="top">

<b>Группы пользователя:</b><br />
<?php $_from = $this->_tpl_vars['list_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['el']):
?>
 <INPUT type="checkbox" name="groups_ids[<?php echo $this->_tpl_vars['el']['id']; ?>
]" value="<?php echo $this->_tpl_vars['el']['id']; ?>
"<?php if (! empty ( $this->_tpl_vars['entry']['groups'][$this->_tpl_vars['el']['id']] )): ?> checked<?php endif; ?>> <LABEL><?php echo $this->_tpl_vars['el']['name']; ?>
</LABEL><br />
<?php endforeach; endif; unset($_from); ?>

</TD></TR>
</TABLE>
</FORM>

<b>Изменить пароль</b>
<FORM action="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'update'), $this);?>
" method="POST">
<TABLE border="1" style="border-collapse:collapse;">
 <TR>
  <TD>Новый пароль: <INPUT type="text" size="20" name="passwd"></TD>
 </TR>
 <TR>
  <TD><INPUT type="submit" value="Изменить"></TD>
 </TR>
</TABLE>
</FORM>

<A href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'list'), $this);?>
">Вернуться к списку</A>