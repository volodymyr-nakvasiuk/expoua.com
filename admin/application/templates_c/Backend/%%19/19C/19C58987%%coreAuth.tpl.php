<?php /* Smarty version 2.6.18, created on 2010-12-21 13:06:32
         compiled from coreAuth.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'coreAuth.tpl', 36, false),)), $this); ?>
<HTML>
 <HEAD>
  <TITLE>Exhibition Global Marketing System</TITLE>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8">
  <LINK rel="stylesheet" href="<?php echo $this->_tpl_vars['document_root']; ?>
css/admin/admin_style.css" type="text/css">
 </HEAD>
<?php echo '
<STYLE type="text/css">
HTML, BODY {
	height: 98%;
}
</STYLE>
'; ?>

 <BODY id="cms-wrapper">

<TABLE width="100%" align="center" cellspacing="0" border="0" style="position:static;">
 <TR>
  <TD align="right" valign="top"><img src="<?php echo $this->_tpl_vars['document_root']; ?>
images/admin/logo.gif"></TD>
 </TR>
</TABLE>
<TABLE height="100%" width="100%" border="0" cellpadding="8">
<TR><TD>
 <P>&nbsp;</P><P>&nbsp;</P><P>&nbsp;</P>

 <?php if (isset ( $this->_tpl_vars['last_action_result'] )): ?>
 <CENTER>
  <?php if ($this->_tpl_vars['last_action_result'] == 1): ?>
   <?php if ($this->_tpl_vars['user_params']['action'] == 'logout'): ?>
    Вы успешно вышли
   <?php else: ?>
    Вы успешно вошли
<SCRIPT type="text/javascript">
setTimeout("redir()", 200);
function redir() {
	<?php if ($this->_tpl_vars['user_params']['controller'] == 'admin_auth'): ?>
		document.location.href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_index','action' => 'index'), $this);?>
";
	<?php elseif ($this->_tpl_vars['user_params']['controller'] == 'sab_operator_auth'): ?>
		document.location.href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'sab_operator_index','action' => 'index'), $this);?>
";
	<?php elseif ($this->_tpl_vars['user_params']['controller'] == 'sab_organizer_auth'): ?>
		document.location.href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'sab_organizer_index','action' => 'index'), $this);?>
";
	<?php endif; ?>
}
</SCRIPT>
   <?php endif; ?>
  <?php elseif ($this->_tpl_vars['last_action_result'] == -1): ?>
   Такого пользователя не существует
  <?php elseif ($this->_tpl_vars['last_action_result'] == -3): ?>
   Введен неверный пароль
  <?php endif; ?>
 </CENTER>
 <?php endif; ?>

 <P>&nbsp;</P>

 <FORM method="post" action="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'login'), $this);?>
">

<TABLE align="center" cellspacing="0" border="0">
 <TR>
  <TH colspan="2" align="center"><?php if ($this->_tpl_vars['user_params']['controller'] == 'sab_organizer_auth'): ?>
  Организаторам выставок
 <?php else: ?>
  Authentification
<?php endif; ?></TH>
 </TR>
 <TR>
  <TD>Логин: </TD><TD><INPUT type="text" size="20" name="login"></TD>
 </TR>
 <TR>
  <TD>Пароль: </TD><TD><INPUT type="password" name="passwd"></TD>
 </TR>
 <TR>
  <TD colspan="2" align="center"><INPUT type="submit" value="Ok"></TD>
 </TR>
</TABLE>

</FORM>
</TD></TR>
<TR><TD height="100%" valign="bottom" align="right">
 <!-- <a href="http://framework.zend.com/" target="zfwindow"><img src="http://framework.zend.com/images/PoweredBy_ZF_4LightBG.png" border="0"></a> -->
</TD></TR>
</TABLE>

</BODY>
</HTML>