<?php /* Smarty version 2.6.18, created on 2010-12-21 12:56:51
         compiled from core.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'core.tpl', 1, false),array('function', 'getUrl', 'core.tpl', 20, false),array('function', 'debug', 'core.tpl', 37, false),)), $this); ?>
<?php echo smarty_function_config_load(array('file' => "global.conf"), $this);?>
<HTML>
 <HEAD>
  <TITLE>Exhibition Global Marketing System</TITLE>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <LINK rel="stylesheet" href="<?php echo $this->_tpl_vars['document_root']; ?>
css/admin/admin_style.css" type="text/css" media="screen" />
  <LINK rel="stylesheet" href="<?php echo $this->_tpl_vars['document_root']; ?>
css/admin/jqModal.css" type="text/css" media="screen" />
  <LINK rel="stylesheet" href="<?php echo $this->_tpl_vars['document_root']; ?>
css/admin/ui.datepicker.css" type="text/css" media="screen" />
  <SCRIPT type="text/javascript" src="<?php echo $this->_tpl_vars['document_root']; ?>
js/adminGeneral.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="<?php echo $this->_tpl_vars['document_root']; ?>
js/adminAutocomplete.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="<?php echo $this->_tpl_vars['document_root']; ?>
js/jquery.js"></SCRIPT -->
  <!-- script src="http://cdn.jquerytools.org/1.0.2/jquery.tools.min.js"></script -->
  <SCRIPT type="text/javascript" src="<?php echo $this->_tpl_vars['document_root']; ?>
js/jqExtensions/jqModal.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="<?php echo $this->_tpl_vars['document_root']; ?>
js/jqExtensions/ui.datepicker.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="<?php echo $this->_tpl_vars['document_root']; ?>
js/jqExtensions/jquery.accordion.js"></SCRIPT>
 </HEAD>
 <BODY id="cms-wrapper">

<TABLE width="100%" align="center" cellspacing="0" border="0" style="position:static;">
 <TR>
  <TD align="left"><span class="blue_hilight">Вы вошли как: <?php echo $this->_tpl_vars['session_user']; ?>
 [<a href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_auth','action' => 'logout'), $this);?>
">Выйти</a>]</span></TD>
  <TD align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/listLanguages.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></TD>
 </TR>
</TABLE>
<br/>
<TABLE width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
 <TR>
  <TD valign="top" id="shelby_left_menu_td" width="<?php if (! empty ( $_COOKIE['shelby_left_menu'] ) && $_COOKIE['shelby_left_menu'] == 'hide'): ?>10<?php else: ?>218<?php endif; ?>">
   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/leftMenu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </TD>
  <td width="6" valign="top" style="padding-top:100px;"><a href="#" onclick="Shelby_Backend.leftMenuHS();"><img src="<?php echo $this->_tpl_vars['document_root']; ?>
images/admin/hide_button.gif" width="6" border="0"></a></td>
  <TD valign="top" id="shelby_content_td">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "controllers/".($this->_tpl_vars['user_params']['controller'])."/".($this->_tpl_vars['user_params']['action']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </TD>
 </TR>
</TABLE>

<?php if ($_GET['debug']): ?><?php echo smarty_function_debug(array(), $this);?>
<?php endif; ?>

</BODY>
</HTML>