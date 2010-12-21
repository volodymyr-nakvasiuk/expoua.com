<?php /* Smarty version 2.6.18, created on 2010-12-21 13:08:40
         compiled from common/generalPaging.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'common/generalPaging.tpl', 13, false),)), $this); ?>
<?php if ($this->_tpl_vars['pages'] > 1): ?>
<?php echo '<div align="center" style="padding-bottom:5px;">'; ?><?php $this->assign('start', 0); ?><?php echo ''; ?><?php if (( $this->_tpl_vars['page'] > 20 )): ?><?php echo ''; ?><?php $this->assign('start', ($this->_tpl_vars['page']-10)); ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['start'] > 0): ?><?php echo '-&nbsp;'; ?><?php unset($this->_sections['scroll']);
$this->_sections['scroll']['name'] = 'scroll';
$this->_sections['scroll']['loop'] = is_array($_loop=$this->_tpl_vars['start']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['scroll']['step'] = ((int)10) == 0 ? 1 : (int)10;
$this->_sections['scroll']['show'] = true;
$this->_sections['scroll']['max'] = $this->_sections['scroll']['loop'];
$this->_sections['scroll']['start'] = $this->_sections['scroll']['step'] > 0 ? 0 : $this->_sections['scroll']['loop']-1;
if ($this->_sections['scroll']['show']) {
    $this->_sections['scroll']['total'] = min(ceil(($this->_sections['scroll']['step'] > 0 ? $this->_sections['scroll']['loop'] - $this->_sections['scroll']['start'] : $this->_sections['scroll']['start']+1)/abs($this->_sections['scroll']['step'])), $this->_sections['scroll']['max']);
    if ($this->_sections['scroll']['total'] == 0)
        $this->_sections['scroll']['show'] = false;
} else
    $this->_sections['scroll']['total'] = 0;
if ($this->_sections['scroll']['show']):

            for ($this->_sections['scroll']['index'] = $this->_sections['scroll']['start'], $this->_sections['scroll']['iteration'] = 1;
                 $this->_sections['scroll']['iteration'] <= $this->_sections['scroll']['total'];
                 $this->_sections['scroll']['index'] += $this->_sections['scroll']['step'], $this->_sections['scroll']['iteration']++):
$this->_sections['scroll']['rownum'] = $this->_sections['scroll']['iteration'];
$this->_sections['scroll']['index_prev'] = $this->_sections['scroll']['index'] - $this->_sections['scroll']['step'];
$this->_sections['scroll']['index_next'] = $this->_sections['scroll']['index'] + $this->_sections['scroll']['step'];
$this->_sections['scroll']['first']      = ($this->_sections['scroll']['iteration'] == 1);
$this->_sections['scroll']['last']       = ($this->_sections['scroll']['iteration'] == $this->_sections['scroll']['total']);
?><?php echo ''; ?><?php $this->assign('tmp', $this->_sections['scroll']['index']+1); ?><?php echo '<a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','page' => $this->_tpl_vars['tmp']), $this);?><?php echo '" class="'; ?><?php if ($this->_tpl_vars['tmp'] != $this->_tpl_vars['page']): ?><?php echo 'page'; ?><?php else: ?><?php echo 'current_page'; ?><?php endif; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['tmp']; ?><?php echo '</a> -&nbsp;'; ?><?php endfor; endif; ?><?php echo ''; ?><?php endif; ?><?php echo '-&nbsp;'; ?><?php unset($this->_sections['scroll']);
$this->_sections['scroll']['name'] = 'scroll';
$this->_sections['scroll']['loop'] = is_array($_loop=$this->_tpl_vars['pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['scroll']['start'] = (int)$this->_tpl_vars['start'];
$this->_sections['scroll']['max'] = (int)20;
$this->_sections['scroll']['show'] = true;
if ($this->_sections['scroll']['max'] < 0)
    $this->_sections['scroll']['max'] = $this->_sections['scroll']['loop'];
$this->_sections['scroll']['step'] = 1;
if ($this->_sections['scroll']['start'] < 0)
    $this->_sections['scroll']['start'] = max($this->_sections['scroll']['step'] > 0 ? 0 : -1, $this->_sections['scroll']['loop'] + $this->_sections['scroll']['start']);
else
    $this->_sections['scroll']['start'] = min($this->_sections['scroll']['start'], $this->_sections['scroll']['step'] > 0 ? $this->_sections['scroll']['loop'] : $this->_sections['scroll']['loop']-1);
if ($this->_sections['scroll']['show']) {
    $this->_sections['scroll']['total'] = min(ceil(($this->_sections['scroll']['step'] > 0 ? $this->_sections['scroll']['loop'] - $this->_sections['scroll']['start'] : $this->_sections['scroll']['start']+1)/abs($this->_sections['scroll']['step'])), $this->_sections['scroll']['max']);
    if ($this->_sections['scroll']['total'] == 0)
        $this->_sections['scroll']['show'] = false;
} else
    $this->_sections['scroll']['total'] = 0;
if ($this->_sections['scroll']['show']):

            for ($this->_sections['scroll']['index'] = $this->_sections['scroll']['start'], $this->_sections['scroll']['iteration'] = 1;
                 $this->_sections['scroll']['iteration'] <= $this->_sections['scroll']['total'];
                 $this->_sections['scroll']['index'] += $this->_sections['scroll']['step'], $this->_sections['scroll']['iteration']++):
$this->_sections['scroll']['rownum'] = $this->_sections['scroll']['iteration'];
$this->_sections['scroll']['index_prev'] = $this->_sections['scroll']['index'] - $this->_sections['scroll']['step'];
$this->_sections['scroll']['index_next'] = $this->_sections['scroll']['index'] + $this->_sections['scroll']['step'];
$this->_sections['scroll']['first']      = ($this->_sections['scroll']['iteration'] == 1);
$this->_sections['scroll']['last']       = ($this->_sections['scroll']['iteration'] == $this->_sections['scroll']['total']);
?><?php echo ''; ?><?php $this->assign('tmp', $this->_sections['scroll']['index']+1); ?><?php echo '<a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','page' => $this->_tpl_vars['tmp']), $this);?><?php echo '" class="'; ?><?php if ($this->_tpl_vars['tmp'] != $this->_tpl_vars['page']): ?><?php echo 'page'; ?><?php else: ?><?php echo 'current_page'; ?><?php endif; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['tmp']; ?><?php echo '</a> -&nbsp;'; ?><?php endfor; endif; ?><?php echo ''; ?><?php if (( $this->_tpl_vars['start']+20 ) < $this->_tpl_vars['pages']): ?><?php echo '-&nbsp;'; ?><?php unset($this->_sections['scroll']);
$this->_sections['scroll']['name'] = 'scroll';
$this->_sections['scroll']['loop'] = is_array($_loop=$this->_tpl_vars['pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['scroll']['start'] = (int)($this->_tpl_vars['start']+20);
$this->_sections['scroll']['step'] = ((int)10) == 0 ? 1 : (int)10;
$this->_sections['scroll']['show'] = true;
$this->_sections['scroll']['max'] = $this->_sections['scroll']['loop'];
if ($this->_sections['scroll']['start'] < 0)
    $this->_sections['scroll']['start'] = max($this->_sections['scroll']['step'] > 0 ? 0 : -1, $this->_sections['scroll']['loop'] + $this->_sections['scroll']['start']);
else
    $this->_sections['scroll']['start'] = min($this->_sections['scroll']['start'], $this->_sections['scroll']['step'] > 0 ? $this->_sections['scroll']['loop'] : $this->_sections['scroll']['loop']-1);
if ($this->_sections['scroll']['show']) {
    $this->_sections['scroll']['total'] = min(ceil(($this->_sections['scroll']['step'] > 0 ? $this->_sections['scroll']['loop'] - $this->_sections['scroll']['start'] : $this->_sections['scroll']['start']+1)/abs($this->_sections['scroll']['step'])), $this->_sections['scroll']['max']);
    if ($this->_sections['scroll']['total'] == 0)
        $this->_sections['scroll']['show'] = false;
} else
    $this->_sections['scroll']['total'] = 0;
if ($this->_sections['scroll']['show']):

            for ($this->_sections['scroll']['index'] = $this->_sections['scroll']['start'], $this->_sections['scroll']['iteration'] = 1;
                 $this->_sections['scroll']['iteration'] <= $this->_sections['scroll']['total'];
                 $this->_sections['scroll']['index'] += $this->_sections['scroll']['step'], $this->_sections['scroll']['iteration']++):
$this->_sections['scroll']['rownum'] = $this->_sections['scroll']['iteration'];
$this->_sections['scroll']['index_prev'] = $this->_sections['scroll']['index'] - $this->_sections['scroll']['step'];
$this->_sections['scroll']['index_next'] = $this->_sections['scroll']['index'] + $this->_sections['scroll']['step'];
$this->_sections['scroll']['first']      = ($this->_sections['scroll']['iteration'] == 1);
$this->_sections['scroll']['last']       = ($this->_sections['scroll']['iteration'] == $this->_sections['scroll']['total']);
?><?php echo ''; ?><?php $this->assign('tmp', $this->_sections['scroll']['index']+1); ?><?php echo '<a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','page' => $this->_tpl_vars['tmp']), $this);?><?php echo '" class="'; ?><?php if ($this->_tpl_vars['tmp'] != $this->_tpl_vars['page']): ?><?php echo 'page'; ?><?php else: ?><?php echo 'current_page'; ?><?php endif; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['tmp']; ?><?php echo '</a> -&nbsp;'; ?><?php endfor; endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['tmp'] != $this->_tpl_vars['pages']): ?><?php echo '<a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','page' => $this->_tpl_vars['pages']), $this);?><?php echo '" class="page">'; ?><?php echo $this->_tpl_vars['pages']; ?><?php echo '</a> -'; ?><?php endif; ?><?php echo ''; ?><?php endif; ?><?php echo '</div>'; ?>

<?php endif; ?>