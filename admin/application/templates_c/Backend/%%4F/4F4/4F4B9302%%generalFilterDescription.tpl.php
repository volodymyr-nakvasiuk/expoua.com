<?php /* Smarty version 2.6.18, created on 2010-12-21 13:08:40
         compiled from common/Lists/generalFilterDescription.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'common/Lists/generalFilterDescription.tpl', 12, false),)), $this); ?>
<?php if (! empty ( $this->_tpl_vars['list']['search'] )): ?>

 <?php $this->assign('tmp', ""); ?>

 <DIV style="padding-bottom:5px; float:right">
 <b><?php echo $this->_config[0]['vars']['activeFilter']; ?>
:</b><BR />
 <?php $_from = $this->_tpl_vars['list']['search']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fe'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['el']):
        $this->_foreach['fe']['iteration']++;
?>
  <?php echo $this->_tpl_vars['el']['column']; ?>
 <?php echo $this->_tpl_vars['el']['type']; ?>
 <?php echo $this->_tpl_vars['el']['value']; ?>
<BR />
  <?php $this->assign('tmp', ($this->_tpl_vars['tmp']).($this->_tpl_vars['el']['column']).($this->_tpl_vars['el']['type']).($this->_tpl_vars['el']['value'])); ?>
  <?php if (! ($this->_foreach['fe']['iteration'] == $this->_foreach['fe']['total'])): ?><?php $this->assign('tmp', ($this->_tpl_vars['tmp']).";"); ?><?php endif; ?>
 <?php endforeach; endif; unset($_from); ?>
 <a href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','del' => 'search'), $this);?>
"><?php echo $this->_config[0]['vars']['dropFilter']; ?>
</a>
 </DIV><DIV style="clear:both"></DIV>

 <SCRIPT type="text/javascript">
 Shelby_Backend.currentSearchFilter = '<?php echo $this->_tpl_vars['tmp']; ?>
';
 </SCRIPT>

<?php endif; ?>